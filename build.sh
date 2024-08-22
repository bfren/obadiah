#!/bin/sh

IMAGE=`cat VERSION`

docker buildx build \
    --load \
    --no-cache \
    --progress plain \
    --build-arg BF_IMAGE=obadiah \
    --build-arg BF_VERSION=${IMAGE} \
    -f docker/Dockerfile \
    -t obadiah${PHP}-dev \
    . \
    && \
    docker run -it -e BF_DEBUG=1 obadiah${PHP}-dev sh
