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
