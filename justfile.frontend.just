# Launch Vite development server
[group('frontend')]
vite:
	{{DOCKER_COMPOSE_EXEC_YARN}} dev --host

# Install the frontend dependencies
[group('frontend')]
yarn-install:
	{{DOCKER_COMPOSE_EXEC_YARN}} install

# Upgrade the frontend dependencies
[group('frontend')]
yarn-upgrade:
	{{DOCKER_COMPOSE_EXEC_YARN}} upgrade

# Audit the frontend dependencies
[group('frontend')]
yarn-audit:
	{{DOCKER_COMPOSE_EXEC_YARN}} audit
