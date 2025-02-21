<?php

namespace Obadiah\Pages\Settings;

use Obadiah\App;
use Obadiah\Config\Config_Section;

App::check();

class Form_Model
{
    /**
     * Create Form model.
     *
     * @param string $id                HTML ID.
     * @param Config_Section $values    Form values.
     */
    public function __construct(
        public readonly string $id,
        public readonly Config_Section $values
    ) {}

    /**
     * Returns true if the value type is supported by the Form helpers.
     *
     * @param string $type              Value type.
     * @return bool                     Whether or not the type is supported.
     */
    public function is_supported(string $type): bool
    {
        $supported = ["boolean", "integer", "string"];
        return in_array($type, $supported);
    }
}
