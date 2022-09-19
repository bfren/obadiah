<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Helpers\Image;
use Feeds\Pages\Parts\Header\Header_Model;
use Feeds\Pages\Prayer\Print_Model;
use Feeds\Response\View;

App::check();

/** @var View $this */
/** @var Print_Model $model */

// get the first day of this month
$current = $model->month->get_first_day_of_month();

// output header
$this->header(new Header_Model("Prayer"), variant: "print");

?>

<?php if ($model->month->people) : ?>

    <h2 class="prayer-calendar-title row">
        <div class="col-4"><a href="/prayer"><?php Image::echo_logo("logo me-4"); ?></a></div>
        <div class="col-4 text-center">Prayer Calendar</div>
        <div class="col-4 text-end"><?php _e($model->month->get_display_month()); ?></div>
    </h2>

    <div class="row prayer-calendar-days">

        <div class="col-12 verse">
            Devote yourselves to prayer, being watchful and thankful.
            <span class="ref">Colossians 4.2 (NIV)</span>
        </div>

        <!-- Left-hand column -->
        <div class="col-4">
            <?php
            while ($current->format("j") <= 10) {
                $this->part("day", model: $current);
                $current = $current->modify("+1 day");
            }
            ?>
            <div class="guidance rounded">
                <p>
                    If you don&rsquo;t know someone you can still pray...<br />
                    And then, why not try to find out who they are?
                </p>
            </div>
        </div>

        <!-- Middle column -->
        <div class="col-4">
            <?php
            while ($current->format("j") <= 20) {
                $this->part("day", model: $current);
                $current = $current->modify("+1 day");
            }
            ?>
            <div class="guidance rounded">
                <p class="prayer">&lsquo;Father, may <em>N</em> know your presence today, and as you bless us may we be a blessing to others.&rsquo;</p>
            </div>
        </div>

        <!-- Right-hand column -->
        <div class="col-4">
            <?php
            $current_month = $current->format("n");
            while ($current->format("n") == $current_month) {
                $this->part("day", model: $current);
                $current = $current->modify("+1 day");
            }
            ?>
        </div>

    </div>

<?php else : ?>
    <p class="mt-2">There is no-one on this prayer calendar yet.</p>
<?php endif;

$this->footer("prayer");
