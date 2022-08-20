<?php

namespace Feeds\Pages;

use Feeds\Request\Request;

defined("IDX") || die("Nice try.");

/** @var string $title */

// build navigation links
if (Request::$auth) {
    $links = array(
        "Home" => "/",
        "Rota" => "/rota",
        "Prayer" => "/prayer",
        "Log Out" => "/logout.php"
    );
    if (Request::is_admin()) {
        $links = array_merge(
            array_slice($links, 0, 1),
            array("Admin" => "/admin"),
            array_slice($links, 1)
        );
    }
} else {
    $links = array();
}

?>

<div class="container-fluid d-print-none">
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
            <img class="logo me-4" style="max-height: 40px;" src="/img/logo-small.png" alt="Christ Church Selly Park" />
            <span class="fs-4">Church Suite Feeds</span>
        </a>

        <ul class="nav nav-pills">
            <?php foreach ($links as $link_title => $link) : ?>
                <li class="nav-item">
                    <?php if ($title == $link_title) : ?>
                        <a href="<?php echo $link; ?>" class="nav-link active" aria-current="page"><?php echo $link_title; ?></a>
                    <?php else : ?>
                        <a href="<?php echo $link; ?>" class="nav-link"><?php echo $link_title; ?></a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </header>
</div>
