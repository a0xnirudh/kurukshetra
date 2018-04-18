Requirements:
    mysql-server-5.7+
    apache2
    php5.5.9+

Pre-requesites:
    create a mysql user with "create database" permission
    create a folder `/var/config/` with write permission to `www-data / apache` user
    create google oauth credentials from https://console.developers.google.com/apis/credentials

git clone https://github.com/a0xnirudh/kurukshetra.git
cd kurukshetra
cp -r * /var/www/html/

Access <protocal>://<domain> to install Security Playground
    ex: http://localhost
