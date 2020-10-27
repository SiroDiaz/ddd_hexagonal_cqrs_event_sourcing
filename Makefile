.PHONY: run test install remove clean help

run: ## run the application
	@docker-compose up --build -d
	@docker exec -it composer_container composer install
	@docker exec -it php_fpm_container ./bin/console cache:warmup

test: ## run unit and functional tests
	@docker exec -it php_fpm_container ./vendor/phpunit/phpunit/phpunit

install: ## install php dependency. EX: make install DEP=<package> DEV=--dev
	@docker run --rm -it -v ${PWD}:/app composer:2.0 require $(DEP) $(DEV) --ignore-platform-reqs

remove: ## remove php dependency. EX: make remove DEP=<package> DEV=--dev
	@docker run --rm -it -v ${PWD}:/app composer:2.0 remove $(DEP) $(DEV) --ignore-platform-reqs

clean: ## stops the containers if exists and remove all the dependencies
	@docker-compose down --remove-orphans || true
	@rm -rf vendor || true
	@rm -rf var || true
	@rm -rf composer.lock || true

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

.DEFAULT_GOAL := help
