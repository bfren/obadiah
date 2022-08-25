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
use Feeds\Prayer\Person;
use Feeds\Prayer\Prayer_Calendar;
use Feeds\Request\Request;
use Throwable;

App::check();
Request::is_admin() || Request::redirect("/logout.php");

// get prayer calendar
$prayer_calendar = Cache::get_prayer_calendar(fn () => new Prayer_Calendar());

// define variables
$people_per_day = round(count($prayer_calendar->people) / Month::MAX_DAYS, 1);

// get template month (will pre-populate the days with this month's data)
$from_id = Arr::get($_GET, "from");
if ($from_id) {
    $from = Month::load($from_id);
} else {
    $from = Month::get_most_recent();
}

// the day for loop begins with 1 not 0 so we need an empty array item to push everything up one place
$from_days = array_merge(array(""), array_values($from->days));
$from_people = $from->people;

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

$for_date = $for->modify("first day of");

/**
 * Output HTML for a person button.
 *
 * @param Person $person                Person object.
 * @return void
 */
function output_person_button(Person $person)
{
    // get details
    $colour = $person->is_child ? "info" : "warning";
    $name = strtolower($person->get_full_name());
    $hash = Hash::person($person);

    // if this person is not in the prayer calendar, highlight them in red
    $prayer_calendar = Cache::get_prayer_calendar(fn () => new Prayer_Calendar());
    if (!in_array($hash, array_keys($prayer_calendar->people))) {
        $colour = "danger";
    }

    // output button HTML
    $html = "<button type=\"button\" class=\"btn btn-sm btn-%s m-1\" data-name=\"%s\" data-hash=\"%s\">%s</button>";
    echo sprintf($html, $colour, $name, $hash, $person->get_full_name());
}

// output header
$title = "Prayer Calendar";
$subtitle = "Use this page to assign everyone to a day on the prayer calendar.";
require_once("parts/header.php");

// output alert
require_once("parts/alert.php"); ?>

<div class="row d-flex flex-grow-1 h-100">
    <div class="col-6 col-lg-4 col-xxl-2 mh-100 admin-prayer-calendar-column">
        <div class="people-search mt-2 mb-2">
            <input type="text" class="form-control" name="search" placeholder="Search..." />
        </div>
        <p class="mt-1">
            <span class="text-warning">Adults</span> and <span class="text-info">children</span> are in different colours.
            There need to be an average of <?php echo $people_per_day; ?> people assigned to each day.
        </p>
        <div class="people">
            <?php foreach ($prayer_calendar->people as $person) in_array(Hash::person($person), $from_people) || output_person_button($person); ?>
        </div>
    </div>
    <div class="col-6 col-lg-8 col-xxl-10 mh-100 admin-prayer-calendar-column">
        <div class="row">
            <div class="col-12">
                <h5 class="mt-2">
                    <?php echo $for->format(C::$formats->display_month); ?>
                    <span class="ps-3 fs-6" id="save"></span>
                </h5>
            </div>
            <?php for ($i = 1; $i <= Month::MAX_DAYS; $i++) : ?>
                <?php
                // if this is a Sunday, move on one day
                if ($for_date->format("N") == 7) $for_date = $for_date->modify("+1 day");

                // get the hashes of people already added to this day
                $date = $for_date->format(C::$formats->sortable_date);
                if (isset($from_days[$i])) {
                    $people_hashes = $from_days[$i];
                } else {
                    $people_hashes = array();
                }

                // get the names of those people
                $people = $prayer_calendar->get_people($people_hashes);
                ?>
                <div class="col-12 col-lg-6 col-xxl-4">
                    <div class="card mt-2 mb-2" id="day-<?php echo $i; ?>" data-date="<?php echo $date; ?>">
                        <div class="card-header">Day <?php echo $i; ?> (<?php echo $for_date->format(C::$formats->display_day); ?>)</div>
                        <div class="card-body day">
                            <?php foreach ($people as $person) output_person_button($person); ?>
                        </div>
                    </div>
                </div>
            <?php $for_date = $for_date->modify("+1 day");
            endfor; ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    var prayer_calendar_month_max_days = <?php echo Month::MAX_DAYS; ?>;
    var prayer_calendar_month_id = "<?php echo $for->format(C::$formats->prayer_month_id); ?>";
    var prayer_calendar_save_url = "/ajax.php";
</script>

<?php require_once("parts/footer.php"); ?>
