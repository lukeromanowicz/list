# How to setup the project?
* `docker-compose up`
* `docker-compose exec php sh -c 'cd /var/www/api && composer update'`
* `docker-compose exec php sh -c 'cd /var/www/api && php bin/console doctrine:database:create'`
* `docker-compose exec php sh -c 'cd /var/www/api && php bin/console doctrine:migrations:migrate'`

API is accessible by default at localhost:8888/words. It supports GET, POST and DELETE methods.

# How to run unit tests?
* `docker-compose exec php sh -c 'cd /var/www/api && ./vendor/bin/simple-phpunit'`

TODO:
* cover api with unit tests
* move api, frontend and env to separate repositories
* implement swagger for API docs
* refactor response format for unified error handling
