<?php

namespace Feeds\Pages\Error;

use Feeds\App;
use Feeds\Request\Request;
use Feeds\Response\Html;

App::check();

class Error
{
    public function not_found() : Html
    {
        return new Html("error", "404", Request::$uri);
    }
}
