<?php

namespace Feeds\Config;

class Config_Airtable
{
    /**
     * API Key.
     *
     * @var string
     */
    public readonly string $api_key;

    /**
     * Base reference.
     *
     * @var string
     */
    public readonly string $base;

    /**
     * Get values from Airtable configuration array.
     *
     * @param array $config             Airtable configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->api_key = $config["api_key"];
        $this->base = $config["base"];
    }
}
