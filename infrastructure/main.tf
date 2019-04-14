variable "region" {}

variable "namespace" {}

variable "production_db_name" {}

variable "staging_db_name" {}

output "db_username" {
  value = "${random_string.db_username.result}"
}

output "db_password" {
  value = "${random_string.db_password.result}"
}

output "db_endpoint" {
  value = "${aws_db_instance.db.endpoint}"
}

output "db_address" {
  value = "${aws_db_instance.db.address}"
}

output "private_key_pem" {
  value = "${tls_private_key.private_key.private_key_pem}"
}

output "instance_ip" {
  value = "${aws_instance.ecs.public_ip}"
}

provider "aws" {
  region = "${var.region}"
}

terraform {
  backend "s3" {
    key    = "cp3402/tfstate"
    region = "ap-southeast-2"
    bucket = "cp3402-terraform-state"
  }
}