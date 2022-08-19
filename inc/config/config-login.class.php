<?php

namespace Feeds\Config;

class Config_Login
{
    /**
     * API passphrase.
     *
     * @var string
     */
    public string $api;

    /**
     * Maximum number of login attempts.
     *
     * @var int
     */
    public int $max_attempts;

    /**
     * Login passphrase.
     *
     * @var string
     */
    public string $pass;

    /**
     * Get values from Login configuration array.
     *
     * @param array $config             Login configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->api = $config["api"];
        $this->max_attempts = $config["max_attempts"];
        $this->pass = $config["pass"];
    }
}
