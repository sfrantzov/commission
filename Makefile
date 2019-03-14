up:
	docker-compose up -d

stop:
	docker-compose stop

down:
	docker-compose down

cli:
	docker-compose run --rm cli

reboot: stop up

install:
	docker-compose run --rm cli composer install

update:
	docker-compose run --rm cli composer update

setup: up update
