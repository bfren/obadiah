<?php

namespace Feeds\Pages\Prayer;

use Feeds\Admin\Result;
use Feeds\App;

App::check();

class Index_Model
{
    /**
     * Create Index model.
     *
     * @param null|Result $result       Operation result.
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
