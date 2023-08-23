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
    <div class="col-3"><a href="/prayer"><?php Image::echo_logo("logo me-4"); ?></a></div>
    <div class="col-6 text-center">Refresh Calendar</div>
    <div class="col-3 text-end"><?php _e($model); ?></div>
</h2>

<!-- <div class="prayer-calendar-verse">
    Devote yourselves to prayer, being watchful and thankful.
    <span class="ref">Colossians 4.2 (NIV)</span>
</div> -->
