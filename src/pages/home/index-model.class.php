<?php

namespace Feeds\Pages\Home;

use Feeds\App;

App::check();

class Index_Model
{
    /**
     * Create Index model.
     *
     * @param array $this_week          Rota filter values to show this week's services.
     * @param array $ten_thirty         Rota filter values to show upcoming 10:30 services.
     * @param array $refresh            Query values to enable refresh ICS feed.
     * @return void
     */
    public function __construct(
        public readonly array $this_week,
        public readonly array $ten_thirty,
        public readonly array $refresh
    ) {
    }
}
