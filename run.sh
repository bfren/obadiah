#!/bin/sh

docker pull bfren/obadiah:dev
docker run -it \
    -p "127.0.0.1:3000:80" \
    -e BF_PHP_ENV=development \
    -v $(pwd)/src:/www \
    -v $(pwd)/data:/data \
    bfren/obadiah:dev nu
