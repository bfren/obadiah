<?php

namespace Obadiah\Response;

use Obadiah\App;

App::check();

class Text extends Action
{
    /**
     * Store plain text and add headers.
     *
     * @param string $text              Plain text value.
     * @param int $status               HTTP status code.
     * @param int|null $last_modified   Optional last modified timestamp.
     * @return void
     */
    public function __construct(
        public readonly string $text,
        int $status = 200,
        ?int $last_modified = null
    ) {
        // add default headers
        parent::__construct($status);

        // add debug headers
        if ($this->add_debug_headers()) {
            return;
        }

        // add standard plain text headers
        $this->add_header("Content-Type", "text/plain; charset=utf-8");
        $this->add_last_modified_header($last_modified);
    }

    /**
     * Execute the action, encoding and printing the plain text value.
     *
     * @return void
     */
    public function execute(): void
    {
        print_r($this->text);
    }
}
