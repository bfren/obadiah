FROM bfren/nginx-php:php8.2

LABEL org.opencontainers.image.source="https://github.com/bfren/churchsuite-feeds"

ARG BF_IMAGE
ARG BF_VERSION

ENV \
    # generate empty config.yml if one does not already exist
    CCF_GENERATE_EMPTY_CONFIG=1

# clear base nginx files
RUN rm -f /www/*

# copy image overlay
COPY ./docker/overlay /

# copy source files
COPY ./src /www/
COPY ./config-sample.yml /www/

# run installation
RUN bf-install

VOLUME [ "/data" ]
