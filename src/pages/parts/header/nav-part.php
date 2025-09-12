<?php

namespace Obadiah\Pages\Parts\Header;

use Obadiah\App;
use Obadiah\Helpers\Image;
use Obadiah\Request\Request;
use Obadiah\Response\View;

App::check();

/** @var View $this */
/** @var Header_Model $model */

// build navigation links
if (Request::$session->is_authorised) {
    $links = array(
        "Home" => "/",
        "Rota" => "/rota",
        "Refresh" => "/refresh",
        "Bible" => "/bible",
        "About" => "/about",
        "Log Out" => "/auth/logout"
    );
    if (Request::$session->is_admin) {
        $links = array_merge(
            array_slice($links, 0, 1),
            array("Upload" => "/upload"),
            array_slice($links, 1, 1),
            array("Prayer Calendar" => "/prayer"),
            array_slice($links, 3, 1),
            array("Settings" => "/settings"),
            array_slice($links, 4),
        );
    }
} else {
    $links = [];
}

?>

<header class="d-print-none">
    <nav class="navbar navbar-expand-md navbar-light bg-light py-3 border-bottom">
        <div class="container-fluid d-flex justify-content-between">
            <a href="/" class="d-flex align-items-center mb-md-0 text-dark text-decoration-none">
                <?php Image::echo_logo("logo me-4"); ?>
                <span class="fs-4">Obadiah</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse text-end" id="navbar">
                <ul class="navbar-nav ms-5 mb-0">
                    <?php foreach ($links as $link_title => $link) : ?>
                        <li class="nav-item ms-2">
                            <?php if ($model->title == $link_title) : ?>
                                <a href="<?php _e($link); ?>" class="nav-link active" aria-current="page"><?php _e($link_title); ?></a>
                            <?php else : ?>
                                <a href="<?php _e($link); ?>" class="nav-link"><?php _e($link_title); ?></a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>