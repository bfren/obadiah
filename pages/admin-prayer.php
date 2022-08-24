<?php

namespace Feeds\Pages;

use DateTimeImmutable;
use Feeds\Admin\Result;
use Feeds\App;
use Feeds\Cache\Cache;
use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;
use Feeds\Helpers\Hash;
use Feeds\Prayer\Month;
use Feeds\Prayer\Prayer_Calendar;
use Throwable;

App::check();

// get prayer calendar
$prayer_calendar = Cache::get_prayer_calendar(fn () => new Prayer_Calendar());

// define variables
$people_per_day = round(count($prayer_calendar->people) / Month::MAX_DAYS, 1);

// get template month (will pre-populate the days with this month's data)
$from_id = Arr::get($_GET, "from");
if ($from_id) {
    $from = Month::load($from_id);
}

// get the month this calendar is for
$for_id = Arr::get($_GET, "for");
if (!$for_id) {
    $result = Result::failure("You must set the month this calendar is for.");
}

try {
    $for = new DateTimeImmutable(sprintf("%s-01", $for_id));
} catch (Throwable $th) {
    die("Unable to determine the month this calendar is for.");
}

$current = $for->modify("first day of");

// output header
$title = "Admin";
$subtitle = "Use this page to assign everyone to a day on the prayer calendar.";
require_once("parts/header.php");

// output alert
require_once("parts/alert.php"); ?>

<div class="row d-flex flex-grow-1 h-100">
    <div class="col-6 col-xxl-3 mh-100 admin-prayer-calendar-column">
        <div class="people-search mt-2 mb-2">
            <input type="text" class="form-control" name="search" placeholder="Search..." />
        </div>
        <p class="mt-1">
            <span class="text-warning">Adults</span> and <span class="text-info">children</span> are in different colours.
            There need to be an average of <?php echo $people_per_day; ?> people assigned to each day.
        </p>
        <div class="people">
            <?php foreach ($prayer_calendar->people as $person) : ?>
                <?php
                $colour = $person->is_child ? "info" : "warning";
                $name = strtolower($person->get_full_name());
                $hash = Hash::person($person);
                ?>
                <button type="button" class="btn btn-sm btn-<?php echo $colour; ?> m-1" data-name="<?php echo $name; ?>" data-hash="<?php echo $hash; ?>">
                    <?php echo $person->get_full_name(); ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col-6 col-xxl-9 mh-100 admin-prayer-calendar-column">
        <div class="row">
            <div class="col-12">
                <h5 class="mt-2">
                    <?php echo $for->format(C::$formats->display_month); ?>
                    <span class="ps-3 fs-6" id="save"></span>
                </h5>
            </div>
            <?php for ($i = 1; $i <= Month::MAX_DAYS; $i++) : if ($current->format("N") == 7) $current = $current->modify("+1 day"); ?>
                <div class="col-12 col-xxl-6">
                    <div class="card mt-2 mb-2" id="day-<?php echo $i; ?>" data-date="<?php echo $current->format(C::$formats->sortable_date); ?>">
                        <div class="card-header">Day <?php echo $i; ?> (<?php echo $current->format(C::$formats->display_day); ?>)</div>
                        <div class="card-body day"></div>
                    </div>
                </div>
            <?php $current = $current->modify("+1 day");
            endfor; ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    var month_max_days = <?php echo Month::MAX_DAYS; ?>;
    var month_id = "<?php echo $for->format(C::$formats->prayer_month_id); ?>";
    var prayer_calendar_save_url = "/ajax";
</script>

<?php require_once("parts/footer.php"); ?>
