#################################
Testing:

#################################

.PHONY: phpunit

## Launch the PHPUnit tests
phpunit: api/tests env-test
	@$(DOCKER_COMPOSE_EXEC_PHPUNIT)
	@$(MAKE) env-dev
