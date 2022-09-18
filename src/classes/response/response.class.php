<?php

namespace Feeds\Response;

use Feeds\App;
use Feeds\Request\Request;

App::check();

class Response
{
    /**
     * Close session and redirect to $uri.
     *
     * @param string $uri               Redirect URI.
     * @param bool $include_path        If true, the request path will be included in the redirect URL.
     * @return void
     */
    public static function redirect(string $uri, bool $include_path = false): void
    {
        // add requested URI
        if ($include_path) {
            $uri = sprintf("%s?requested=%s", $uri, Request::$uri);
        }

        // close session
        session_write_close();

        // redirect
        header(sprintf("Location: %s", $uri));
    }
}
