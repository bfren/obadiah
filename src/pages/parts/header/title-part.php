<?php

namespace Feeds\Pages\Parts\Header;

use Feeds\App;
use Feeds\Response\View;

App::check();

/** @var View $this */
/** @var Header_Model $model */

?>
<div class="container-fluid mt-2 border-bottom">
    <h1><?php _e($model->title); ?></h1>
    <?php if ($model->subtitle) : ?>
        <h6><?php _e($model->subtitle); ?></h6>
    <?php endif; ?>
</div>
