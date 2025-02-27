# Bootstrap the database
[group('database')]
bootstrap-database: create-database migrate-database
	@echo "{{GREEN}}Database bootstrapped{{NORMAL}}"

# Create the database
[group('database')]
create-database: drop-database
	@{{DOCKER_COMPOSE_EXEC_CONSOLE}} doctrine:database:create --if-not-exists
	@echo "{{GREEN}}Database created{{NORMAL}}"

# Drop the database
[group('database')]
drop-database:
	@{{DOCKER_COMPOSE_EXEC_CONSOLE}} doctrine:database:drop --if-exists --force
	@echo "{{GREEN}}Database dropped{{NORMAL}}"

# Update the database schema
[group('database')]
update-database-schema:
	@{{DOCKER_COMPOSE_EXEC_CONSOLE}} doctrine:schema:update --force
	@echo "{{GREEN}}Database schema updated{{NORMAL}}"

# Show the database schema update sql
[group('database')]
database-schema-diff:
	@{{DOCKER_COMPOSE_EXEC_CONSOLE}} doctrine:schema:update --dump-sql

# Dump the database
[group('database')]
dump-database:
	@{{DOCKER_COMPOSE_EXEC_POSTGRES}} pg_dump --data-only -U app app > .docker/postgres/dump/dump.sql
	@echo "{{GREEN}}Database dumped{{NORMAL}}"

# Restore the database
[group('database')]
restore-database: bootstrap-database
	@{{DOCKER_COMPOSE_EXEC_POSTGRES}} psql -U app -d app -f /var/lib/postgresql/dump/dump.sql
	@echo "{{GREEN}}Database restored{{NORMAL}}"

# Execute the migrations
[group('database')]
migrate-database:
	@{{DOCKER_COMPOSE_EXEC_CONSOLE}} doctrine:migrations:migrate --no-interaction
	@echo "{{GREEN}}Database migrated{{NORMAL}}"

# Load the database fixtures
[group('database')]
fixtures:
	@{{DOCKER_COMPOSE_EXEC_CONSOLE}} doctrine:fixtures:load -n --append
	@echo "{{GREEN}}Database fixtures loaded{{NORMAL}}"
