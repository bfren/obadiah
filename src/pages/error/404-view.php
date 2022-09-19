<?php

namespace Feeds\Pages\Error;

use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Pages\Parts\Header\Header_Model;
use Feeds\View\Html;

App::check();

/** @var Html $this */
/** @var string $model */

$this->header(new Header_Model("Not Found", subtitle: "The page you requested could not be found, please try again."));

if (!C::$general->production) : ?>

    <p class="mt-2">Page requested: <?php _e($model); ?></p>

<?php endif;

$this->footer();
