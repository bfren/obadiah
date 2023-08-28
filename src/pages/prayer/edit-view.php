<?php

namespace Feeds\Pages\Prayer;

use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Helpers\Hash;
use Feeds\Pages\Parts\Header\Header_Model;
use Feeds\Prayer\Month;
use Feeds\Prayer\Prayer_Calendar;
use Feeds\Response\View;

App::check();

/** @var View $this */
/** @var Edit_Model $model */

// store current day
$current = $model->for;

// output header
$this->header(new Header_Model("Prayer Calendar", subtitle: "Use this page to assign everyone to a day on the prayer calendar.", overflow_scroll: true));

// output alert
$this->alert($model->result);

?>

<div class="row d-flex flex-grow-1 h-100">
    <div class="col-6 col-lg-4 col-xxl-2 mh-100 admin-prayer-calendar-column">
        <div class="people-search mt-2 mb-2">
            <input type="text" class="form-control" name="search" placeholder="Search..." />
        </div>
        <p class="mt-1">
            <span class="text-warning">Adults</span> and <span class="text-info">children</span> are in different colours.
            There need to be an average of <?php _e($model->per_day); ?> people assigned to each day.
        </p>
        <div class="people">
            <?php foreach (Prayer_Calendar::get_people() as $person) in_array(Hash::person($person), $model->people) || $this->part("person", model: $person); ?>
        </div>
    </div>
    <div class="col-6 col-lg-8 col-xxl-10 mh-100 admin-prayer-calendar-column">
        <div class="row">
            <div class="col-12">
                <h5 class="mt-2">
                    <?php _e($model->for->format(C::$formats->display_month)); ?>
                    <span class="ps-3 fs-6" id="save"></span>
                </h5>
            </div>
            <?php for ($i = 1; $i <= Month::MAX_DAYS; $i++) : ?>
                <?php
                // if this is a Sunday, move on one day
                if ($current->format("N") == 7) $current = $current->modify("+1 day");

                // get the hashes of people already added to this day
                $date = $current->format(C::$formats->sortable_date);
                if (isset($model->days[$i])) {
                    $people_hashes = $model->days[$i];
                } else {
                    $people_hashes = array();
                }

                // get the names of those people
                $people = Prayer_Calendar::get_people($people_hashes);
                ?>
                <div class="col-12 col-lg-6 col-xxl-4">
                    <div class="card mt-2 mb-2" id="day-<?php _e($i); ?>" data-date="<?php _e($date); ?>">
                        <div class="card-header">Day <?php _e($i); ?> (<?php _e($current->format(C::$formats->display_day)); ?>)</div>
                        <div class="card-body day">
                            <?php foreach ($people as $person) $this->part("person", model: $person); ?>
                        </div>
                    </div>
                </div>
            <?php $current = $current->modify("+1 day");
            endfor; ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    var prayer_calendar_month_max_days = <?php _e(Month::MAX_DAYS); ?>;
    var prayer_calendar_month_id = "<?php _e($model->for->format(C::$formats->prayer_month_id)); ?>";
    var prayer_calendar_save_url = "/ajax/month";
</script>

<?php

$this->footer();
