<?php

namespace Feeds\Pages\Ajax;

use Feeds\Admin\Result;
use Feeds\App;
use Feeds\Prayer\Month;
use Feeds\Request\Request;
use Feeds\Response\Json;
use Throwable;

App::check();

class Ajax
{
    private ?Json $result = null;

    private function get_input(): mixed
    {
        // check auth
        if (!Request::$session->is_admin) {
            $this->result = new Json(Result::failure("Unauthorised."), 401);
            return null;
        }

        // get input text
        $input = file_get_contents("php://input");
        if (!$input) {
            $this->result = new Json(Result::failure("No input."), 400);
            return null;
        }

        // decode JSON
        try {
            $json = json_decode($input);
        } catch (Throwable $th) {
            $this->result = new Json(Result::failure("Invalid request."), 400);
            return null;
        }

        // return decoded JSON object
        return $json;
    }

    public function month_post(): Json
    {
        // get data
        $data = $this->get_input();

        // check for failure result
        if($this->result) {
            return $this->result;
        }

        // save month data
        $result = Month::save($data);
        return new Json($result);
    }
}
