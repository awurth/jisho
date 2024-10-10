#################################
Frontend:

#################################

.PHONY: vite yarn-install yarn-upgrade yarn-audit

## Launch Vite development server
vite: app/package.json
	$(DOCKER_COMPOSE_EXEC_YARN) dev --host

## Install the frontend dependencies
yarn-install: app/package.json
	$(DOCKER_COMPOSE_EXEC_YARN) install

## Upgrade the frontend dependencies
yarn-upgrade: app/package.json
	$(DOCKER_COMPOSE_EXEC_YARN) upgrade

## Audit the frontend dependencies
yarn-audit: app/package.json
	$(DOCKER_COMPOSE_EXEC_YARN) audit
