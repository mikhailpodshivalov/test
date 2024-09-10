DOCKER_COMPOSE = docker-compose
PATH_DOCKER    = ./docker/docker-compose.yml
EXEC_PHP       = $(DOCKER_COMPOSE) exec -it php
EXEC_ROOT      = docker exec -it -u root php-fpm
EXEC           = docker exec -it php-fpm
SYMFONY        = $(EXEC) php bin/console
COMPOSER       = composer


## Build + up
start:
	$(DOCKER_COMPOSE) -f $(PATH_DOCKER) up --build --remove-orphans --detach

## Stop
stop:
	$(DOCKER_COMPOSE) -f $(PATH_DOCKER) down

## composer install
composer:
	$(EXEC_ROOT) $(COMPOSER) install

## migration
migrate:
	$(SYMFONY) doctrine:migrations:migrate

## fill db
fixtures:
	$(SYMFONY) doctrine:fixtures:load

## run cli
test:
	$(SYMFONY) app:test
