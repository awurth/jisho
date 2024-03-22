#################################
Frontend:

#################################

.PHONY: yarn-install yarn-watch yarn-build yarn-dev yarn-audit

## Install the frontend dependencies
yarn-install: app/package.json
	$(DOCKER_COMPOSE_EXEC_YARN) install

## Watch changes to the assets and build them
yarn-watch: app/package.json
	$(DOCKER_COMPOSE_EXEC_YARN) watch

## Build the assets for the development env
yarn-dev: app/package.json
	$(DOCKER_COMPOSE_EXEC_YARN) dev

## Build the assets for the production env
yarn-build: app/package.json
	$(DOCKER_COMPOSE_EXEC_YARN) build

## Audit the frontend dependencies
yarn-audit: app/package.json
	$(DOCKER_COMPOSE_EXEC_YARN) audit
