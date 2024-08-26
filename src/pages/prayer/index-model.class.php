<?php

namespace Obadiah\Pages\Prayer;

use Obadiah\Admin\Result;
use Obadiah\App;

App::check();

class Index_Model
{
    /**
     * Create Index model.
     *
     * @param Result|null $result       Operation result.
     * @param string[] $months          Array of months in the cache.
     * @param string $next_month        Month ID of next month.
     */
    public function __construct(
        public readonly ?Result $result,
        public readonly array $months,
        public readonly string $next_month
    ) {
    }
}
