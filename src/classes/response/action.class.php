<?php

namespace Feeds\Response;

use Feeds\App;

App::check();

abstract class Action
{
    /**
     * Array of headers.
     *
     * @var Header[]
     */
    private array $headers = array();

    /**
     * Add default headers.
     *
     * @return void
     */
    public function __construct()
    {
        $this->add_header("X-Software", "bfren/ccf");
    }

    /**
     * Add a header to the response.
     *
     * @param string $key               Header key.
     * @param string $value             Header value.
     * @param int $status               HTTP status code.
     * @return void
     */
    protected function add_header(string $key, string $value, int $status = 200)
    {
        $this->headers[$key] = new Header($key, $value, $status);
    }

    /**
     * Send headers to the response.
     *
     * @return void
     */
    public function send_headers()
    {
        foreach ($this->headers as $header) {
            header(sprintf("%s: %s", $header->key, $header->value), response_code: $header->status);
        }
    }

    /**
     * Execute action.
     *
     * @return void
     */
    public abstract function execute(): void;
}
