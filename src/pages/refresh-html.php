<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Config\Config as C;

App::check();

/** @var \Feeds\Refresh\Refresh $refresh */

// get today
$today = $refresh->today;

// output header
$title = "Refresh";
$subtitle = "View today’s entry on the Refresh calendar.";
require_once "parts/header.php"; ?>

<h2><?php _e($today->date->format(C::$formats->display_date)); ?></h2>

<?php if ($today->readings) : $readings = $today->readings; ?>
    <h3>Bible Readings</h3>
    <p>
        <?php _e("Psalms %s", $readings->ot_psalms); ?><br />
        <?php _e($readings->ot_1); ?><br />
        <?php _e($readings->ot_2); ?><br />
        <?php _e($readings->nt_gospels); ?><br />
        <?php _e($readings->nt_epistles); ?><br />
    </p>
<?php else : ?>
    <p>There are no Bible readings for today.</p>
<?php endif; ?>

<?php if ($today->people) : ?>
    <h3>People</h3>
    <p><?php _h(join("<br/>", $today->people)); ?></p>
<?php else : ?>
    <p>There are no people on the prayer calendar today.</p>
<?php endif; ?>

<?php require_once "parts/footer.php"; ?>