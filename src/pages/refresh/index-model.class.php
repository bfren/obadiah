<?php

namespace Feeds\Pages\Refresh;

use Feeds\App;
use Feeds\Cache\Cache;
use Feeds\Lectionary\Lectionary;
use Feeds\Refresh\Day;

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
    ) {
    }
}
