#syntax=docker/dockerfile:1.4

FROM node:20

COPY --link --chmod=755 entrypoint.sh /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]
