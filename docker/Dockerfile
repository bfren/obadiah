FROM bfren/nginx-php:php8.1

LABEL org.opencontainers.image.source="https://github.com/bfren/churchsuite-feeds"

ARG BF_IMAGE
ARG BF_VERSION

ENV \
    # disable container debug output
    BF_DEBUG=0 \
    # generate empty config.yml if one does not already exist
    CCF_GENERATE_EMPTY_CONFIG=1 \
    # set nginx root to public directory
    NGINX_ROOT_OVERRIDE=/www/public

COPY ./docker/overlay /
COPY ./src /www/
COPY ./config-sample.yml /www/

RUN bf-install

VOLUME [ "/data" ]
