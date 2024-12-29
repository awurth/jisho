#syntax=docker/dockerfile:1

FROM dunglas/frankenphp:php8.4 AS base

WORKDIR /jisho/api

VOLUME /jisho/api/var/

# persistent / runtime deps
RUN apt-get update && apt-get install -y --no-install-recommends \
	acl \
	file \
	gettext \
	git \
	&& rm -rf /var/lib/apt/lists/*

RUN set -eux; \
	install-php-extensions \
		@composer \
		apcu \
		intl \
		opcache \
		zip \
		pdo_pgsql \
	;

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --link .docker/php/conf.d/app.ini $PHP_INI_DIR/conf.d/
COPY --link --chmod=755 .docker/php/entrypoint.sh /usr/local/bin/docker-entrypoint
COPY --link .docker/php/Caddyfile /etc/caddy/Caddyfile

ENTRYPOINT ["docker-entrypoint"]
CMD [ "frankenphp", "run", "--config", "/etc/caddy/Caddyfile" ]

FROM base AS dev

ENV APP_ENV=dev XDEBUG_MODE=off
ENV PHP_CS_FIXER_IGNORE_ENV=1

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN set -eux; \
	install-php-extensions \
		xdebug \
	;

COPY --link .docker/php/conf.d/app.dev.ini $PHP_INI_DIR/conf.d/

CMD [ "frankenphp", "run", "--config", "/etc/caddy/Caddyfile", "--watch" ]

FROM base AS prod

ENV APP_ENV=prod

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY --link .docker/php/conf.d/app.prod.ini $PHP_INI_DIR/conf.d/

# prevent the reinstallation of vendors at every changes in the source code
COPY --link api/composer.* ../../api/symfony.* ./
RUN set -eux; \
	composer install --no-cache --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress

# copy sources
COPY --link api ./

RUN set -eux; \
	mkdir -p var/cache var/log; \
	composer dump-autoload --classmap-authoritative --no-dev; \
	composer dump-env prod; \
	composer run-script --no-dev post-install-cmd; \
	chmod +x bin/console; sync;

EXPOSE 80
EXPOSE 443
