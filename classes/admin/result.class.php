<?php

namespace Feeds\Admin;

use Feeds\App;

App::check();

class Result
{
    /**
     * Create Result object.
     *
     * @param bool $success             Whether or not the operation was a success.
     * @param null|string $message      Optional message.
     * @return void
     */
    private function __construct(
        public readonly bool $success,
        public readonly ?string $message
    ) {
    }

    /**
     * Return a success result with an optional message.
     *
     * @param null|string $message      Optional message.
     * @return Result                   Success result.
     */
    public static function success(?string $message): Result
    {
        return new Result(true, $message);
    }

    /**
     * Return a failure result with a message.
     *
     * @param string $message           Message.
     * @return Result                   Failure result.
     */
    public static function failure(string $message): Result
    {
        return new Result(false, $message);
    }
}
