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
    docker run -it -p "127.0.0.1:3000:80" -e BF_DEBUG=1 -v $(pwd)/src:/www -v $(pwd)/data:/data obadiah${PHP}-dev sh
