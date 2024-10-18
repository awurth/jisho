#################################
Search:

#################################

.PHONY: create-search-dump

## Create a new dictionary dump
create-search-dump: compose.yaml
	@curl -X POST 'http://localhost:7700/dumps' -H 'Authorization: Bearer N2Q3YzE0MzgzMTY4ZjZlNDkxOTExNDQzNzZkYTk2MGI4NzI1MmQ4ZWQxZmJmYmE1M2Ql'

## Create the dictionary search index
create-search-index: api/bin/console
	@$(DOCKER_COMPOSE_EXEC_PHP) bin/console app:create:search-index

## Index dictionary
index: api/bin/console
	@$(DOCKER_COMPOSE_EXEC_PHP) bin/console app:index
