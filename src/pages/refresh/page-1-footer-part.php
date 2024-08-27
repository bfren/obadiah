<?php

namespace Obadiah\Pages\Refresh;

use Obadiah\App;
use Obadiah\Config\Config as C;
use Obadiah\Response\View;

App::check();

/** @var View $this */

?>

<div class="prayer-page-footer mt-2">
    <small>
        <p class="d-flex justify-content-between">
            <span><?php _h(C::$refresh->footer_page_1_left); ?></span>
            <span><?php _h(C::$refresh->footer_page_1_right); ?></span>
        </p>
    </small>
</div>