resource "ssh_resource" "db_init" {
  host        = aws_instance.database.0.public_ip
  user        = var.connect_user
  host_user   = var.connect_user
  private_key = file("./rwd-yna.pem")

  file {
    source      = "yna-int"
    destination = ".ssh/id_rsa"
    permissions = "0600"
  }
  file {
    source      = "yna-int.pub"
    destination = ".ssh/id_rsa.pub"
    permissions = "0644"
  }

  commands = [
    "sudo hostnamectl set-hostname database-0",
    "echo '127.0.0.1 database-0' | sudo tee -a /etc/hosts",
    "sudo apt-get update",
    "sudo apt-get upgrade -y",
    "sudo apt-get install git -y",
    "ssh-keyscan github.com >> ~/.ssh/known_hosts",
    "git clone --quiet git@github.com:rdewalt/Artsite.Gallery.git",
    "cd Artsite.Gallery",
    "git checkout dev", #TODO:  Remove checkout branch.
    "sudo mysql",
    "create user 'yna'@'localhost' identified by '${var.dbpass}';",
    "create database yna",
    "grant all on mysql.* to 'yna'@'localhost'",
    "grant all on yna.* to 'yna'@'localhost'",
    "exit",
    "mysql -u yna -p${var.dbpass} yna < yart.sql"
  ]
}

resource "ssh_resource" "web_init" {
  count       = var.webserver_server_count
  host        = aws_instance.webserver[count.index].public_ip
  user        = var.connect_user
  host_user   = var.connect_user
  private_key = file("./rwd-yna.pem")

  file {
    source      = "yna-int"
    destination = ".ssh/id_rsa"
    permissions = "0600"
  }
  file {
    source      = "yna-int.pub"
    destination = ".ssh/id_rsa.pub"
    permissions = "0644"
  }

  commands = [
    "sudo hostnamectl set-hostname webserver-${count.index}",
    "echo '127.0.0.1 webserver-${count.index}' | sudo tee -a /etc/hosts",
    "sudo apt-get update",
    "sudo apt-get upgrade -y",
    "sudo apt-get install git mysql-server -y",
    "ssh-keyscan github.com >> ~/.ssh/known_hosts",
    "git clone --quiet git@github.com:rdewalt/Artsite.Gallery.git",
  ]
}