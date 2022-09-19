<?php

namespace Feeds\Response;

use Feeds\App;
use Feeds\Request\Request;

App::check();

class Redirect extends Action
{
    /**
     * Create redirect object.
     *
     * @param string $uri               URI to redirect the user to.
     * @param bool $include_path        Whether or not to include the currently requested path.
     * @param int $status               HTTP status code.
     * @return void
     */
    public function __construct(
        public readonly string $uri,
        public readonly bool $include_path = false,
        public readonly int $status = 307
    ) {
        // build requested URI
        $uri = $this->include_path
            ? sprintf("%s?requested=%s", $this->uri, Request::$uri)
            : $this->uri;

        // add redirect header
        $this->add_header("Location", $uri, $status);
    }

    /**
     * Do nothing - redirect is handled when headers are sent.
     *
     * @return void
     */
    public function execute(): void
    {
    }
}
