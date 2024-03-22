include Makefile.common.mk
include Makefile.database.mk
include Makefile.testing.mk
include Makefile.tools.mk
include Makefile.frontend.mk

#################################
Docker:

#################################

.PHONY: up down clean pull build

## Build the containers
build: .docker compose.yaml pull
	@$(DOCKER_COMPOSE) build
	@echo "${GREEN}Images built${GREEN}"

## Up the containers (along with the WebEdito project)
up: .docker compose.yaml
	@$(DOCKER_COMPOSE) up -d --force-recreate --remove-orphans
	@echo "${GREEN}Project started${GREEN}"

## Down the containers (along with the WebEdito project)
down: .docker compose.yaml
	@$(DOCKER_COMPOSE) down --remove-orphans
	@echo "${GREEN}Project stopped${GREEN}"

## Down the containers with associated volumes
clean: .docker compose.yaml
	@$(DOCKER_COMPOSE) down --remove-orphans --volumes
	@echo "${GREEN}Project stopped and volumes removed${GREEN}"

## Pull the images
pull: .docker compose.yaml
	$(DOCKER_COMPOSE) pull
	@echo "${GREEN}Images pulled${GREEN}"

#################################
Build:

#################################

.PHONY: start vendor

## Start the project
start: up vendor
	@echo "${GREEN}Project started${GREEN}"

## Install the dependencies
vendor: api/composer.json api/composer.lock
	$(DOCKER_COMPOSE_EXEC_COMPOSER) install

## Audit the composer dependencies
audit: api/composer.json api/composer.lock
	$(DOCKER_COMPOSE_EXEC_COMPOSER) audit

#################################
Common:

#################################

.PHONY: env-dev env-test

## Switch the current environment to dev
env-dev: compose.yaml
	@echo "Switching to ${YELLOW}dev${RESET}"
	@$(DOCKER_COMPOSE_EXEC_PHP) bash -c 'grep APP_ENV= .env.local 1>/dev/null 2>&1 || echo -e "\nAPP_ENV=dev" >> .env.local'
	@$(DOCKER_COMPOSE_EXEC_PHP) sed -i 's/APP_ENV=.*/APP_ENV=dev/g' .env.local

## Switch the current environment to test
env-test: compose.yaml
	@echo "Switching to ${YELLOW}test${RESET}"
	@$(DOCKER_COMPOSE_EXEC_PHP) bash -c 'grep APP_ENV= .env.local 1>/dev/null 2>&1 || echo -e "\nAPP_ENV=test" >> .env.local'
	@$(DOCKER_COMPOSE_EXEC_PHP) sed -i 's/APP_ENV=.*/APP_ENV=test/g' .env.local
