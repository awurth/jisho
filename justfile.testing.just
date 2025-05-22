alias test := phpunit

# Launch the PHPUnit tests
[group('testing')]
[no-exit-message]
phpunit *command='': env-test create-database migrate-database cache-clear && env-dev
    {{ DOCKER_COMPOSE_EXEC_PHPUNIT }} {{ command }}
