<?php

namespace Feeds\Pages\Rota;

use Feeds\App;

App::check();

class Index_Model
{
    public function __construct(
        public readonly array $filters,
        public readonly array $ten_thirty,
        public readonly array $wednesday,
        public readonly array $days_of_the_week,
        public readonly array $people,
        public readonly array $series,
        public readonly array $days
    ) {
    }
}
