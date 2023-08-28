<?php

namespace Feeds\Helpers;

use Feeds\App;
use Feeds\Config\Config as C;

App::check();

class Image
{
    /**
     * Get the absolute path of an image inside the resources directory.
     *
     * @param string $src               File name / path within /resources/img directory.
     * @return string                   Absolute path to image file.
     */
    public static function get_src(string $src):string
    {
        return sprintf("/resources/img/%s", $src);
    }

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
        Escape::echo_html("<img src=\"%1\$s\" class=\"%3\$s\" alt=\"%2\$s\" title=\"%2\$s\" />", self::get_src($src), $alt, $class);
    }

    /**
     * Output the logo.
     *
     * @param string $class             CSS class.
     * @return void
     */
    public static function echo_logo(string $class = "logo"): void
    {
        self::echo_image("logo-small.png", sprintf("%s Logo", C::$general->church_name_full), $class);
    }
}
