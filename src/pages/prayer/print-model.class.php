<?php

namespace Feeds\Pages\Prayer;

use Feeds\App;
use Feeds\Prayer\Month;

App::check();

class Print_Model
{
    /**
     * Create Print model.
     *
     * @param Month $last_month         Rota filter values to show this week's services.
     * @param Month $this_month         Rota filter values to show this week's services.
     * @param Month $next_month         Rota filter values to show this week's services.
     */
    public function __construct(
        public readonly Month $last_month,
        public readonly Month $this_month,
        public readonly Month $next_month
    ) {
    }
}
