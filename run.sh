#!/usr/bin/fish

docker run -p "127.0.0.1:3000:80" -v (pwd):/www bfren/nginx-php:php8.1
