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

# clear base nginx files
RUN rm -f /www/*

# copy image overlay
COPY ./docker/overlay /

# copy source files
COPY ./src /www/
COPY ./config-sample.yml /www/
COPY ./VERSION /www/

# run installation
RUN bf-install

VOLUME [ "/data" ]
