#!/bin/sh
set -e

if [ "$1" = 'frankenphp' ] || [ "$1" = 'php' ] || [ "$1" = 'bin/console' ]; then
	chown -R www-data:www-data /jisho/api/var
fi

exec docker-php-entrypoint "$@"
