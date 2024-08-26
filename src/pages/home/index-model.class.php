<?php

namespace Obadiah\Pages\Home;

use Obadiah\App;

App::check();

class Index_Model
{
    /**
     * Create Index model.
     *
     * @param mixed[] $this_week        Rota filter values to show this week's services.
     * @param mixed[] $upcoming         Rota filter values to show upcoming Sunday services.
     * @param mixed[] $refresh_print    Query values to link to printable version of this month's refresh calendar.
     * @param mixed[] $refresh_feed     Query values to enable refresh ICS feed.
     */
    public function __construct(
        public readonly array $this_week,
        public readonly array $upcoming,
        public readonly array $refresh_print,
        public readonly array $refresh_feed
    ) {
    }
}
