resource "aws_ecs_cluster" "cluster" {
  name = "${var.namespace}"
}

resource "aws_cloudwatch_log_group" "db" {
  name = "${var.namespace}-db-logs"
}

data aws_iam_policy_document ec2_role {

  statement {
    actions = ["sts:AssumeRole"]
    principals {
      identifiers = ["ec2.amazonaws.com"]
      type        = "Service"
    }
  }
}

resource aws_iam_role ec2_role {
  assume_role_policy = "${data.aws_iam_policy_document.ec2_role.json}"
}

data aws_iam_policy_document ec2_ecs_role_policy {

  statement {
    actions   = [
      "ecs:CreateCluster",
      "ecs:DeregisterContainerInstance",
      "ecs:DiscoverPollEndpoint",
      "ecs:Poll",
      "ecs:RegisterContainerInstance",
      "ecs:StartTelemetrySession",
      "ecs:Submit*",
      "ecr:GetAuthorizationToken",
      "ecr:BatchCheckLayerAvailability",
      "ecr:GetDownloadUrlForLayer",
      "ecr:BatchGetImage",
      "logs:CreateLogStream",
      "logs:PutLogEvents"
    ]
    resources = [
      "*"
    ]
  }
}

resource aws_iam_role_policy ec2_ecs_role_policy {
  name   = "${var.namespace}-ec2-ecs"
  role   = "${aws_iam_role.ec2_role.id}"
  policy = "${data.aws_iam_policy_document.ec2_ecs_role_policy.json}"
}

resource aws_iam_instance_profile ec2_instance_profile {
  name = "${var.namespace}-ec2-instance-profile"
  role = "${aws_iam_role.ec2_role.name}"
}

resource "tls_private_key" "private_key" {
  algorithm = "RSA"
  rsa_bits  = 4096
}

resource "aws_key_pair" "kp" {
  key_name   = "${var.namespace}"
  public_key = "${tls_private_key.private_key.public_key_openssh}"
}

resource aws_instance ecs {
  ami                         = "ami-bc04d5de"
  instance_type               = "t2.micro"
  subnet_id                   = "${aws_default_subnet.subnet-a.id}"
  associate_public_ip_address = true
  iam_instance_profile        = "${aws_iam_instance_profile.ec2_instance_profile.id}"
  vpc_security_group_ids      = ["${module.prod_service.service_sg_id}"]
  key_name                    = "${aws_key_pair.kp.key_name}"
  user_data                   = <<EOF
  #!/bin/bash
  echo ECS_CLUSTER=${aws_ecs_cluster.cluster.name} >> /etc/ecs/ecs.config
  EOF
  tags {
    Name = "${var.namespace}"
  }

  provisioner file {
    source      = "./instance-provisioning-files/awslogs.conf"
    destination = "/tmp/awslogs.conf"

    connection {
      type        = "ssh"
      user        = "ec2-user"
      private_key = "${tls_private_key.private_key.private_key_pem}"
    }
  }

  provisioner remote-exec {
    inline = [
      "sudo yum install -y awslogs",
      "sudo mv /etc/awslogs/awslogs.conf /etc/awslogs/awslogs.conf.bak",
      "sudo mv /tmp/awslogs.conf /etc/awslogs/awslogs.conf",
      "sudo sed -i -e \"s/us-east-1/${var.region}/g\" /etc/awslogs/awscli.conf",
      "sudo sed -i -e \"s/{cluster}/${aws_ecs_cluster.cluster.name}/g\" /etc/awslogs/awslogs.conf",
      "sudo sed -i -e \"s/{container_instance_id}/${aws_instance.ecs.id}/g\" /etc/awslogs/awslogs.conf",
      "sudo service awslogs start",
      "sudo chkconfig awslogs on",
      "sudo chown -R ec2-user:ec2-user /ecs"
    ]

    connection {
      type        = "ssh"
      user        = "ec2-user"
      private_key = "${tls_private_key.private_key.private_key_pem}"
    }
  }
}

resource "aws_security_group" "inbound_sg" {
  vpc_id      = "${aws_default_vpc.default_vpc.id}"
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

resource "aws_alb" "alb" {
  name            = "${var.namespace}-alb"
  subnets         = ["${aws_default_subnet.subnet-a.id}", "${aws_default_subnet.subnet-b.id}"]
  security_groups = ["${aws_security_group.inbound_sg.id}"]
}

resource "aws_alb_listener" "alb_listener_prod" {
  load_balancer_arn = "${aws_alb.alb.arn}"
  port              = "80"
  protocol          = "HTTP"

  default_action {
    type             = "forward"
    target_group_arn = "${module.prod_service.service_target_group_arn}"
  }
}

resource "aws_alb_listener" "alb_listener_stage" {
  load_balancer_arn = "${aws_alb.alb.arn}"
  port              = "8080"
  protocol          = "HTTP"

  default_action {
    type             = "forward"
    target_group_arn = "${module.stage_service.service_target_group_arn}"
  }
}

module "prod_service" {
  source      = "./service"
  namespace   = "${var.namespace}-prod"
  region      = "${var.region}"
  vpc_id      = "${aws_default_vpc.default_vpc.id}"
  subnet_a_id = "${aws_default_subnet.subnet-a.id}"
  subnet_b_id = "${aws_default_subnet.subnet-b.id}"
  cluster_id  = "${aws_ecs_cluster.cluster.id}"
  db_name     = "prod"
  db_username = "${aws_db_instance.db.username}"
  db_password = "${aws_db_instance.db.password}"
  db_endpoint = "${aws_db_instance.db.endpoint}"
  host_port   = "80"
}

module "stage_service" {
  source      = "./service"
  namespace   = "${var.namespace}-stage"
  region      = "${var.region}"
  vpc_id      = "${aws_default_vpc.default_vpc.id}"
  subnet_a_id = "${aws_default_subnet.subnet-a.id}"
  subnet_b_id = "${aws_default_subnet.subnet-b.id}"
  cluster_id  = "${aws_ecs_cluster.cluster.id}"
  db_name     = "stage"
  db_username = "${aws_db_instance.db.username}"
  db_password = "${aws_db_instance.db.password}"
  db_endpoint = "${aws_db_instance.db.endpoint}"
  host_port   = "8080"
}
