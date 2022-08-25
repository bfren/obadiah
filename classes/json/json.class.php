<?php

namespace Feeds\Json;

use Feeds\Admin\Result;
use Feeds\App;

App::check();

class Json
{
    /**
     * Output JSON response and exit.
     *
     * @param mixed $obj                Object will be encoded as JSON and printed.
     * @param int $response_code        Optional HTTP response code.
     * @return void
     */
    public static function output(mixed $obj, ?int $response_code = null): void
    {
        // ensure that we don't try to encode a null object
        $obj_to_encode = $obj ?: Result::failure("Something has gone wrong, please try again.");

        // if response code is not set and $obj is a Result that failed, return 400 (bad request)
        if ($response_code == null && $obj_to_encode instanceof Result && !$obj->success) {
            $response_code = 400;
        }

        // output JSON header using the response code
        header("Content-Type: application/json", response_code: $response_code ?: 200);

        // output JSON and exit
        echo json_encode($obj_to_encode);
        exit;
    }
}
