SHELL := /bin/bash

tests:
	docker compose --project-directory ~/Projects/foss-fi exec php bin/console doctrine:database:drop --force --env=test || true
	docker compose --project-directory ~/Projects/foss-fi exec php bin/console doctrine:database:create --env=test
	docker compose --project-directory ~/Projects/foss-fi exec php bin/console doctrine:migrations:migrate -n --env=test
	docker compose --project-directory ~/Projects/foss-fi exec php bin/console doctrine:fixtures:load -n --env=test
	docker compose --project-directory ~/Projects/foss-fi exec php bin/phpunit $(MAKECMDGOALS)
.PHONY: tests