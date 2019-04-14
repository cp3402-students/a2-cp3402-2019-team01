resource "random_string" "db_password" {
  length  = 32
  special = false
}

resource "random_string" "db_username" {
  length  = 32
  special = false
}

resource "aws_db_subnet_group" "db-subnet-group" {
  subnet_ids = ["${aws_default_subnet.subnet-a.id}", "${aws_default_subnet.subnet-b.id}"]
}

resource "aws_security_group" "db-public" {
  vpc_id = "${aws_default_vpc.default_vpc.id}"

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

resource "aws_db_instance" "db" {
  instance_class         = "db.t2.micro"
  storage_type           = "gp2"
  engine                 = "mysql"
  engine_version         = "5.7"
  allocated_storage      = 10
  apply_immediately      = true
  publicly_accessible    = true
  username               = "${random_string.db_username.result}"
  password               = "${random_string.db_password.result}"
  vpc_security_group_ids = ["${aws_security_group.db-public.id}"]
  db_subnet_group_name   = "${aws_db_subnet_group.db-subnet-group.name}"
}

resource "null_resource" "setup_db" {
  depends_on = ["aws_db_instance.db"]
  provisioner "local-exec" {
    command = "mysql -u ${aws_db_instance.db.username} -p${aws_db_instance.db.password} -h ${aws_db_instance.db.address} < provision_db.sql"
  }
}
