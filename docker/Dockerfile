FROM bfren/nginx-php:php8.1

LABEL org.opencontainers.image.source="https://github.com/bfren/churchsuite-feeds"

ENV \
    # disable container debug output
    BF_DEBUG=0 \
    # generate empty config.yml if one does not already exist
    CCF_GENERATE_EMPTY_CONFIG=1

COPY ./docker/overlay /
COPY ./VERSION /tmp

RUN bf-install

VOLUME [ "/data" ]
