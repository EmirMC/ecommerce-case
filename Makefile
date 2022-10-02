up:
	docker-compose up -d

down:
	docker-compose down

rebuild:
	docker-compose down -v --remove-orphans
	docker-compose rm -vsf
	docker-compose up -d --build

dbr: db-remove db-create migrate load-fixtures # Database reset

prod:
	docker-compose -f docker-compose_prod.yml up -d

prod_build:
	docker-compose -f docker-compose_prod.yml build

shell:
	docker-compose exec php bash

db-remove:
	docker-compose exec php ./bin/console doctrine:database:drop --force

db-create:
	docker-compose exec php ./bin/console doctrine:database:create

migrate:
	docker-compose exec php ./bin/console doctrine:migrations:migrate -n

load-fixtures:
	docker-compose exec php ./bin/console doctrine:fixtures:load -n