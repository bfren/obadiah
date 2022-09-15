<?php

namespace Feeds\Calendar;

use Feeds\App;

App::check();

class JEvent
{
    /**
     *
     * @param string $id                Event ID.
     * @param string $start             Start date and time.
     * @param string $end               End date and time.
     * @param string $title             Title.
     * @param null|string $description  Extended description.
     * @return void
     */
    public function __construct(
        public readonly string $id,
        public readonly string $start,
        public readonly string $end,
        public readonly string $title,
        public readonly ?string $description
    ) {
    }
}
