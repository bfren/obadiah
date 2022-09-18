<?php

namespace Feeds\Pages;

use Feeds\App;

App::check();

/** @var string $title */
/** @var null|string $subtitle */

?>
<div class="container-fluid mt-2 border-bottom">
    <h1><?php _e($title); ?></h1>
    <?php if (isset($subtitle)) : ?>
        <h6><?php _e($subtitle); ?></h6>
    <?php endif; ?>
</div>
