#!/bin/bash

if [ -f /home/ubuntu/.web_install_complete ]
then 
    echo "Install already completed."
    exit 0
else
    apt-get install nginx php8.1-fpm php8.1-mysql -y
    chown root:root nginx.conf
    chown root:root sites-enabled-default
    chown root:root php.ini
    chown root:root php-fpm-pool-www.conf
    mv nginx.conf /etc/nginx/nginx.conf
    mv sites-enabled-default /etc/nginx/sites-enabled/default
    mv php.ini /etc/php/8.1/fpm/php.ini
    mv php-fpm-pool-www.conf /etc/php/8.1/fpm/pool.d/www.conf
    systemctl restart php8.1-fpm
    systemctl restart nginx
    touch /home/ubuntu/.web_install_complete
fi