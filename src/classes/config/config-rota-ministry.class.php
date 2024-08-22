<?php

namespace Obadiah\Config;

use Obadiah\App;
use Obadiah\Helpers\Arr;

App::check();

class Config_Rota_Ministry
{
    /**
     * The Church Suite ministry name (e.g. 'Readings').
     *
     * @var string
     */
    public readonly string $name;

    /**
     * Optional ministry name override (e.g. 'Reader').
     *
     * @var string
     */
    public readonly ?string $description;

    /**
     * Optional ministry name abbreviation (e.g. 'R').
     *
     * @var string
     */
    public readonly ?string $abbreviation;

    /**
     * Get values from a rota configuration ministries array.
     *
     * @param array $config             Rota configuration ministries array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->name = Arr::get($config, "name", "");
        $this->description = Arr::get($config, "desc");
        $this->abbreviation = Arr::get($config, "abbv");
    }
}
