#################################
Tools:

#################################

.PHONY: rector rector-dry php-cs-fixer phpstan

## Execute Rector in dry-run mode
rector-dry: api/rector.php
	$(DOCKER_COMPOSE_EXEC_PHP) vendor/bin/rector --dry-run

## Execute Rector
rector: api/rector.php
	$(DOCKER_COMPOSE_EXEC_PHP) vendor/bin/rector

## Launch php-cs-fixer (dry-run mode)
php-cs-fixer-dry: api/.php-cs-fixer.dist.php
	$(DOCKER_COMPOSE_EXEC_PHP) vendor/bin/php-cs-fixer fix --dry-run --stop-on-violation --show-progress=dots

## Execute PHP-CS-Fixer
php-cs-fixer: api/.php-cs-fixer.dist.php
	$(DOCKER_COMPOSE_EXEC_PHP) vendor/bin/php-cs-fixer fix --show-progress=dots

## Execute PHPStan
phpstan: api/phpstan.dist.neon
	$(DOCKER_COMPOSE_EXEC_PHP) vendor/bin/phpstan

## Execute all linters
lint: php-cs-fixer phpstan rector
