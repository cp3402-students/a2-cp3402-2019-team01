variable "namespace" {}

variable "region" {}

variable "cluster_id" {}

variable "subnet_a_id" {}

variable "subnet_b_id" {}

variable "vpc_id" {}

variable "db_name" {}

variable "db_endpoint" {}

variable "db_username" {}

variable "db_password" {}

variable "host_port" {}


resource "aws_security_group" "service" {
  vpc_id = "${var.vpc_id}"
  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

resource "random_string" "alb_name" {
  length  = 6
  special = false
}

resource "aws_alb_target_group" "alb_target_group" {
  name_prefix = "${random_string.alb_name.result}"
  port        = "${var.host_port}"
  protocol    = "HTTP"
  vpc_id      = "${var.vpc_id}"
  target_type = "instance"

  lifecycle {
    create_before_destroy = true
  }

  health_check {
    path     = "/"
    protocol = "HTTP"
    matcher  = "301,302,200"
    port     = "${var.host_port}"
  }
}

resource "aws_security_group" "inbound_sg" {
  vpc_id      = "${var.vpc_id}"
  name        = "${var.namespace}-inbound-sg"
  description = "Allow HTTP from Anywhere into ALB"

  ingress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

resource "aws_cloudwatch_log_group" "logs" {
  name = "${var.namespace}-logs"
}

resource "aws_iam_role" "ecs_execution_role" {
  name               = "${var.namespace}_ecs_execution_role"
  assume_role_policy = <<EOF
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "",
      "Effect": "Allow",
      "Principal": {
        "Service": "ecs-tasks.amazonaws.com"
      },
      "Action": "sts:AssumeRole"
    }
  ]
}
EOF
}

resource "aws_iam_role_policy" "ecs_execution_role_policy" {
  name   = "${var.namespace}-role-policy"
  role   = "${aws_iam_role.ecs_execution_role.id}"
  policy = <<EOF
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Effect": "Allow",
      "Action": [
        "ecr:GetAuthorizationToken",
        "ecr:BatchCheckLayerAvailability",
        "ecr:GetDownloadUrlForLayer",
        "ecr:BatchGetImage",
        "logs:CreateLogStream",
        "logs:PutLogEvents"
      ],
      "Resource": "*"
    }
  ]
}
EOF
}

resource "aws_iam_role" "task" {
  name               = "${var.namespace}-task"
  assume_role_policy = <<EOF
{
  "Version": "2012-10-17",
  "Statement": [
    {
      "Sid": "",
      "Effect": "Allow",
      "Principal": {
        "Service": "ecs-tasks.amazonaws.com"
      },
      "Action": "sts:AssumeRole"
    }
  ]
}
EOF
}

resource "aws_iam_role_policy" "task" {
  name   = "${var.namespace}-task-policy"
  role   = "${aws_iam_role.task.id}"
  policy = <<EOF
{
    "Version": "2012-10-17",
    "Statement": [
      {
        "Effect": "Deny",
        "Action": [ "*" ],
        "Resource": "*"
      }
    ]
}
EOF
}
resource "aws_ecs_service" "service" {
  name                               = "${var.namespace}"
  task_definition                    = "${aws_ecs_task_definition.service.arn}"
  launch_type                        = "EC2"
  cluster                            = "${var.cluster_id}"
  desired_count                      = 1
  deployment_minimum_healthy_percent = 0
  deployment_maximum_percent         = 100

  load_balancer {
    target_group_arn = "${aws_alb_target_group.alb_target_group.arn}"
    container_name   = "${var.namespace}"
    container_port   = "80"
  }
}

resource "aws_ecs_task_definition" "service" {
  family                   = "${var.namespace}"
  requires_compatibilities = ["EC2"]
  network_mode             = "bridge"
  cpu                      = "256"
  memory                   = "256"
  execution_role_arn       = "${aws_iam_role.ecs_execution_role.arn}"
  task_role_arn            = "${aws_iam_role.task.arn}"

  volume {
    name      = "${var.namespace}-storage"
    host_path = "/ecs/${var.namespace}-storage"
  }

  container_definitions = <<EOF
[
  {
    "name": "${var.namespace}",
    "mountPoints": [
      {
        "sourceVolume": "${var.namespace}-storage",
        "containerPath": "/var/www/html"
      }
    ],
    "image": "wordpress:latest",
    "portMappings": [
      {
        "containerPort": 80,
        "hostPort": ${var.host_port}
      }
    ],
    "environment": [
       {
          "name": "WORDPRESS_DB_NAME",
          "value": "${var.db_name}"
        },
        {
          "name": "WORDPRESS_DB_PASSWORD",
          "value": "${var.db_password}"
        },
        {
          "name": "WORDPRESS_DB_USER",
          "value": "${var.db_username}"
        },
        {
          "name": "WORDPRESS_DB_HOST",
          "value": "${var.db_endpoint}"
        }
    ],
    "cpu": 256,
    "memory": 256,
    "logConfiguration": {
      "logDriver": "awslogs",
      "options": {
        "awslogs-group": "${aws_cloudwatch_log_group.logs.name}",
        "awslogs-region": "${var.region}",
        "awslogs-stream-prefix": "${aws_cloudwatch_log_group.logs.name}"
      }
    }
  }
]
EOF
}

output "service_sg_id" {
  value = "${aws_security_group.service.id}"
}

output "service_target_group_arn" {
  value = "${aws_alb_target_group.alb_target_group.arn}"
}
