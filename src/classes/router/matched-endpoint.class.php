<?php

namespace Obadiah\Router;

use Obadiah\App;

App::check();

class Matched_Endpoint
{
    /**
     * Data structure for representing an endpoint matched from a URI.
     *
     * @param Route $route                      The matching Route.
     * @param string $action                    The matching action.
     */
    public function __construct(
        public readonly Route $route,
        public readonly string $action
    ) {}
}
