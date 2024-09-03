<?php

namespace Obadiah\Response;

use Obadiah\App;
use Obadiah\Request\Request;

App::check();

class Json extends Action
{
    /**
     * Store JSON model and add headers.
     *
     * @param mixed $model              JSON model.
     * @param int $status               HTTP status code.
     * @param int|null $last_modified   Optional last modified timestamp.
     * @return void
     */
    public function __construct(
        public readonly mixed $model,
        int $status = 200,
        ?int $last_modified = null
    ) {
        // add default headers
        parent::__construct($status);

        // add debug headers
        if ($this->add_debug_headers()) {
            return;
        }

        // add standard JSON headers
        $this->add_header("Access-Control-Allow-Origin", "*");
        $this->add_header("Content-Type", "text/json; charset=utf-8");
        $this->add_last_modified_header($last_modified);
    }

    /**
     * Execute the action, encoding and printing the JSON model.
     *
     * @return void
     */
    public function execute(): void
    {
        // on debug output pretty printed JSON
        $json = Request::$debug
            ? json_encode($this->model, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT)
            : json_encode($this->model);

        // print JSON
        print_r($json);
    }
}
