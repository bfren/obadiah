<?php

namespace Feeds\Pages\Test;

use Feeds\App;
use Feeds\Response\Html;

App::check();

class Test
{
    public function show(): Html
    {
        return new Html("test", "fred", $_SERVER);
    }
}
