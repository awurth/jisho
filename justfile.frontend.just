# Launch Vite development server
[group('frontend')]
vite:
    {{ DOCKER_COMPOSE_EXEC_YARN }} dev --host

# Execute a yarn command
[group('frontend')]
yarn *command='':
    {{ DOCKER_COMPOSE_EXEC_YARN }} {{ command }}

# Install the frontend dependencies
[group('frontend')]
yarn-install:
    {{ DOCKER_COMPOSE_EXEC_YARN }} install

# Upgrade the frontend dependencies
[group('frontend')]
yarn-upgrade:
    {{ DOCKER_COMPOSE_EXEC_YARN }} upgrade

# Audit the frontend dependencies
[group('frontend')]
yarn-audit:
    {{ DOCKER_COMPOSE_EXEC_YARN }} audit
