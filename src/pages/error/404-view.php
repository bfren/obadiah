<?php

namespace Obadiah\Pages\Error;

use Obadiah\App;
use Obadiah\Config\Config as C;
use Obadiah\Pages\Parts\Header\Header_Model;
use Obadiah\Response\View;

App::check();

/** @var View $this */
/** @var string $model */

$this->header(new Header_Model("Not Found", subtitle: "The page you requested could not be found, please try again."));

if (!C::$general->production) : ?>

    <p class="mt-2">Page requested: <?php _e($model); ?></p>

<?php endif;

$this->footer();
