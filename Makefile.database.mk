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

## Update the database schema
update-database-schema: api/config/packages/doctrine.yaml
	@$(DOCKER_COMPOSE_EXEC_PHP) bin/console doctrine:schema:update --force
	@echo "${GREEN}Database schema updated${GREEN}"

## Show the database schema update sql
show-database-schema: api/config/packages/doctrine.yaml
	@$(DOCKER_COMPOSE_EXEC_PHP) bin/console doctrine:schema:update --dump-sql

## Dump the database
dump-database: .docker/postgres/dump
	@$(DOCKER_COMPOSE_EXEC_POSTGRES) pg_dump -c -U app app > .docker/postgres/dump/dump.sql
	@echo "${GREEN}Database dumped${GREEN}"

## Restore the database
restore-database: .docker/postgres/dump/dump.sql
	@$(DOCKER_COMPOSE_EXEC_POSTGRES) psql -U app -d app -f /var/lib/postgresql/dump/dump.sql
	@echo "${GREEN}Database restored${GREEN}"

## Execute the migrations
migrate-database: api/config/packages/doctrine.yaml
	@$(DOCKER_COMPOSE_EXEC_PHP) bin/console doctrine:migrations:migrate --no-interaction
	@echo "${GREEN}Database migrated${GREEN}"
