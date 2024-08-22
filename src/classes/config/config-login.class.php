<?php

namespace Obadiah\Config;

use Obadiah\App;
use Obadiah\Helpers\Arr;

App::check();

class Config_Login
{
    /**
     * Admin passphrase.
     *
     * @var string
     */
    public readonly string $admin;

    /**
     * API passphrase.
     *
     * @var string
     */
    public readonly string $api;

    /**
     * Maximum number of login attempts.
     *
     * @var int
     */
    public readonly int $max_attempts;

    /**
     * Login passphrase.
     *
     * @var string
     */
    public readonly string $pass;

    /**
     * Get values from login configuration array.
     *
     * @param array $config             Login configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->admin = Arr::get($config, "admin", "");
        $this->api = Arr::get($config, "api", "");
        $this->max_attempts = Arr::get_integer($config, "max_attempts", 5);
        $this->pass = Arr::get($config, "pass", "");
    }
}
