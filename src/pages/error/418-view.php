<?php

namespace Obadiah\Pages\Error;

use Obadiah\App;
use Obadiah\Pages\Parts\Header\Header_Model;
use Obadiah\Response\View;

App::check();

/** @var View $this */

$this->header(new Header_Model("I'm a teapot"));
$this->footer();
