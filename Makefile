include Makefile.common.mk
include Makefile.ci.mk
include Makefile.database.mk
include Makefile.frontend.mk
include Makefile.search.mk
include Makefile.testing.mk
include Makefile.tools.mk

#################################
Docker:

#################################

.PHONY: up down clean pull build

## Build the containers
build: .docker compose.yaml pull
	@$(DOCKER_COMPOSE) build
	@echo "${GREEN}Images built${GREEN}"

## Up the containers
up: .docker compose.yaml
	@$(DOCKER_COMPOSE) up -d --force-recreate --remove-orphans
	@echo "${GREEN}Project started${GREEN}"

## Down the containers
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
start: up env-dev vendor yarn-install bootstrap-database
	@echo "${GREEN}Project started${GREEN}"

## Install the dependencies
vendor: api/composer.json api/composer.lock
	$(DOCKER_COMPOSE_EXEC_COMPOSER) install

## Audit the composer dependencies
audit: api/composer.json api/composer.lock
	$(DOCKER_COMPOSE_EXEC_COMPOSER) audit

## Clear the cache
cache-clear: api/bin/console
	$(DOCKER_COMPOSE_EXEC_PHP) bin/console cache:clear

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

## Import the JMDict XML file
import: api/bin/console
	@$(DOCKER_COMPOSE_EXEC_PHP) bin/console app:import -vv

## Compress the dictionary import file
compress-dictionary-import-file: api/bin/tools
	@$(DOCKER_COMPOSE_EXEC_PHP) rm -rf data/JMdict.xml.gz
	@$(DOCKER_COMPOSE_EXEC_PHP) sh bin/tools compress_dictionary
	@$(DOCKER_COMPOSE_EXEC_PHP) rm -rf data/JMdict.xml
	@echo "${GREEN}Dictionary import file compressed"

## Decompress the dictionary import file
decompress-dictionary-import-file: api/bin/tools
	@$(DOCKER_COMPOSE_EXEC_PHP) sh bin/tools decompress_dictionary
	@echo "${GREEN}Dictionary import file decompressed"
