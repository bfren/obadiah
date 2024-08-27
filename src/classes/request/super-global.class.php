<?php

namespace Obadiah\Request;

use Obadiah\App;
use Obadiah\Helpers\Sanitise;

App::check();

class Super_Global
{
    /**
     * Create object.
     *
     * @param int $type                 Superglobal type (e.g. INPUT_GET).
     * @return void
     */
    public function __construct(
        public readonly int $type
    ) {}

    /**
     * Return all values from the superglobal.
     *
     * @return mixed[]                  All superglobal values (or an empty array).
     */
    public function all(): array
    {
        return filter_input_array($this->type) ?: [];
    }

    /**
     * Get and sanitise a boolean value.
     *
     * @param string $key               Array key.
     * @param bool $default             Optional default value.
     * @return bool                     Value or default value.
     */
    public function bool(string $key, bool $default = false): bool
    {
        return filter_input($this->type, $key, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?: $default;
    }

    /**
     * Get and sanitise an integer value.
     *
     * @param string $key               Array key.
     * @param int $default              Optional default value.
     * @return int                      Value or default value.
     */
    public function int(string $key, int $default = 0): int
    {
        return filter_input($this->type, $key, FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE) ?: $default;
    }

    /**
     * Get and sanitise a string value.
     *
     * @param string $key               Array key.
     * @param string $default           Optional default value.
     * @return string                   Value or default value.
     */
    public function string(string $key, string $default = ""): string
    {
        // get value
        $input = filter_input($this->type, $key, FILTER_UNSAFE_RAW, FILTER_NULL_ON_FAILURE | FILTER_FLAG_STRIP_BACKTICK) ?: $default;

        // return sanitised string
        return Sanitise::text_input($input);
    }
}
