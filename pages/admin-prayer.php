<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Cache\Cache;
use Feeds\Prayer\Prayer_Calendar;

App::check();

// get prayer calendar
$prayer_calendar = Cache::get_prayer_calendar(fn () => new Prayer_Calendar());

// define variables
$number_of_days = 24;
$people_per_day = count($prayer_calendar->people) / $number_of_days;

// output header
$title = "Admin";
$subtitle = "User this page to assign everyone to a day on the prayer calendar.";
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
            <?php foreach ($prayer_calendar->people as $person) : $colour = $person->is_child ? "info" : "warning"; ?>
                <button type="button" class="btn btn-sm btn-<?php echo $colour; ?> m-1" data-name="<?php echo strtolower($person->get_full_name()); ?>">
                    <?php echo $person->get_full_name(); ?>
                </button>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col-6 col-xxl-9 mh-100 admin-prayer-calendar-column">
        <div class="row">
            <?php for ($i = 1; $i <= $number_of_days; $i++) : ?>
                <div class="col-12 col-xxl-6">
                    <div class="card m-2">
                        <div class="card-header">Day <?php echo $i; ?></div>
                        <div class="card-body day"></div>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</div>

<script src="/resources/js/dragula.min.js"></script>
<script type="text/javascript" defer>
    // enabled dragging between people and day containers
    var drake = dragula({
        isContainer: function(el) {
            return el.classList.contains("people") || el.classList.contains("day");
        },
        revertOnSpill: true,
    });

    // when a user types in the search box, show only people matching the search string
    // (if they have typed two or more characters)
    document.querySelector(".people-search > input").addEventListener("keyup", (e) => {
        // get search string and force it to lower case
        var search = new String(e.srcElement.value).toLowerCase();

        // get all people buttons
        document.querySelectorAll(".people > button").forEach((e) => {
            // return true if search length is under two characters,
            // or if the data-name attribute contains the search string
            var match = search.length < 2 || e.getAttribute("data-name").includes(search);

            // set the display style to match
            if (match) {
                e.style.display = "inline-block";
            } else {
                e.style.display = "none";
            }
        })
    });
</script>

<?php require_once("parts/footer.php"); ?>
