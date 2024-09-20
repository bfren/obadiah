#!/bin/sh

LEVEL=7
echo "Using PHPStan to check source with level ${LEVEL}."
php vendor/bin/phpstan analyse --level=${LEVEL} src
