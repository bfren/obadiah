#!/bin/sh

docker pull bfren/obadiah:dev
docker run -p "127.0.0.1:3000:80" \
    -e PHP_INI=development \
    -v $(pwd)/src:/www \
    -v $(pwd)/data:/data \
    bfren/obadiah:dev
