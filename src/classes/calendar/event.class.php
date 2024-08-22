<?php

namespace Obadiah\Calendar;

use DateTimeImmutable;
use Obadiah\App;
use Obadiah\Config\Config as C;
use Obadiah\Request\Request;
use JsonSerializable;

App::check();

class Event implements JsonSerializable
{
    /**
     *
     * @param string $uid               Unique ID.
     * @param DateTimeImmutable $start  Start date and time.
     * @param DateTimeImmutable $end    End date and time.
     * @param string $title             Title.
     * @param string $location          Location.
     * @param null|string $description  Extended description.
     * @param bool $is_all_day          Whether or not the event is an all day event.
     * @return void
     */
    public function __construct(
        public readonly string $uid,
        public readonly DateTimeImmutable $start,
        public readonly DateTimeImmutable $end,
        public readonly string $title,
        public readonly string $location,
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
    public static function create_uid(int $last_modified): string
    {
        static $count = 0;
        $date = date("Ymd\THis", $last_modified);
        $ip = Request::$server->string("REMOTE_ADDR");
        return sprintf("%06d-%s@%s", $count++, $date, $ip);
    }

    /**
     * Customise JSON serialisation.
     *
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return array(
            "id" => $this->uid,
            "start" => $this->start->format(C::$formats->json_datetime),
            "end" => $this->end->format(C::$formats->json_datetime),
            "title" => $this->title,
            "location" => $this->location ?: C::$events->default_location,
            "description" => $this->description
        );
    }
}
