<?php

namespace Feeds\Response;

use Feeds\App;
use Feeds\Request\Request;

App::check();

class Json extends Action
{
    /**
     * Create Json and add headers.
     *
     * @param mixed $model              JSON model.
     * @param int $status               HTTP status code.
     * @param null|int $last_modified   Optional last modified timestamp.
     * @return void
     */
    public function __construct(
        public readonly mixed $model,
        public readonly int $status = 200,
        ?int $last_modified = null
    ) {
        parent::__construct();

        // output as plain text on debug
        if (Request::$debug) {
            $this->add_header("Content-Type", "text/plain");
            return;
        }

        // add standard JSON headers
        $this->add_header("Access-Control-Allow-Origin", "*");
        $this->add_header("Content-Type", "text/json; charset=utf-8", $status);
        $this->add_header("Last-Modified", gmdate("D, d M Y H:i:s", $last_modified ?: time()));
    }

    /**
     * Execute the action, encoding and printing the JSON model.
     *
     * @return void
     */
    public function execute(): void
    {
        print_r(json_encode($this->model));
    }
}
