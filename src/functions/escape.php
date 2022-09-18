<?php

use Feeds\Helpers\Escape;

/**
 * Escape $text - using sprintf if $args are defined.
 *
 * @param null|string $text             Text (or sprintf format) to be printed.
 * @param array $args                   Optional arguments to use for sprintf.
 * @return string                       Safe (escaped) text.
 */
function __(?string $text, mixed ...$args): string
{
    return Escape::get_text($text, ...$args);
}

/**
 * Safely echo $text - using sprintf if $args are defined.
 *
 * @param null|string $text             Text (or sprintf format) to be printed.
 * @param array $args                   Optional arguments to use for sprintf.
 * @return void
 */
function _e(?string $text, mixed ...$args): void
{
    Escape::echo_text($text, ...$args);
}

/**
 * Safely echo $html.
 *
 * @param null|string $html             HTML to be printed.
 * @return void
 */
function _h(?string $html): void
{
    Escape::echo_html($html);
}
