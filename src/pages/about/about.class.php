<?php

namespace Obadiah\Pages\About;

use Obadiah\App;
use Obadiah\Response\View;
use Obadiah\Router\Endpoint;

App::check();

class About extends Endpoint
{
    /**
     * GET: /
     *
     * @return View
     */
    public function index_get(): View
    {
        return new View("about", model: new Index_Model());
    }
}
