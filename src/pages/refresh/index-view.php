<?php

namespace Feeds\Pages\Refresh;

use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Pages\Parts\Header\Header_Model;
use Feeds\Response\View;

App::check();

/** @var View $this */
/** @var Index_Model $model */

// get values for link to this month's calendar
$this_month_text = $model->today->date->format(C::$formats->display_month);
$this_month_query = http_build_query(
    array("month" => $model->today->date->format(C::$formats->prayer_month_id))
);

// output header
$this->header(new Header_Model("Refresh"));

?>

<h2><?php _e($model->today->date->format(C::$formats->display_date)); ?></h2>
<p>Today&rsquo;s entry on the Refresh calendar.</p>

<?php if ($model->today->people) : ?>
    <h3>People</h3>
    <p><?php _h(join("<br/>", $model->today->people)); ?></p>
<?php else : ?>
    <p>There are no people on the prayer calendar today.</p>
<?php endif; ?>

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

<h2><?php _e($this_month_text); ?></h2>
<p>View a printable version of this month&rsquo;s calendar <a href="/refresh/print/?<?php _e($this_month_query); ?>" target="_blank">here</a>.</p>

<?php

$this->footer();
