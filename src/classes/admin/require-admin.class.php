<?php

namespace Obadiah\Admin;

use Attribute;
use Obadiah\App;

App::check();

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION)]
class Require_Admin {}
