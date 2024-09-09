ENV = -f docker-compose.yml -f docker-compose.override.yml --env-file ./docker/.env.local

build:
	docker-compose ${ENV} build
up:
	docker-compose ${ENV} up -d
down:
	docker-compose ${ENV} down
stop:
	docker-compose ${ENV} stop
enter:
	docker exec -it carsulla-php bash
clear-cache:
	docker exec carsulla-php rm -rf ./var/cache
	docker exec carsulla-php composer dump-autoload
	docker exec carsulla-php bin/console cache:clear

test-db-refresh:
	docker exec carsulla-php bin/console doctrine:database:drop --force --if-exists --env=test
	docker exec carsulla-php bin/console doctrine:database:create --env=test
	docker exec carsulla-php bin/console doctrine:migrations:migrate --no-interaction --env=test
	docker exec carsulla-php bin/console doctrine:fixtures:load --append --env=test --no-interaction
test:
	docker exec carsulla-php ./bin/phpunit
