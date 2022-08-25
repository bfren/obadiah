<?php

namespace Feeds\Config;

use DateTimeZone;
use Feeds\App;

App::check();

class Config_General
{
    /**
     * Church Suite organisation subdomain (e.g. 'kingshope' for 'kingshope.churchsuite.com').
     *
     * @var string
     */
    public readonly string $church_suite_org;

    /**
     * Default timezone.
     *
     * @var DateTimeZone
     */
    public readonly DateTimeZone $timezone;

    /**
     * Get values from events configuration array.
     *
     * @param array $config             Events configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->church_suite_org = $config["church_suite_org"];
        $this->timezone = new DateTimeZone($config["timezone"]);
    }
}
