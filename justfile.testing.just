# Launch the PHPUnit tests
[group('testing')]
phpunit: env-test create-database migrate-database cache-clear && env-dev
    {{ DOCKER_COMPOSE_EXEC_PHPUNIT }}
