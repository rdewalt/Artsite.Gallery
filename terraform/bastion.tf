# The DevOps Toolbox Server.
resource "aws_instance" "bastion" {
  instance_type               = "t4g.micro"
  ami                         = var.default_ami
  associate_public_ip_address = true
  disable_api_termination     = true
  key_name                    = var.keyname
  vpc_security_group_ids      = ["${aws_security_group.webserver.id}"] #misnomer, SSH plus all inside the VPC
  subnet_id                   = element(aws_subnet.public.*.id, 0)
  availability_zone           = element(data.aws_availability_zones.available.names, 0)
  tags = {
    Name = "Bastion"
  }

  root_block_device {
    volume_size = "30"
    volume_type = "gp3"
  }

  #  provisioner "remote-exec" {
  #    inline = [
  #      "sudo echo \"AllowTcpForwarding yes\" >> /etc/ssh/sshd_config"
  #    ]
  #    connection {
  #      host        = aws_instance.bastion.public_ip
  #      type        = "ssh"
  #      user        = var.connect_user
  #      private_key = file("./rwd-yna.pem")
  #    }
  #  }

}

resource "aws_route53_record" "bastion" {
  zone_id = "Z10381301HZKPQJ9VVOUJ"
  name    = "grunky.solfire.com"
  type    = "A"
  ttl     = 300
  records = [aws_instance.bastion.public_ip]
}

variable "ssh_proxy_block" {
  default = "10.1.*"
}

resource "local_file" "ssh_proxy_block" {
  content  = "Host ${aws_instance.bastion.public_ip}\n\tUser ${var.connect_user}\n\tStrictHostKeyChecking no\n\tPort 22\n\tIdentityFile  ~/.ssh/rwd-yna.pem\n\nHost ${var.ssh_proxy_block}\n\tProxyCommand ssh -W %h:%p ${var.connect_user}@${aws_instance.bastion.public_ip}\n\tUser ${var.connect_user}\n\tStrictHostKeyChecking no\n\tIdentityFile  ~/.ssh/rwd-yna.pem"
  filename = "~/.ssh/configs/yna"
}
