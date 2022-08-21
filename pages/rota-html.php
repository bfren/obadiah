<?php

namespace Feeds\Pages;

use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;
use Feeds\Rota\Builder;

defined("IDX") || die("Nice try.");

/** @var \Feeds\Rota\Combined_Day[] $combined_days */
/** @var \Feeds\Lectionary\Lectionary $lectionary */
/** @var array $filters */

// output header
$title = "Rota";
require_once("parts/header.php"); ?>

<!-- Filters -->
<h2 class="border-bottom">Filters</h2>
<form method="GET" action="/rota/">
    <div class="row mb-2">
        <div class="col-8 col-sm-6">
            <div class="input-group">
                <span class="input-group-text" for="person">Person</span>
                <select class="form-control" name="person">
                    <option value="">Choose...</option>
                    <?php foreach ($rota->people as $person) : $selected = $person == Arr::get($filters, "person") ? "selected" : ""; ?>
                        <option value="<?php echo $person; ?>" <?php echo $selected; ?>><?php echo $person; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-4 col-sm-6 d-flex align-items-center">
            <div class="form-check">
                <?php $checked = Arr::get($filters, "include") == "all" ? "checked" : ""; ?>
                <input class="form-check-input" type="checkbox" value="all" name="include" id="include" <?php echo $checked; ?> />
                <label class="form-check-label" for="include">Include All</label>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-6">
            <div class="input-group">
                <span class="input-group-text" for="from">From</span>
                <input type="date" class="form-control" name="from" id="from" placeholder="From" value="<?php echo Arr::get($filters, "from"); ?>" />
            </div>
        </div>
        <div class="col-6">
            <div class="input-group">
                <span class="input-group-text" for="to">To</span>
                <input type="date" class="form-control" name="to" id="to" placeholder="To" value="<?php echo Arr::get($filters, "to"); ?>" />
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-6">
            <div class="input-group">
                <span class="input-group-text" for="day">Day</span>
                <select class="form-control" name="day">
                    <option value="">Choose...</option>
                    <?php foreach (Builder::$days_of_the_week as $num => $txt) : $selected = $num == Arr::get($filters, "day") ? "selected" : ""; ?>
                        <option value="<?php echo $num; ?>" <?php echo $selected; ?>><?php echo $txt; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="input-group">
                <span class="input-group-text" for="time">Time</span>
                <input type="time" class="form-control" name="start" placeholder="Start" value="<?php echo Arr::get($filters, "start"); ?>" />
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-6">
            <div class="input-group">
                <span class="input-group-text" for="max">Limit</span>
                <select class="form-control" name="max">
                    <option value="">Show All</option>
                    <?php for ($i = 1; $i <= 20; $i++) : $selected = $i == Arr::get($filters, "max") ? "selected" : ""; ?>
                        <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?> service<?php echo $i > 1 ? "s" : ""; ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="input-group">
                <span class="input-group-text" for="series">Series</span>
                <select class="form-control" name="series">
                    <option value="">Choose...</option>
                    <?php foreach ($lectionary->series as $series) : $selected = $series == Arr::get($filters, "series") ? "selected" : ""; ?>
                        <option value="<?php echo htmlentities($series); ?>" <?php echo $selected; ?>><?php echo $series; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6"></div>
        <div class="col-6 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary me-1">Apply</button>
            <a href="/rota/" class="btn btn-danger me-3">Reset</a>
            <a href="/rota/ics/?<?php echo $_SERVER["QUERY_STRING"]; ?>&api=<?php echo C::$login->api ?>" class="btn btn-secondary me-1" target="_blank">ICS</a>
            <a href="/rota/json/?<?php echo $_SERVER["QUERY_STRING"]; ?>&api=<?php echo C::$login->api ?>" class="btn btn-secondary d-none d-sm-inline-block me-1" target="_blank">JSON</a>
            <a href="/rota/print/?<?php echo $_SERVER["QUERY_STRING"]; ?>" class="btn btn-secondary d-none d-sm-inline-block" target="_blank">Print</a>
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
