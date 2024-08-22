<?php

namespace Obadiah\Pages\Refresh;

use Obadiah\App;
use Obadiah\Cache\Cache;
use Obadiah\Pages\Parts\Header\Header_Model;
use Obadiah\Pages\Prayer\Print_Model;
use Obadiah\Response\View;

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

// output header
$this->header(new Header_Model("Refresh Calendar"), variant: "print");

// output two pages
for ($i=1; $i<=2; $i++) {

    $this->part("heading", model: $model->month);
?>

<div class="row prayer-calendar-days">

<?php for ($j=1; $j<=3; $j++) : ?>

    <div class="col-4">
        <?php
        for ($k=1; $k<=7; $k++) {
            $this->part("day", model: new Day_Model($current, $bible_plan, $lectionary));
            $current = $current->modify("+1 day");
        }
        ?>
    </div>

<?php endfor; ?>

</div>

<?php
    $this->part(sprintf("page-%s-footer", $i));

}

$this->footer("blank");
