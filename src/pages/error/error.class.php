<?php

namespace Obadiah\Pages\Error;

use Obadiah\App;
use Obadiah\Request\Request;
use Obadiah\Response\Redirect;
use Obadiah\Response\View;
use Throwable;

App::check();

class Error
{
    /**
     * Deny access and redirect to login page.
     *
     * @return Redirect
     */
    public static function denied(): Redirect
    {
        Request::$session->deny();
        return new Redirect("/auth/login", include_path: true);
    }

    /**
     * Return 'Not Found' view.
     *
     * @return View
     */
    public static function not_found(): View
    {
        return new View("error", "404", model: Request::$uri, status: 404);
    }

    /**
     * Return 'Server Error' view.
     *
     * @param Throwable $model          Error model.
     * @return View
     */
    public static function server_error(Throwable $model): View
    {
        return new View("error", "500", model: $model, status: 500);
    }

    /**
     * Return 'I'm a Teapot'.
     *
     * @return View
     */
    public static function teapot(): View
    {
        return new View("error", "418", status: 418);
    }
}
