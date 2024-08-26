<?php

namespace Obadiah\Config;

use Obadiah\App;
use Obadiah\Helpers\Arr;

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
     * Church Suite Tag ID for adults who have consented to be in the Prayer Calendar.
     *
     * @var int
     */
    public readonly int $tag_id_adults;

    /**
     * Church Suite Tag ID for children whose parents have consented for them to be in the Prayer Calendar.
     *
     * @var int
     */
    public readonly int $tag_id_children;

    /**
     * Get values from ChurchSuite configuration array.
     *
     * @param mixed[] $config           ChurchSuite configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->api_application = Arr::get($config, "api_application", "");
        $this->api_key = Arr::get($config, "api_key", "");
        $this->org = Arr::get($config, "org", "");
        $this->tag_id_adults = Arr::get_integer($config, "tag_id_adults", 0);
        $this->tag_id_children = Arr::get_integer($config, "tag_id_children", 0);
    }
}
