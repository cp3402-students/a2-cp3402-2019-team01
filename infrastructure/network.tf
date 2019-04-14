resource "aws_default_subnet" "subnet-a" {
  availability_zone = "${var.region}a"
}

resource "aws_default_subnet" "subnet-b" {
  availability_zone = "${var.region}b"
}

resource "aws_default_vpc" "default_vpc" {
}
