#################################
CI:

#################################

## Build the images for the CI
build-ci: compose.yaml compose.ci.yaml
	$(DOCKER_COMPOSE) build

## Push the images built in CI
push-ci: compose.yaml compose.ci.yaml
	$(DOCKER_COMPOSE) push

## Pull the images for the CI
pull-ci: compose.yaml compose.ci.yaml
	$(DOCKER_COMPOSE) pull
