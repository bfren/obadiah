<?php

namespace Feeds\Config;

use Feeds\App;
use Feeds\Helpers\Arr;

App::check();

class Config_ChurchSuite
{
    /**
     * Church Suite API application.
     *
     * @var string
     */
    public readonly string $api_application;

    /**
     * Church Suite API key.
     *
     * @var string
     */
    public readonly string $api_key;

    /**
     * Church Suite organisation subdomain (e.g. 'kingshope' for 'kingshope.churchsuite.com').
     *
     * @var string
     */
    public readonly string $org;

    /**
     * Get values from general configuration array.
     *
     * @param array $config             General configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->api_application = Arr::get($config, "api_application", "");
        $this->api_key = Arr::get($config, "api_key", "");
        $this->org = Arr::get($config, "org", "");
    }
}
