#!/bin/sh

#docker buildx build -f cli.Dockerfile -t obadiah:cli --load .
docker run \
    -v /mnt/q/src/churchsuite-feeds/data:/data \
    -v /mnt/q/src/churchsuite-feeds/src:/ws \
    -v /mnt/q/src/churchsuite-feeds/config-sample.yml:/ws/config-sample.yml \
    obadiah:cli \
    php -f cmd.php $@
