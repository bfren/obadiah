<?php

namespace Obadiah\Api\Ajax;

use Obadiah\Admin\Result;
use Obadiah\App;
use Obadiah\Prayer\Month;
use Obadiah\Request\Request;
use Obadiah\Response\Json;
use Obadiah\Router\Endpoint;
use Throwable;

App::check();

class Ajax extends Endpoint
{
    /**
     * Holds an optional JSON result.
     *
     * @var Json|null
     */
    private ?Json $result = null;

    /**
     * Get and validate JSON input from php://input.
     *
     * @return mixed
     */
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
            $json = json_decode($input, flags: JSON_THROW_ON_ERROR);
        } catch (Throwable $th) {
            _l_throwable($th);
            $this->result = new Json(Result::failure("Invalid request."), 400);
            return null;
        }

        // return decoded JSON object
        return $json;
    }

    /**
     * POST: /api/ajax/month (called from Prayer Calendar edit page).
     *
     * @return Json                     JSON result.
     */
    public function month_post(): Json
    {
        // get data
        $data = $this->get_input();

        // check for failure result
        if ($this->result) {
            return $this->result;
        }

        // save month data
        $result = Month::save($data);
        return new Json($result);
    }
}
