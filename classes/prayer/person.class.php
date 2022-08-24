<?php

namespace Feeds\Prayer;

use Feeds\App;

App::check();

class Person
{
    /**
     * Create Person object.
     *
     * @param string $first_name        Person's first name.
     * @param string $last_name         Person's last name.
     * @param bool $is_child            Whether or not this person is a child.
     * @return void
     */
    public function __construct(
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly bool $is_child
    ) {
    }

    /**
     * Return the person's full name.
     *
     * @return string                   Full name.
     */
    public function get_full_name(): string
    {
        return sprintf("%s %s", $this->first_name, $this->last_name);
    }
}
