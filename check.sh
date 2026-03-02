#!/bin/sh

LEVEL=8
echo "Using PHPStan to check source with level ${LEVEL}."
php vendor/bin/phpstan analyse --level=${LEVEL} src
