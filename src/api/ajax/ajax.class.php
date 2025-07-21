<?php

namespace Obadiah\Api\Ajax;

use Obadiah\Admin\Result;
use Obadiah\App;
use Obadiah\Config\Config as C;
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

    /**
     * Save configuration.
     *
     * @param string $name              The name of the config section to be saved.
     * @return Json                     JSON result.
     */
    private function save_config(string $name): Json
    {
        // get data
        $data = $this->get_input();

        // check for failure result
        if ($this->result) {
            return $this->result;
        }

        // get config array and update with new values
        $config = C::as_array();
        $new_values = json_decode(json_encode($data) ?: "[]", true);
        $updated_config = array_merge($config[$name], $new_values);
        $config[$name] = $updated_config;

        // save settings to config file
        try {
            C::store_config($config, true);
        } catch (Throwable $th) {
            _l_throwable($th);
            return new Json(Result::failure($th->getMessage()), 500);
        }

        return new Json(Result::success());
    }

    /**
     * POST: /api/ajax/settings_general (called from Settings page)
     *
     * @return Json                     JSON result.
     */
    public function settings_general_post(): Json
    {
        return $this->save_config("general");
    }

    /**
     * POST: /api/ajax/settings_baserow (called from Settings page)
     *
     * @return Json                     JSON result.
     */
    public function settings_baserow_post(): Json
    {
        return $this->save_config("general");
    }

    /**
     * POST: /api/ajax/settings_cache (called from Settings page)
     *
     * @return Json                     JSON result.
     */
    public function settings_cache_post(): Json
    {
        return $this->save_config("general");
    }

    /**
     * POST: /api/ajax/settings_churchsuite (called from Settings page)
     *
     * @return Json                     JSON result.
     */
    public function settings_churchsuite_post(): Json
    {
        return $this->save_config("churchsuite");
    }

    /**
     * POST: /api/ajax/settings_events (called from Settings page)
     *
     * @return Json                     JSON result.
     */
    public function settings_events_post(): Json
    {
        return $this->save_config("events");
    }

    /**
     * POST: /api/ajax/settings_formats (called from Settings page)
     *
     * @return Json                     JSON result.
     */
    public function settings_formats_post(): Json
    {
        return $this->save_config("formats");
    }

    /**
     * POST: /api/ajax/settings_login (called from Settings page)
     *
     * @return Json                     JSON result.
     */
    public function settings_login_post(): Json
    {
        return $this->save_config("login");
    }

    /**
     * POST: /api/ajax/settings_prayer (called from Settings page)
     *
     * @return Json                     JSON result.
     */
    public function settings_prayer_post(): Json
    {
        return $this->save_config("prayer");
    }

    /**
     * POST: /api/ajax/settings_refresh (called from Settings page)
     *
     * @return Json                     JSON result.
     */
    public function settings_refresh_post(): Json
    {
        return $this->save_config("refresh");
    }

    /**
     * POST: /api/ajax/settings_rota (called from Settings page)
     *
     * @return Json                     JSON result.
     */
    public function settings_rota_post(): Json
    {
        return $this->save_config("rota");
    }
}
