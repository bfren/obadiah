<?php

namespace Feeds\Pages\Refresh;

use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Pages\Parts\Header\Header_Model;
use Feeds\Refresh\Day;
use Feeds\Response\View;

App::check();

/** @var View $this */
/** @var Index_Model $model */

// output header
$this->header(new Header_Model("Refresh", "View today’s entry on the Refresh calendar."));

?>

<h2><?php _e($model->today->date->format(C::$formats->display_date)); ?></h2>

<?php if ($model->today->readings) : $readings = $model->today->readings; ?>
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

<?php if ($model->today->people) : ?>
    <h3>People</h3>
    <p><?php _h(join("<br/>", $model->today->people)); ?></p>
<?php else : ?>
    <p>There are no people on the prayer calendar today.</p>
<?php endif; ?>

<?php

$this->footer();
