<?php

namespace Obadiah\Pages\Settings;

use Obadiah\App;
use Obadiah\Response\View;
use Obadiah\Router\Endpoint;

App::check();

class Settings extends Endpoint
{
    /**
     * GET: /
     *
     * @return View
     */
    public function index_get(): View
    {
        return new View("settings", model: new Index_Model());
    }
}
