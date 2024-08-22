<?php

use Obadiah\Helpers\Log;

/**
 * Log an error to the standard output buffer - using sprintf if $args are defined.
 *
 * @param null|string $error            Error message (or sprintf format) to be logged.
 * @param array $args                   Optional arguments to use for sprintf.
 * @return void
 */
function _l(?string $text, mixed ...$args): void
{
    Log::error($text, ...$args);
}
