#!/usr/local/bin/fish

docker run -p "127.0.0.1:3000:80" -e PHP_EXT="curl pecl-yaml" -v (pwd):/www bfren/nginx-php:php8.1
