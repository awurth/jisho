#################################
Testing:

#################################

.PHONY: phpunit

## Launch the PHPUnit tests
phpunit: api/tests env-test create-database migrate-database cache-clear
	@$(DOCKER_COMPOSE_EXEC_PHPUNIT)
	@$(MAKE) env-dev
