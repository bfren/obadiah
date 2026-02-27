<?php

namespace Obadiah\Pages\Bible;

use Obadiah\App;
use Obadiah\NetBible\Api;
use Obadiah\Request\Request;
use Obadiah\Response\View;
use Obadiah\Router\Endpoint;

App::check();

class Bible extends Endpoint
{
    /**
     * GET: /bible
     *
     * @return View
     */
    public function index_get(): View
    {
        // get the requested passage reference
        $passage = Request::$get->string("passage", "");

        // use the API to get the Bible text
        $netbible = new Api();
        $text = $netbible->get_text($passage);

        // format the passage reference with uppercase words
        return new View("bible", model: new Index_Model(ucwords($passage), $text));
    }
}
