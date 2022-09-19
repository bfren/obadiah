<?php

namespace Feeds\Pages\Error;

use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Pages\Parts\Header\Header_Model;
use Feeds\View\Html;
use Throwable;

App::check();

/** @var Html $this */
/** @var Throwable $model */

$this->header(new Header_Model("Error", subtitle: "Something went wrong, please try again."));

if (!C::$general->production) : ?>

    <p class="mt-2"><?php _e($model->getMessage()); ?></p>
    <p>Trace:</p>
    <p><?php _h(str_replace("\n", "<br/>", $model->getTraceAsString())) ?></p>

<?php endif;

$this->footer();
