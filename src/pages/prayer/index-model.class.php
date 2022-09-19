<?php

namespace Feeds\Pages\Prayer;

use Feeds\Admin\Result;
use Feeds\App;

App::check();

class Index_Model
{
    /**
     *
     * @param null|Result $result
     * @param string[] $months
     * @return void
     */
    public function __construct(
        public readonly ?Result $result,
        public readonly array $months
    )
    {

    }
}
