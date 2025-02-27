# Create a new dictionary dump
[group('search')]
create-search-dump:
	@curl -X POST 'http://localhost:7700/dumps' -H 'Authorization: Bearer N2Q3YzE0MzgzMTY4ZjZlNDkxOTExNDQzNzZkYTk2MGI4NzI1MmQ4ZWQxZmJmYmE1M2Ql'

# Create the dictionary search index
[group('search')]
create-search-index:
	@{{DOCKER_COMPOSE_EXEC_CONSOLE}} app:create:search-index --delete

# Index dictionary
[group('search')]
index-dictionary:
	@{{DOCKER_COMPOSE_EXEC_CONSOLE}} app:index -vv
