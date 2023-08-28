<?php

namespace Feeds\Pages\Refresh;

use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Response\View;

App::check();

/** @var View $this */

?>

<div class="prayer-page-footer mt-2">
    <small>
        <p class="d-flex justify-content-between">
            <span><?php _h(C::$refresh->footer_page_2_left); ?></span>
            <span><?php _h(C::$refresh->footer_page_2_right); ?></span>
        </p>
    </small>
</div>
