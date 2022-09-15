<?php

namespace Feeds\Wp;

use Feeds\App;

App::check();

class Service
{
    /**
     *
     * @param string $id                Service ID.
     * @param string $start             Start date and time.
     * @param string $end               End date and time.
     * @param string $title             Title.
     * @param string $description       Extended description.
     * @return void
     */
    public function __construct(
        public readonly string $id,
        public readonly string $start,
        public readonly string $end,
        public readonly string $title,
        public readonly string $description
    ) {
    }
}
