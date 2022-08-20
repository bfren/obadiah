<?php

namespace Feeds\Pages;

use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;
use Feeds\Rota\Builder;

defined("IDX") || die("Nice try.");

/** @var string $title */
/** @var \Feeds\Rota\Combined_Day[] $combined_days */
/** @var array $filters */

// output header
$title = "Rota";
require_once("parts/header.php"); ?>

<!-- Filters -->
<h2 class="border-bottom">Filters</h2>
<form method="GET" action="/rota/">
    <div class="row mb-2">
        <label for="person" class="col-2 col-form-label">Person</label>
        <div class="col-8 col-md-9">
            <select class="form-control" name="person">
                <option value="">Select a person</option>
                <?php foreach ($rota->people as $person) : $selected = $person == Arr::get($filters, "person") ? "selected" : ""; ?>
                    <option value="<?php echo $person; ?>" <?php echo $selected; ?>><?php echo $person; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-2 col-md-1 d-flex align-items-center">
            <div class="form-check">
                <?php $checked = Arr::get($filters, "include") == "all" ? "checked" : ""; ?>
                <input class="form-check-input" type="checkbox" value="all" name="include" id="include" <?php echo $checked; ?> />
                <label class="form-check-label" for="include">All</label>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <label for="from" class="col-2 col-form-label">Dates</label>
        <div class="col-5">
            <input type="date" class="form-control" name="from" placeholder="From" value="<?php echo Arr::get($filters, "from"); ?>" />
        </div>
        <div class="col-5">
            <input type="date" class="form-control" name="to" placeholder="To" value="<?php echo Arr::get($filters, "to"); ?>" />
        </div>
    </div>
    <div class="row mb-2">
        <label for="day" class="col-2 col-form-label">Day</label>
        <div class="col-5">
            <select class="form-control" name="day">
                <option value="">Select a day of the week</option>
                <?php foreach (Builder::$days_of_the_week as $num => $txt) :  $selected = $num == Arr::get($filters, "day") ? "selected" : ""; ?>
                    <option value="<?php echo $num; ?>" <?php echo $selected; ?>><?php echo $txt; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-5">
            <input type="time" class="form-control" name="start" placeholder="Start" value="<?php echo Arr::get($filters, "start"); ?>" />
        </div>
    </div>
    <div class="row">
        <div class="col-2"></div>
        <div class="col-10">
            <button type="submit" class="btn btn-primary">Apply</button>
            <a href="/rota/ics/?<?php echo $_SERVER["QUERY_STRING"]; ?>&api=<?php echo C::$login->api ?>" class="btn btn-secondary" target="_blank">ICS</a>
            <a href="/rota/json/?<?php echo $_SERVER["QUERY_STRING"]; ?>&api=<?php echo C::$login->api ?>" class="btn btn-secondary d-none d-sm-inline" target="_blank">JSON</a>
            <a href="/rota/" class="btn btn-danger ms-3">Reset</a>
        </div>
    </div>
</form>

<!-- Rota -->
<h2 class="border-bottom mt-3">
    <?php echo $title; ?>
    <a class="ps-3 fs-6" data-bs-toggle="collapse" data-bs-target=".people" href="#collapsePeople" role="button" aria-expanded="true">show / hide people</a>
</h2>
<?php require_once("parts/rota-services.php"); ?>

<?php require_once("parts/footer.php"); ?>
