<?php

namespace Feeds\Pages\Refresh;

use Feeds\App;
use Feeds\Helpers\Image;
use Feeds\Response\View;

App::check();

/** @var View $this */
/** @var string $model */

?>

<h2 class="prayer-calendar-title row">
    <div class="col-3"><a href="/refresh"><?php Image::echo_logo("logo me-4"); ?></a></div>
    <div class="col-6 text-center">Refresh Calendar</div>
    <div class="col-3 text-end"><?php _e($model); ?></div>
</h2>
