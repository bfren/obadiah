<?php

namespace Feeds\Json;

use Feeds\Admin\Result;
use Feeds\App;
use Feeds\Request\Request;

App::check();

class Json
{
    /**
     * Output JSON response and exit.
     *
     * @param mixed $obj                Object will be encoded as JSON and printed.
     * @param int $response_code        Optional HTTP response code.
     * @param int $last_modified        Optional last modified timestamp.
     * @return void
     */
    public static function output(mixed $obj, ?int $response_code = null, ?int $last_modified = null): void
    {
        // ensure that we don't try to encode a null object
        $obj_to_encode = $obj ?: Result::failure("Something has gone wrong, please try again.");

        // if response code is not set and $obj is a Result that failed, return 400 (bad request)
        if ($response_code == null && $obj_to_encode instanceof Result && !$obj->success) {
            $response_code = 400;
        }

        // output JSON header using the response code
        if (Request::$debug) {
            header("Content-Type: text/plain", response_code: $response_code ?: 200);
        } else {
            header("Access-Control-Allow-Origin: *");
            header("Content-Type: text/json; charset=utf-8", response_code: $response_code ?: 200);
            header(sprintf("Last-Modified: %s", gmdate("D, d M Y H:i:s", $last_modified ?: time())));
        }

        // output JSON and exit
        echo json_encode($obj_to_encode);
        exit;
    }
}
