#################################
Testing:

#################################

.PHONY: phpunit

## Launch the PHPUnit tests
phpunit: api/tests env-test create-database-schema
	@$(DOCKER_COMPOSE_EXEC_PHPUNIT)
	@$(MAKE) env-dev
