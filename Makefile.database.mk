#################################
Database:

#################################

.PHONY: create-database drop-database dump-database restore-database

## Create the database
create-database: api/config/packages/doctrine.yaml drop-database
	@$(DOCKER_COMPOSE_EXEC_PHP) bin/console doctrine:database:create --if-not-exists
	@echo "${GREEN}Database created${GREEN}"

## Drop the database
drop-database: api/config/packages/doctrine.yaml
	@$(DOCKER_COMPOSE_EXEC_PHP) bin/console doctrine:database:drop --if-exists --force
	@echo "${GREEN}Database dropped${GREEN}"

## Create the database schema
create-database-schema: api/config/packages/doctrine.yaml create-database
	@$(DOCKER_COMPOSE_EXEC_PHP) bin/console doctrine:schema:create
	@echo "${GREEN}Database schema created${GREEN}"
