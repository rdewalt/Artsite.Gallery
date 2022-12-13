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

  # I really hate doing this, I normally set up bastion hosts and never ever use public IPs.
  # However in interest of brevity and free account, I am -intentionally- going against my normal best practices in this case.
  associate_public_ip_address = true

  tags = {
    Name        = "Webserver-${count.index}"
    Environment = "${var.Environment}"
  }

  root_block_device {
    volume_size = "60"
    volume_type = "gp2"
  }

  #upload demo ssh keys to grab webserver
  provisioner "file" { # INTENTIONAL commit of SSH Keys to the repo.
    source      = "yna-int"
    destination = "/home/ec2-user/id_rsa"
    on_failure  = fail
    connection {
      type        = var.connect_type
      user        = var.connect_user
      host        = self.public_ip
      private_key = file("rwd-yna.pem")
    }
  }

  provisioner "file" { # INTENTIONAL commit of SSH Keys to the repo.
    source      = "yna-int.pub"
    destination = "/home/ec2-user/id_rsa.pub"
    on_failure  = fail
    connection {
      type        = var.connect_type
      user        = var.connect_user
      host        = self.public_ip
      private_key = file("rwd-yna.pem")
    }
  }

  # Remove the -q and --quiet if you need to debug these.  They were very noisy, so once I was done 'developing' I made them quiet.
  provisioner "remote-exec" {
    inline = [
      "sudo hostnamectl set-hostname webserver-${count.index}",
      "echo '127.0.0.1 webserver-${count.index}' | sudo tee -a /etc/hosts",
      "sudo yum -q install git -y",
      "mkdir -p ~/.ssh/",
      "mv id_rsa ~/.ssh/",
      "mv id_rsa.pub ~/.ssh/",
      "chmod 0644 ~/.ssh/id_rsa.pub",
      "chmod 0600 ~/.ssh/id_rsa",
      "ssh-keyscan github.com >> ~/.ssh/known_hosts",
      "git clone --quiet git@github.com:rdewalt/Artsite.Gallery.git",
    ]
    connection {
      type        = var.connect_type
      user        = var.connect_user
      host        = self.public_ip
      private_key = file("rwd-yna.pem")
    }
  }
}