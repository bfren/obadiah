<?php

namespace Feeds\Pages\Error;

use Feeds\Pages\Parts\Header\Header;

/** @var \Feeds\View\Html $this */

$this->header(new Header("Not Found", "The page you requested could not be found, please try again."));

$this->footer();
