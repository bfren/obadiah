#!/bin/sh

#docker buildx build -f cli.Dockerfile -t obadiah:cli --load .
docker run \
    -v $(pwd)/data:/data \
    -v $(pwd)/src:/ws \
    -v $(pwd)/config-sample.yml:/ws/config-sample.yml \
    obadiah:cli \
    php -f cmd.php $@
