<?php

namespace Feeds\Pages;

use Feeds\App;

App::check();

/** @var string $title */
/** @var null|string $subtitle */

?>
<div class="container-fluid mt-2 border-bottom">
    <h1><?php echo $title; ?></h1>
    <?php if (isset($subtitle)) : ?>
        <h6><?php echo $subtitle; ?></h6>
    <?php endif; ?>
</div>
