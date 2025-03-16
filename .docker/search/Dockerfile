#syntax=docker/dockerfile:1

FROM getmeili/meilisearch:v1.13.3 AS search_base

COPY --link dumps/dictionary.dump /dumps/dictionary.dump

CMD ["meilisearch", "--import-dump", "/dumps/dictionary.dump", "--ignore-dump-if-db-exists"]

FROM search_base AS search_dev

FROM search_base AS search_prod
