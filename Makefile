# Makefile

.PHONY: up down restart logs fpm-log

up:
	docker-compose up

down:
	docker-compose down

restart: down up

logs:
	docker-compose logs -f

fpm-log:
	docker-compose log php-fpm -f
