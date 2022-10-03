up:
	docker-compose up -d

down:
	docker-compose down

build:
	docker-compose down -v --remove-orphans
	docker-compose rm -vsf
	docker-compose build --build-arg USERID=$(shell (id -u))
	docker-compose up -d
	make dbr

shell:
	docker-compose exec php bash

db:
	docker-compose exec postgres bash

dbr: db-remove db-create migrate load-fixtures # Database reset

prod:
	docker-compose -f docker-compose_prod.yml up -d

prod_build:
	docker-compose -f docker-compose_prod.yml build

db-remove:
	docker-compose exec php ./bin/console doctrine:database:drop --force

db-create:
	docker-compose exec php ./bin/console doctrine:database:create

migrate:
	docker-compose exec php ./bin/console doctrine:migrations:migrate -n

load-fixtures:
	docker-compose exec php ./bin/console doctrine:fixtures:load -n

jwt-generate-keypair:
	docker-compose exec php ./bin/console lexik:jwt:generate-keypair