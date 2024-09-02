<?php

namespace Obadiah\Pages\Refresh;

use Obadiah\App;
use Obadiah\Refresh\Day;

App::check();

class Index_Model
{
    /**
     * Create Index model.
     *
     * @param Day $today                Today's refresh calendar values.
     */
    public function __construct(
        public readonly Day $today
    ) {}
}
