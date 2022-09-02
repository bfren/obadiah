<?php

namespace Feeds\Helpers;

use Feeds\App;

App::check();

class Image
{
    public static function get_image(string $src, string $alt, ?string $class = null): string
    {
        return sprintf("<img src=\"/resources/img/%1\$s\" class=\"%3\$s\" alt=\"%2\$s\" title=\"%2\$s\" />", $src, $alt, $class);
    }

    public static function get_logo(string $class = "logo"): string
    {
        return self::get_image("logo-small.png", "Christ Church Selly Park Logo", $class);
    }
}
