<?php

namespace Feeds\Config;

use DateInterval;
use Feeds\App;
use Feeds\Helpers\Arr;

App::check();

class Config_Rota
{
    /**
     * The Bible version to use for links on rota pages.
     *
     * @var int
     */
    public readonly string $bible_version;

    /**
     * The number of days of the rota to show on the rota home page.
     *
     * @var int
     */
    public readonly int $default_days;

    /**
     * The default length of time for services (override using $services array).
     *
     * @var DateInterval
     */
    public readonly DateInterval $default_length;

    /**
     * Array of ministries on this rota, with override descriptions and abbreviations.
     *
     * @var Config_Rota_Ministry[]
     */
    public readonly array $ministries;

    /**
     * Get values from rota configuration array.
     *
     * @param array $config             Rota configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->bible_version = Arr::get($config, "bible_version", "NIVUK");
        $this->default_days = Arr::get($config, "default_days", 28);
        $this->default_length = new DateInterval(Arr::get($config, "default_length", "PT60M"));

        $ministries = array();
        foreach (Arr::get($config, "ministries") as $ministry) {
            $ministries[] = new Config_Rota_Ministry($ministry);
        }
        $this->ministries = $ministries;
    }
}
