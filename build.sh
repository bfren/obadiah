#!/bin/sh

IMAGE=`cat VERSION`

docker buildx build \
    --load \
    --no-cache \
    --progress plain \
    --build-arg BF_IMAGE=ccf \
    --build-arg BF_VERSION=${IMAGE} \
    -f docker/Dockerfile \
    -t ccf${PHP}-dev \
    . \
    && \
    docker run -it -e BF_DEBUG=1 ccf${PHP}-dev sh
