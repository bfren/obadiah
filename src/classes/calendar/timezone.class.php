<?php

namespace Feeds\Calendar;

use Feeds\App;

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
