#!/usr/local/bin/fish

docker pull bfren/ccf
docker run -p "127.0.0.1:3000:80" \
    -v (pwd)/src:/www \
    -v (pwd)/data:/data \
    bfren/ccf
