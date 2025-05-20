#!/usr/bin/env just --justfile

import 'justfile.common.just'
import 'justfile.ci.just'
import 'justfile.database.just'
import 'justfile.frontend.just'
import 'justfile.search.just'
import 'justfile.testing.just'
import 'justfile.tools.just'
import? 'justfile.local.just'

_default:
    @just --list

# Docker

# Build the containers
[group('docker')]
build:
    {{ DOCKER_COMPOSE }} build
    @echo "{{ GREEN }}Images built{{ NORMAL }}"

# Up the containers
[group('docker')]
up *containers='':
    {{ DOCKER_COMPOSE }} up --detach --force-recreate --remove-orphans --wait {{ containers }}
    @echo "{{ GREEN }}Project started{{ NORMAL }}"

# Down the containers
[group('docker')]
down:
    {{ DOCKER_COMPOSE }} down --remove-orphans
    @echo "{{ GREEN }}Project stopped{{ NORMAL }}"

# Down the containers with associated volumes
[group('docker')]
clean:
    {{ DOCKER_COMPOSE }} down --remove-orphans --volumes
    @echo "{{ GREEN }}Project stopped and volumes removed{{ NORMAL }}"

# Pull the images
[group('docker')]
pull:
    {{ DOCKER_COMPOSE }} pull
    @echo "{{ GREEN }}Images pulled{{ NORMAL }}"

# Build

alias cc := cache-clear

# Start the project
[group('build')]
start: up env-dev vendor bootstrap-database
    @echo "{{ GREEN }}Project started{{ NORMAL }}"

# Install the dependencies
[group('build')]
vendor:
    {{ DOCKER_COMPOSE_EXEC_COMPOSER }} install

# Audit the composer dependencies
[group('build')]
audit:
    {{ DOCKER_COMPOSE_EXEC_COMPOSER }} audit

# Clear the cache
[group('build')]
cache-clear:
    {{ DOCKER_COMPOSE_EXEC_CONSOLE }} cache:clear

# Common

# Change the current environment
[group('common')]
env environment='dev':
    @echo "Switching to {{ YELLOW }}{{ environment }}{{ NORMAL }}"
    @{{ DOCKER_COMPOSE_EXEC_PHP }} bash -c 'grep APP_ENV= .env.local 1>/dev/null 2>&1 || echo -e "\nAPP_ENV={{ environment }}" >> .env.local'
    @{{ DOCKER_COMPOSE_EXEC_PHP }} sed -i 's/APP_ENV=.*/APP_ENV={{ environment }}/g' .env.local

# Switch the current environment to dev
[group('common')]
[private]
env-dev: env

# Switch the current environment to test
[group('common')]
[private]
env-test: (env 'test')

# Execute a console command
[group('common')]
console *command='':
    {{ DOCKER_COMPOSE_EXEC_CONSOLE }} {{ command }}

# Execute a composer command
[group('common')]
composer *command='':
    {{ DOCKER_COMPOSE_EXEC_COMPOSER }} {{ command }}

# Update the composer dependencies
[group('common')]
[private]
composer-update: (composer 'update')

# Update all dependencies
[group('common')]
update: composer-update yarn-upgrade
