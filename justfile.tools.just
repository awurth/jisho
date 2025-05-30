alias phpcs := php-cs-fixer
alias stan := phpstan

# Execute Rector in dry-run mode
[group('tools')]
[no-exit-message]
rector-dry:
    {{ DOCKER_COMPOSE_EXEC_PHP }} vendor/bin/rector --dry-run

# Execute Rector
[group('tools')]
rector:
    {{ DOCKER_COMPOSE_EXEC_PHP }} vendor/bin/rector

# Launch PHP-CS-Fixer (dry-run mode)
[group('tools')]
[no-exit-message]
php-cs-fixer-dry:
    {{ DOCKER_COMPOSE_EXEC_PHP }} vendor/bin/php-cs-fixer fix --dry-run --stop-on-violation --show-progress=dots

# Execute PHP-CS-Fixer
[group('tools')]
php-cs-fixer:
    {{ DOCKER_COMPOSE_EXEC_PHP }} vendor/bin/php-cs-fixer fix --show-progress=dots

# Execute PHPStan
[group('tools')]
[no-exit-message]
phpstan:
    {{ DOCKER_COMPOSE_EXEC_PHP }} vendor/bin/phpstan --memory-limit=1G

# Execute Composer Dependency Analyser
[group('tools')]
[no-exit-message]
composer-dependency-analyser:
    {{ DOCKER_COMPOSE_EXEC_PHP }} vendor/bin/composer-dependency-analyser

# Execute Prettier
[group('tools')]
prettier:
    {{ DOCKER_COMPOSE_EXEC_YARN }} prettier . --write --log-level warn

# Execute all linters
[group('tools')]
lint: rector php-cs-fixer prettier phpstan

# Execute code style linters
[group('tools')]
cs: rector php-cs-fixer prettier
