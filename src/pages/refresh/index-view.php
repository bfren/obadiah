<?php

namespace Feeds\Pages\Refresh;

use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Helpers\Image;
use Feeds\Pages\Parts\Header\Header_Model;
use Feeds\Prayer\Person;
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

<div class="row">

    <div class="col-12 col-sm-6">
        <h3>People</h3>
        <?php if ($model->today->people) : ?>
            <?php foreach ($model->today->people as $person) : $name = $person instanceof Person ? $person->get_full_name(C::$prayer->show_last_name) : $person; ?>
                <div class="person d-flex align-items-center">
                    <div class="image">
                        <?php if($person instanceof Person && $person->image_url):?>
                            <a href="<?php _e($person->image_url) ?>" target="_blank">
                                <img src="<?php _e($person->image_url) ?>" alt="<?php _e($name); ?>" title="<?php _e($name); ?>" />
                            </a>
                        <?php else: ?>
                            <img src="<?php _e(Image::get_src("person.svg")); ?>" alt="<?php _e($name); ?>" title="<?php _e($name); ?>" />
                        <?php endif; ?>
                    </div>
                    <div class="name ps-2"><?php _e($name); ?></div>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>There are no people on the prayer calendar today.</p>
        <?php endif; ?>
    </div>

    <div class="col-12 col-sm-6">
        <h3>Bible Readings</h3>
        <?php if ($model->today->readings) : $readings = $model->today->readings; ?>
            <p><?php $this->part("reading", model: sprintf("Psalms %s", $readings->ot_psalms)); ?></p>
            <p><?php $this->part("reading", model: $readings->ot_1); ?></p>
            <p><?php $this->part("reading", model: $readings->ot_2); ?></p>
            <p><?php $this->part("reading", model: $readings->nt_gospels); ?></p>
            <p><?php $this->part("reading", model: $readings->nt_epistles); ?></p>
        <?php else : ?>
            <p>There are no Bible readings for today.</p>
        <?php endif; ?>
    </div>

</div>

<h2><?php _e($this_month_text); ?></h2>
<p>View a printable version of this month&rsquo;s calendar <a href="/refresh/print/?<?php _e($this_month_query); ?>" target="_blank">here</a>.</p>

<?php

$this->footer();
