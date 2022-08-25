<?php

namespace Feeds\Json;

use Feeds\App;
use Throwable;

App::check();

class Action
{
    /**
     * Create Action object.
     *
     * @param string $name              Action name.
     * @param mixed $data               Action data.
     * @return void
     */
    public function __construct(
        public readonly string $name,
        public readonly mixed $data
    ) {
    }

    /**
     * Decode an input JSON string as an Action - on error will return null.
     *
     * @param string $input             Input JSON.
     * @return null|Action
     */
    public static function decode(string $input): ?Action
    {
        try {
            $json = json_decode($input);
            return new Action($json->action, $json->data);
        } catch (Throwable $th) {
            return null;
        }
    }
}
