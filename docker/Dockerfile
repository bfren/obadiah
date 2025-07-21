FROM bfren/nginx-php:php8.4-dev

LABEL org.opencontainers.image.source="https://github.com/bfren/obadiah"

ARG BF_IMAGE
ARG BF_VERSION

# clear base nginx files
RUN rm -rf /www/*

# copy image overlay
COPY ./docker/overlay /

# copy source files
COPY ./src /www/
COPY ./config-sample.yml /www/

ENV \
    # required extensions
    BF_PHP_EXT="curl session sodium pecl-yaml" \
    # generate empty config.yml if one does not already exist
    BF_OBADIAH_GENERATE_EMPTY_CONFIG="1"

# run installation
RUN bf-install

VOLUME [ "/data" ]
