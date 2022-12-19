# The EC2s for the system.
# Current Plan:  One small one for Apache.  One larger one for the database.
# EC2 Specific terraform code
resource "aws_instance" "webserver" {
  instance_type           = var.webserver_ec2_size
  count                   = var.webserver_server_count
  ami                     = var.default_ami
  iam_instance_profile    = aws_iam_instance_profile.ec2_profile.name
  disable_api_termination = var.termination_protection
  key_name                = var.keyname
  vpc_security_group_ids  = ["${aws_security_group.webserver.id}"] #misnomer, SSH plus all inside the VPC
  subnet_id               = element(aws_subnet.public.*.id, count.index)
  availability_zone       = element(data.aws_availability_zones.available.names, count.index)

  associate_public_ip_address = true

  tags = {
    Name        = "Webserver-${count.index}"
    Environment = "${var.Environment}"
    servertype  = "webserver"
  }

  root_block_device {
    volume_size = "30"
    volume_type = "gp3"
  }

}

resource "aws_instance" "database" {
  instance_type           = var.database_ec2_size
  count                   = 1 # For future expansion, if I change this, change ssh_install.tf as well to adapt.
  ami                     = var.default_ami
  disable_api_termination = false
  key_name                = var.keyname
  vpc_security_group_ids  = ["${aws_security_group.internal.id}"] #misnomer, SSH plus all inside the VPC
  subnet_id               = element(aws_subnet.public.*.id, count.index)
  availability_zone       = element(data.aws_availability_zones.available.names, count.index)

  associate_public_ip_address = true

  tags = {
    Name        = "Database-${count.index}"
    Environment = "${var.Environment}"
    servertype  = "database"
  }

  root_block_device {
    volume_size = "100"
    volume_type = "gp3"
  }

}

resource "aws_route53_zone" "primary" {
  name = "solfire.com"
  lifecycle {
    prevent_destroy = true
  }
}

resource "aws_route53_record" "database" {
  zone_id = aws_route53_zone.primary.zone_id
  name    = "db.internal.solfire.com"
  type    = "A"
  ttl     = 300
  records = [aws_instance.database.0.private_ip]
}