<?php

namespace Obadiah\Pages\Error;

use Obadiah\App;
use Obadiah\Config\Config as C;
use Obadiah\Pages\Parts\Header\Header_Model;
use Obadiah\View\Html;
use Throwable;

App::check();

/** @var Html $this */
/** @var Throwable $model */

$this->header(new Header_Model("Error", subtitle: "Something went wrong, please try again."));

if (!C::$general->production) : ?>

    <p class="mt-2"><?php _e($model->getMessage()); ?></p>
    <p>Trace:</p>
    <p><?php _h(join("<br/>", str_replace("\n", "<br/>", [$model->getMessage(), $model->getTraceAsString()]))) ?></p>

<?php endif;

$this->footer();
