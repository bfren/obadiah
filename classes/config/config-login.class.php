<?php

namespace Feeds\Config;

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
     * Get values from Login configuration array.
     *
     * @param array $config             Login configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->admin = $config["admin"];
        $this->api = $config["api"];
        $this->max_attempts = $config["max_attempts"];
        $this->pass = $config["pass"];
    }
}
