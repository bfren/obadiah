<?php

namespace Feeds\Admin;

use Attribute;
use Feeds\App;

App::check();

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION)]
class Require_Admin
{
}
