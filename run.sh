#!/usr/local/bin/fish

docker run -p "127.0.0.1:3000:80" \
    -e NGINX_ROOT_OVERRIDE="/www/public" \
    -e PHP_EXT="curl pecl-yaml session" \
    -v (pwd)/src:/www \
    -v (pwd)/data:/data \
    bfren/nginx-php:php8.1
