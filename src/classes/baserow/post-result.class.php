<?php

namespace Obadiah\Baserow;

use Obadiah\App;

App::check();

class Post_Result
{
    /**
     * Holds information about a Baserow POST request.
     *
     * @param int $status               HTTP status to use.
     * @param mixed $content            Response content - either 'OK' or information about an error.
     * @return void
     */
    public function __construct(public readonly int $status, public readonly mixed $content) {}
}
