<?php

namespace Obadiah\Calendar;

use Obadiah\App;

App::check();

interface Timezone
{
    /**
     * Return timezone definition, to be merged into main VCalendar lines array.
     *
     * @return string[]
     */
    public function get_definition(): array;
}
