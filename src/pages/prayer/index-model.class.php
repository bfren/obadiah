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
     */
    public function __construct(
        public readonly ?Result $result,
        public readonly array $months
    )
    {

    }
}
