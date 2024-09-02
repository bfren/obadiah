<?php

namespace Obadiah\Router;

use Obadiah\App;

App::check();

class Route
{
    /**
     * Create route object.
     *
     * @template T of Endpoint
     * @param class-string<T> $endpoint             Endpoint class name (with full namespace).
     * @param bool $requires_auth                   Whether or not the route requires authentication.
     * @param bool $requires_admin                  Whether or not the route requires admin permissions.
     * @return void
     */
    public function __construct(
        public readonly string $endpoint,
        public readonly bool $requires_auth = true,
        public readonly bool $requires_admin = true
    ) {}
}
