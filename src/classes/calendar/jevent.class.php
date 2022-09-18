<?php

namespace Feeds\Calendar;

use Feeds\App;
use Feeds\Config\Config as C;

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
     * @param bool $is_all_day          Whether or not the event is an all day event.
     * @return void
     */
    public function __construct(
        public readonly string $id,
        public readonly string $start,
        public readonly string $end,
        public readonly string $title,
        public readonly ?string $description,
        public readonly bool $is_all_day = false
    ) {
    }

    /**
     * Generate a unique ID for an event.
     *
     * @param int $last_modified        Calendar last modified date.
     * @return string                   Unique hashed ID.
     */
    public static function get_id(int $last_modified): string
    {
        static $count = 0;
        $date = date(C::$formats->json_datetime, $last_modified);
        return sprintf("%06d-%s", $count++, $date);
    }
}
