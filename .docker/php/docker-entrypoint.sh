#!/bin/bash

############# start nginx
/etc/init.d/nginx start
############# start cron
/etc/init.d/cron start
############# log dir
if [ -d "/var/www/html/var/log" ]; then
  chmod -R 0777 /var/www/html/var/log
fi
############# cache dir
if [ -d "/var/www/html/var/cache" ]; then
  php /var/www/html/bin/console c:c && chmod -R 0777 /var/www/html/var/cache
fi
############# files dir
if [ ! -d "/var/www/html/var/files" ]; then
  mkdir -p "/var/www/html/var/files" && chmod -R 0777 /var/www/html/var/files
  chown -R www-data:www-data /var/www/html
fi
############# jwt keys
PK_DIR=/var/www/html/config/jwt
/bin/bash generate_jwt_keys.sh $PK_DIR $JWT_PASSPHRASE

############# fix permissions
chown -R www-data:www-data /var/www/html
############# migrations
/usr/local/bin/wait-for-it.sh $MYSQL_HOST:3306 -t 10 -s -- php /var/www/html/bin/console doctrine:migrations:migrate --no-interaction
# /usr/local/bin/wait-for-it.sh mysql:3306 -t 10 -s -- php /var/www/html/bin/console doctrine:migrations:migrate --no-interaction
############# run php-fpm
php-fpm -F
