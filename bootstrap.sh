#!/usr/bin/env bash

# Variables path
PROJECT_NAME='public'
DBUSER='admin'
DBPASSWD='admin'
DBPASSWD_ROOT='vagrant'
DBNAME='todolist'
DBDUMPSQL='/var/www/html/app/migrations/todo.sql'
php_config_file="/etc/php/8.1/apache2/php.ini"

# create project folder
#sudo mkdir "/var/www/html/${PROJECT_NAME}"

echo "--- Start installation ---"

echo "--- Update / upgrade ---"
sudo apt-get update
sudo apt-get -y upgrade

echo "--- Install base packages ---"
sudo apt-get -y install vim curl build-essential software-properties-common git

echo "--- Install Apache2 ---"
sudo apt-get install -y apache2

echo "--- Install PHP ---"
# Add PHP Repository
sudo add-apt-repository ppa:ondrej/php
sudo add-apt-repository ppa:ondrej/apache2

sudo apt-get install -y php8.1 libapache2-mod-php8.1 php8.1-curl php8.1-gd php8.1-mcrypt php8.1-mysql php8.1-apc
# Verify PHP
php --version

# echo "--- Install and configure xDebug ---"
sudo apt-get install -y php8.1-xdebug

cat << EOF | sudo tee -a ${xdebug_config_file}
xdebug.scream=1
xdebug.cli_color=1
xdebug.show_local_vars=1
EOF

echo "--- Configure php.ini and apache2.conf ---"
sed -i "s/error_reporting = .*/error_reporting = E_ALL \& ~E_NOTICE/" ${php_config_file}
sed -i "s/display_errors = .*/display_errors = On/" ${php_config_file}
sed -i "s/short_open_tag = .*/short_open_tag = On/" ${php_config_file}

echo "--- Install MySql 8 ---"
echo "mysql-server mysql-server/root_password password ${DBPASSWD_ROOT}" | debconf-set-selections
echo "mysql-server mysql-server/root_password_again password ${DBPASSWD_ROOT}" | debconf-set-selections
sudo apt-get -y install mysql-server mysql-client

echo "--- Verify mysql ---"
mysqld --version


mysql -uroot -p${DBPASSWD_ROOT} -e "CREATE DATABASE ${DBNAME}"
mysql -uroot -p${DBPASSWD_ROOT} -e "CREATE USER '${DBUSER}'@'localhost' identified by '${DBPASSWD}'"
mysql -uroot -p${DBPASSWD_ROOT} -e "GRANT ALL ON $DBNAME.* to '${DBUSER}'@'localhost'"
mysql -uroot -p${DBPASSWD_ROOT} ${DBNAME} < ${DBDUMPSQL}

echo "--- Install PhpMyAdmin ---"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/dbconfig-install boolean true"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/app-password-confirm password $DBPASSWD_ROOT"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/admin-pass password $DBPASSWD_ROOT"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/mysql/app-pass password $DBPASSWD_ROOT"
sudo debconf-set-selections <<< "phpmyadmin phpmyadmin/reconfigure-webserver multiselect apache2"
sudo apt-get -y install phpmyadmin

# setup hosts file
VHOST=$(cat <<EOF
<VirtualHost *:80>
    ServerName ${PROJECT_NAME}.local
    DocumentRoot "/var/www/html/${PROJECT_NAME}"
    <Directory "/var/www/html/${PROJECT_NAME}">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
EOF
)
echo "${VHOST}" > /etc/apache2/sites-available/000-default.conf

# enable mod_rewrite
sudo a2enmod rewrite

a2enconf phpmyadmin

echo "--- Restart Apache2 ---"
service apache2 restart

echo "--- Restart mysql ---"
service mysql restart

echo "--- Install composer ---"
curl --silent https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

echo "--- Update project components ---"
cd /var/www/html/${PROJECT_NAME}
#sudo -u vagrant -H sh -c "composer install"
