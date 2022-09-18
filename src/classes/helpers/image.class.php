<?php

namespace Feeds\Helpers;

use Feeds\App;

App::check();

class Image
{
    /**
     * Output an HTML image tag.
     *
     * @param string $src               Image source (within resources/img/ directory).
     * @param string $alt               Image alternative text (will be used for title as well).
     * @param null|string $class        Optional CSS class.
     * @return void
     */
    public static function echo_image(string $src, string $alt, ?string $class = null): void
    {
        Escape::echo_html("<img src=\"/resources/img/%1\$s\" class=\"%3\$s\" alt=\"%2\$s\" title=\"%2\$s\" />", $src, $alt, $class);
    }

    /**
     * Output the logo.
     *
     * @param string $class             CSS class.
     * @return void
     */
    public static function echo_logo(string $class = "logo"): void
    {
        self::echo_image("logo-small.png", "Christ Church Selly Park Logo", $class);
    }
}
