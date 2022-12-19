#!/usr/bin/env bash

if [ -f /home/ubuntu/.mysql_install_complete ]
then 
    echo "Install already completed."
    exit 0
else
    dbpass="86753091024"
    password="86753091024"
    ssh-keyscan github.com >> ~/.ssh/known_hosts
    git clone --quiet --branch dev git@github.com:rdewalt/Artsite.Gallery.git

    echo PURGE | sudo debconf-communicate mysql-community-server
    sudo apt purge mysql-client mysql-server

    sudo debconf-set-selections <<< "mysql-community-server mysql-community-server/root-pass password $password"
    sudo debconf-set-selections <<< "mysql-community-server mysql-community-server/re-root-pass password $password"
    sudo debconf-set-selections <<< "mysql-community-server mysql-server/default-auth-override select Use Legacy Authentication Method (Retain MySQL 5.x Compatibility)"

    sudo apt install -y dirmngr
    sudo apt-key adv --keyserver pool.sks-keyservers.net --recv-keys 5072E1F5
    echo "deb http://repo.mysql.com/apt/ubuntu $(lsb_release -sc) mysql-8.0" | sudo tee /etc/apt/sources.list.d/mysql80.list
    sudo apt-get update

    export DEBIAN_FRONTEND="noninteractive" 
    sudo apt-get install mysql-server -y
    sudo mv mysqld.cnf /etc/mysql/mysql.conf.d/
    mysql -u root -e "create user 'yna'@'%' identified by '86753091024';"
    mysql -u root -e "create database yna;"
    mysql -u root -e "grant all on yna.* to 'yna'@'%';"
    mysql -u root yna < Artsite.Gallery/sql/yart.sql 

    touch /home/ubuntu/.mysql_install_complete
fi