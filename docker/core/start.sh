#!/usr/bin/env bash
service inetutils-syslogd restart

service nginx stop
service nginx start

php-fpm&

${INSTALL_DIR:=/var/www}

# Check composer
if [[ ! -d ${INSTALL_DIR}/vendor ]]; then
    printf "\033[0;32m > Installing Composer... \n"

    # No vendor folder, run composer install
    composer install --no-scripts --no-progress --no-suggest -d ${INSTALL_DIR}
fi

# Check the database connection
while php ${INSTALL_DIR}/artisan migrate:status | grep -q 'Connection refused'
    do
     # shellcheck disable=SC2028
     echo 'Waiting for database container getting up... \n'
     sleep 1s
done

tail -f /var/log/messages
