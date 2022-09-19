<?php

namespace Feeds\Pages\Error;

use Feeds\App;
use Feeds\Request\Request;
use Feeds\Response\View;
use Throwable;

App::check();

class Error
{
    /**
     * Return 'Not Found' view.
     *
     * @return View
     */
    public function not_found(): View
    {
        return new View("error", "404", model: Request::$uri, status: 404);
    }

    /**
     * Return 'Server Error' view.
     *
     * @param Throwable $model          Error model.
     * @return View
     */
    public function server_error(Throwable $model): View
    {
        return new View("error", "500", model: $model, status: 500);
    }
}
