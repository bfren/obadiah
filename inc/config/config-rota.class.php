<?php

namespace Feeds\Config;

use Feeds\Helpers\Arr;

class Config_Rota
{
    /**
     * The number of days of the rota to show on the rota home page.
     *
     * @var int
     */
    public readonly int $default_days;

    /**
     * Array of roles on this rota, with override descriptions and abbreviations.
     *
     * @var Config_Rota_Role[]
     */
    public readonly array $roles;

    /**
     * Get values from rota configuration array.
     *
     * @param array $config             Rota configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->default_days = $config["default_days"];
        $roles = array();
        foreach ($config["roles"] as $role) {
            $roles[] = new Config_Rota_Role($role);
        }
        $this->roles = $roles;
    }

    /**
     * Get the abbreviation for a role if it is set.
     *
     * @param string $role_name         Role name.
     * @return null|string              Abbreviation or null if not set.
     */
    public function get_abbreviation(string $role_name): ?string
    {
        // get matching roles
        $match = Arr::match($this->roles, function (Config_Rota_Role $role) use ($role_name) {
            return ($role->name == $role_name || $role->description == $role_name) && $role->abbreviation;
        });

        // return abbreviation if there is a match
        if (count($match) == 1) {
            return $match[0]->abbreviation;
        }

        // return null if no abbreviation found
        return null;
    }
}
