FROM getmeili/meilisearch:v1.13.1 AS search_base

FROM search_base AS search_dev

FROM search_base AS search_prod

COPY dumps/latest.dump /meili_data/dumps/latest.dump
