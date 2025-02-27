# Build the images for the CI
[private]
[group('ci')]
build-ci:
    {{ DOCKER_COMPOSE }} build

# Push the images built in CI
[private]
[group('ci')]
push-ci:
    {{ DOCKER_COMPOSE }} push

# Pull the images for the CI
[private]
[group('ci')]
pull-ci:
    {{ DOCKER_COMPOSE }} pull
