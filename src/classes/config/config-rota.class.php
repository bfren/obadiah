<?php

namespace Obadiah\Config;

use DateInterval;
use Obadiah\App;
use Obadiah\Helpers\Arr;
use Obadiah\Helpers\DateTime;

App::check();

class Config_Rota extends Config_Section
{
    /**
     * The Bible version to use for links on rota pages.
     *
     * @var string
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
     * The number of recent uploaded files to show by default.
     *
     * @var int
     */
    public readonly int $show_recent_files;

    /**
     * Get values from rota configuration array.
     *
     * @param mixed[] $config           Rota configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->bible_version = Arr::get($config, "bible_version", "NIVUK");
        $this->default_days = Arr::get_integer($config, "default_days", 28);
        $this->default_length = new DateInterval(Arr::get($config, "default_length", "PT60M"));
        $this->show_recent_files = Arr::get_integer($config, "show_recent_files", 4);

        $ministries = [];
        foreach (Arr::get($config, "ministries", []) as $ministry) {
            $ministries[] = new Config_Rota_Ministry($ministry);
        }
        $this->ministries = $ministries;
    }

    public function as_array(): array
    {
        $ministries = [];
        foreach ($this->ministries as $ministry) {
            $ministries[] = $ministry->as_array();
        }

        return [
            "bible_version" => $this->bible_version,
            "default_days" => $this->default_days,
            "default_length" => DateTime::get_interval_spec($this->default_length),
            "ministries" => $ministries,
            "show_recent_files" => $this->show_recent_files,
        ];
    }
}
