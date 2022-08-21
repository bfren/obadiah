<?php

namespace Feeds\Config;

use Feeds\Helpers\Arr;

class Config_Rota_Role
{
    /**
     * The Church Suite role name (e.g. 'Readings').
     *
     * @var string
     */
    public readonly string $name;

    /**
     * Optional role name override (e.g. 'Reader').
     *
     * @var string
     */
    public readonly ?string $description;

    /**
     * Optional role name abbreviation (e.g. 'R').
     *
     * @var string
     */
    public readonly ?string $abbreviation;

    /**
     * Get values from a rota configuration role array.
     *
     * @param array $config             Rota configuration roles array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->name = $config["name"];
        $this->description = Arr::get($config, "desc");
        $this->abbreviation = Arr::get($config, "abbv");
    }
}
