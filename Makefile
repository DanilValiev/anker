# Makefile

.PHONY: up down restart logs fpm-log ap migration migrate dev

up:
	docker compose up -d --build

down:
	docker compose down

restart: down up

logs:
	docker compose logs -f

fpm-log:
	docker compose log php-fpm -f

ap:
	docker compose exec php-fpm bash

migration:
	docker compose exec php-fpm php ../bin/console make:migration

cache:
	docker compose exec php-fpm php ../bin/console cache:clear

migrate:
	docker compose exec php-fpm php ../bin/console doctrine:migrations:migrate

dev:
	docker compose -f docker-compose.dev.yml up -d