resource "ssh_resource" "db_init" {
  host        = aws_instance.database.0.public_ip
  user        = var.connect_user
  host_user   = var.connect_user
  private_key = file("./rwd-yna.pem")

  file {
    source      = "./files/mysql_install.sh"
    destination = "mysql_install.sh"
    permissions = "0755"
  }
  file {
    source      = "./files/yna-int"
    destination = ".ssh/id_rsa"
    permissions = "0600"
  }
  file {
    source      = "./files/yna-int.pub"
    destination = ".ssh/id_rsa.pub"
    permissions = "0644"
  }

  commands = [
    "sudo hostnamectl set-hostname database-0",
    "echo '127.0.0.1 database-0' | sudo tee -a /etc/hosts",
    "sudo apt-get update",
    "sudo apt-get install git -y",
    "ssh-keyscan github.com >> ~/.ssh/known_hosts",
    "git clone --quiet --branch dev git@github.com:rdewalt/Artsite.Gallery.git",
    "sudo ./mysql_install.sh",
  ]
}
resource "ssh_resource" "web_init" {
  count       = var.webserver_server_count
  host        = aws_instance.webserver[count.index].public_ip
  user        = var.connect_user
  host_user   = var.connect_user
  private_key = file("./rwd-yna.pem")

  file {
    source      = "./files/yna-int"
    destination = ".ssh/id_rsa"
    permissions = "0600"
  }

  file {
    source      = "./files/yna-int.pub"
    destination = ".ssh/id_rsa.pub"
    permissions = "0644"
  }

  file {
    source      = "./files/web-install.sh"
    destination = "web-install.sh"
    permissions = "0770"
  }

  file {
    source      = "./files/nginx.conf"
    destination = "nginx.conf"
    permissions = "0644"
  }

  file {
    source      = "./files/php-fpm-pool-www.conf"
    destination = "php-fpm-pool-www.conf"
    permissions = "0644"
  }

  file {
    source      = "./files/php.ini"
    destination = "php.ini"
    permissions = "0644"
  }

  file {
    source      = "./files/sites-enabled-default"
    destination = "sites-enabled-default"
    permissions = "0644"
  }

  commands = [
    "sudo hostnamectl set-hostname webserver-${count.index}",
    "echo '127.0.0.1 webserver-${count.index}' | sudo tee -a /etc/hosts",
    "echo '${aws_instance.database.0.private_ip} mysql' | sudo tee -a /etc/hosts",
    "sudo apt-get update",
    "sudo apt-get install git -y",
    "ssh-keyscan github.com >> ~/.ssh/known_hosts",
    "git clone --quiet --branch dev git@github.com:rdewalt/Artsite.Gallery.git",
    "sudo ./web-install.sh",
  ]
}