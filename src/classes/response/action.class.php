<?php

namespace Feeds\Response;

use Feeds\App;

App::check();

abstract class Action
{
    public abstract function execute(): void;
}
