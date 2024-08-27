<?php

namespace Obadiah\Pages\Rota;

use Obadiah\App;
use Obadiah\Rota\Combined_Day;

App::check();

class Index_Model
{
    /**
     * Create Index model.
     *
     * @param mixed[] $filters                      Array of rota filters.
     * @param array<string, mixed> $ten_thirty      Sunday 10:30 service filter preset.
     * @param array<int, string> $days_of_the_week  Array of the days of the week, starting with Sunday.
     * @param string[] $people                      All the people in the rota.
     * @param string[] $series                      All the series in the rota.
     * @param Combined_Day[] $days                  The days containing service information.
     */
    public function __construct(
        public readonly array $filters,
        public readonly array $ten_thirty,
        public readonly array $days_of_the_week,
        public readonly array $people,
        public readonly array $series,
        public readonly array $days
    ) {}
}
