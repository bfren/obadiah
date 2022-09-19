<?php

namespace Feeds\Pages\Prayer;

use Feeds\App;
use Feeds\Prayer\Month;

App::check();

class Print_Model
{
    public function __construct(
        public readonly Month $month
    ) {
    }
}
