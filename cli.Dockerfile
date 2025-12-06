FROM bfren/php:php8.5

ENV BF_PHP_EXT="curl session sodium pecl-yaml"

RUN nu -c "use bf-php ext ; ext install [sodium pecl-yaml]"
