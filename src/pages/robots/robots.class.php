<?php

namespace Obadiah\Pages\Robots;

use Obadiah\App;
use Obadiah\Response\Text;

App::check();

class Robots
{
    /**
     * Disallow access.
     *
     * @return Text                 Text response object.
     */
    public function index_get(): Text
    {
        return new Text("User-agent: *\nDisallow: /");
    }
}
