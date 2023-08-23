<?php

namespace Feeds\Pages\Refresh;

use Feeds\App;
use Feeds\Cache\Cache;
use Feeds\Pages\Parts\Header\Header_Model;
use Feeds\Pages\Prayer\Print_Model;
use Feeds\Response\View;

App::check();

/** @var View $this */
/** @var Print_Model $model */

// get the first day of this month
// during the loop 'current' is modified by adding one day until it reaches the last day
$current = $model->first_day;

// get Bible plan, prayer calendar and lectionary from the cache
// we do this here so they can be reused instead of reloaded for each day
$bible_plan = Cache::get_bible_plan();
$lectionary = Cache::get_lectionary();
$prayer_calendar = Cache::get_prayer_calendar();

// output header
$this->header(new Header_Model("Prayer"), variant: "print");

// output two pages
for ($i=0; $i<2; $i++) {

    $this->part("heading", model: $model->month);
?>

<div class="row prayer-calendar-days">

    <!-- Left-hand column -->
    <div class="col-4">
        <?php
        for ($j=0; $j<7; $j++) {
            $this->part("day", model: new Day_Model($current, $bible_plan, $lectionary, $prayer_calendar));
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
        for ($j=0; $j<7; $j++) {
            $this->part("day", model: new Day_Model($current, $bible_plan, $lectionary, $prayer_calendar));
            $current = $current->modify("+1 day");
        }
        ?>
        <div class="guidance rounded">
            <p class="prayer">&lsquo;Father, may <em>N</em> know your love, be filled with your Spirit, and share Jesus in all they do and say.&rsquo;</p>
        </div>
    </div>

    <!-- Right-hand column -->
    <div class="col-4">
        <?php
        for ($j=0; $j<7; $j++) {
            $this->part("day", model: new Day_Model($current, $bible_plan, $lectionary, $prayer_calendar));
            $current = $current->modify("+1 day");
        }
        ?>
        <div class="guidance rounded">
            <p>
                If you don&rsquo;t wish to be included, you can remove yourself in Church Suite or by contacting the office.
            </p>
        </div>
    </div>

</div>

<?php
}

$this->footer("prayer");
