## stack
PHP 7.4, Composer, Laravel 7.2, pgSQL12, Nginx17, Docker
## Deployment
Up containers\
``docker-compose up``\
connect to container\
``docker-compose exec continerName /bin/bash``
## Migration
inside of container\
``php artisan migrate``\
seed\
``php artisan db:seed`
## Tests
inside of container\
``php artisan test``
