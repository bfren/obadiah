<?php

namespace Feeds\Config;

use Feeds\App;
use Feeds\Helpers\Arr;

App::check();

class Config_Baserow
{
    /**
     * API URI.
     *
     * @var string
     */
    public readonly string $api_uri;

    /**
     * Authorisation Token.
     *
     * @var string
     */
    public readonly string $token;

    /**
     * Get values from Baserow configuration array.
     *
     * @param array $config             Baserow configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->api_uri = Arr::get($config, "api_uri", "");
        $this->token = Arr::get($config, "token", "");
    }
}
