# Security Groups
resource "aws_security_group" "webserver" {
  name   = "webserver"
  vpc_id = aws_vpc.default.id

  ingress { # Yes, I could be using a bastion host.  I am not.
    from_port   = 22
    to_port     = 22
    protocol    = "tcp"
    cidr_blocks = var.my_home_cidr
  }

  ingress {
    from_port   = 80
    to_port     = 80
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    from_port   = 443
    to_port     = 443
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress { #from anything inside the VPC - Higher sec requirements will not have this.
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["10.0.0.0/16"]
  }

  # outbound internet access
  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

# Internal Only Servers.
resource "aws_security_group" "internal" {
  name   = "internal"
  vpc_id = aws_vpc.default.id

  ingress { # Yes, I could be using a bastion host.  I am not.
    from_port   = 22
    to_port     = 22
    protocol    = "tcp"
    cidr_blocks = var.my_home_cidr
  }

  ingress { #from anything inside the VPC
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["10.0.0.0/16"]
  }

  # outbound internet access
  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}