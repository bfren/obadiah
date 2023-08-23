<?php

namespace Feeds\Pages\Rota;

use Feeds\App;
use Feeds\Rota\Combined_Day;

App::check();

class Index_Model
{
    /**
     * Create Index model.
     *
     * @param array $filters                Array of rota filters.
     * @param array $ten_thirty             Sunday 10:30 service filter preset.
     * @param string[] $days_of_the_week    Array of the days of the week, starting with Sunday.
     * @param array $people                 All the people in the rota.
     * @param array $series                 All the series in the rota.
     * @param Combined_Day[] $days          The days containing service information.
     */
    public function __construct(
        public readonly array $filters,
        public readonly array $ten_thirty,
        public readonly array $days_of_the_week,
        public readonly array $people,
        public readonly array $series,
        public readonly array $days
    ) {
    }
}
