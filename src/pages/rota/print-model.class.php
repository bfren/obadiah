<?php

namespace Obadiah\Pages\Rota;

use Obadiah\App;
use Obadiah\Rota\Combined_Day;

App::check();

class Print_Model
{
    /**
     *
     * @param string|null $time         Optional time filter.
     * @param string|null $day          Optional day filter.
     * @param string|null $person       Optional person filter.
     * @param Combined_Day[] $days      The days containing service information.
     */
    public function __construct(
        public readonly ?string $time,
        public readonly ?string $day,
        public readonly ?string $person,
        public readonly array $days
    ) {
    }
}
