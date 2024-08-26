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

/**
 * Log a Throwable type to the standard output buffer.
 *
 * @param Throwable $th                 Throwable object.
 * @return void
 */
function _l_throwable(Throwable $th): void
{
    Log::error("Message: %s\nTrace: %s", $th->getMessage(), $th->getTraceAsString());
}
