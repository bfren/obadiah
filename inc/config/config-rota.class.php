<?php

namespace Feeds\Config;

class Config_Rota
{
    /**
     * The number of days of the rota to show on the rota home page.
     *
     * @var int
     */
    public int $default_days;

    /**
     * Get values from rota configuration array.
     *
     * @param array $config             Rota configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->default_days = $config["default_days"];
    }
}
