SHELL = /bin/sh

DOCKER                              = docker
DOCKER_COMPOSE                      = docker-compose
DOCKER_COMPOSE_CI                   = docker-compose -f compose.yaml -f compose.ci.yaml
DOCKER_COMPOSE_EXEC                 = $(DOCKER_COMPOSE) exec
DOCKER_COMPOSE_EXEC_PHP             = $(DOCKER_COMPOSE_EXEC) php
DOCKER_COMPOSE_EXEC_POSTGRES        = $(DOCKER_COMPOSE_EXEC) postgres
DOCKER_COMPOSE_EXEC_COMPOSER        = $(DOCKER_COMPOSE_EXEC_PHP) composer
DOCKER_COMPOSE_EXEC_CONSOLE         = $(DOCKER_COMPOSE_EXEC_PHP) bin/console
DOCKER_COMPOSE_EXEC_PHPUNIT         = $(DOCKER_COMPOSE_EXEC_PHP) bin/phpunit
DOCKER_COMPOSE_EXEC_YARN            = $(DOCKER_COMPOSE_EXEC) node yarn

MUTAGEN_COMPOSE_ENABLED             := $(shell which mutagen-compose)
NEW_DOCKER_COMPOSE_ENABLED          := $(shell which docker compose)

ifdef NEW_DOCKER_COMPOSE_ENABLED
	DOCKER_COMPOSE = docker compose
endif

ifdef MUTAGEN_COMPOSE_ENABLED
	DOCKER_COMPOSE = mutagen-compose
endif

ifndef CI_JOB_ID
	GREEN  := $(shell tput -Txterm setaf 2)
	YELLOW := $(shell tput -Txterm setaf 3)
	WHITE  := $(shell tput -Txterm setaf 7)
	RESET  := $(shell tput -Txterm sgr0)
	TARGET_MAX_CHAR_NUM=30
endif

ifdef GITHUB_ACTIONS
	DOCKER_COMPOSE      = $(DOCKER_COMPOSE_CI)
	DOCKER_COMPOSE_EXEC = $(DOCKER_COMPOSE) exec -T
endif

help::
	@awk '/^[a-zA-Z\-\_0-9]+:/ { \
		helpMessage = match(lastLine, /^## (.*)/); \
		if (helpMessage) { \
			helpCommand = substr($$1, 0, index($$1, ":") - 1); helpMessage = substr(lastLine, RSTART + 3, RLENGTH); \
			printf "  ${YELLOW}%-$(TARGET_MAX_CHAR_NUM)s${RESET} ${GREEN}%s${RESET}\n", helpCommand, helpMessage; \
		} \
		isTopic = match(lastLine, /^###/); \
		if (isTopic) { printf "\n%s\n", $$1; } \
	} { lastLine = $$0 }' $(MAKEFILE_LIST)

.DEFAULT_GOAL := help
