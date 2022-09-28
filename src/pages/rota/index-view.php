<?php

namespace Feeds\Pages\Rota;

use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;
use Feeds\Pages\Parts\Header\Header_Model;
use Feeds\Request\Request;
use Feeds\Response\View;
use Feeds\Rota\Builder;

App::check();

/** @var View $this */
/** @var Index_Model $model */

// build filter queries
$query = http_build_query($model->filters);
$query_with_api = http_build_query(array_merge($model->filters, array("api" => C::$login->api)));

// output header
$header = new Header_Model("Rota", "Use the filters to create a personalised rota.");
$this->header($header);

// ics link (for copying)
$ics_link = sprintf("https://%s/rota/ics/?%s", Request::$host, $query_with_api);

?>

<!-- Filters -->
<h2>
    Filters
    <a class="ps-3 fs-6" href="/rota/?<?php _e(http_build_query($model->ten_thirty)); ?>">Sunday 10:30</a>
    <a class="ps-3 fs-6" href="/rota/?<?php _e(http_build_query($model->wednesday)); ?>">Wednesday Morning</a>
</h2>
<form method="GET" action="/rota/">
    <div class="row mb-2">
        <div class="col-8 col-sm-6">
            <div class="input-group">
                <span class="input-group-text" for="person">Person</span>
                <select class="form-control" name="person">
                    <option value="">Choose...</option>
                    <?php foreach ($model->people as $person) : $selected = $person == Arr::get($model->filters, "person") ? "selected" : ""; ?>
                        <option value="<?php _e($person); ?>" <?php _e($selected); ?>><?php _e($person); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-4 col-sm-6 d-flex align-items-center">
            <div class="form-check">
                <?php $checked = Arr::get($model->filters, "include") == "all" ? "checked" : ""; ?>
                <input class="form-check-input" type="checkbox" value="all" name="include" id="include" <?php _e($checked); ?> />
                <label class="form-check-label" for="include">Include All</label>
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-6">
            <div class="input-group">
                <span class="input-group-text" for="start">From</span>
                <input type="date" class="form-control" name="start" id="start" placeholder="From" value="<?php _e(Arr::get($model->filters, "start")); ?>" />
            </div>
        </div>
        <div class="col-6">
            <div class="input-group">
                <span class="input-group-text" for="end">To</span>
                <input type="date" class="form-control" name="end" id="end" placeholder="To" value="<?php _e(Arr::get($model->filters, "end")); ?>" />
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-6">
            <div class="input-group">
                <span class="input-group-text" for="day">Day</span>
                <select class="form-control" name="day">
                    <option value="">Choose...</option>
                    <?php foreach (Builder::$days_of_the_week as $num => $txt) : $selected = $num == Arr::get($model->filters, "day") ? "selected" : ""; ?>
                        <option value="<?php _e($num); ?>" <?php _e($selected); ?>><?php _e($txt); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="input-group">
                <span class="input-group-text" for="time">Time</span>
                <input type="time" class="form-control" name="time" placeholder="Start" value="<?php _e(Arr::get($model->filters, "time")); ?>" />
            </div>
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-6">
            <div class="input-group">
                <span class="input-group-text" for="max">Limit</span>
                <select class="form-control" name="max">
                    <option value="">Show All</option>
                    <?php for ($i = 1; $i <= 20; $i++) : $selected = $i == Arr::get($model->filters, "max") ? "selected" : ""; ?>
                        <option value="<?php _e($i); ?>" <?php _e($selected); ?>><?php _e($i); ?> service<?php _e($i > 1 ? "s" : ""); ?></option>
                    <?php endfor; ?>
                </select>
            </div>
        </div>
        <div class="col-6">
            <div class="input-group">
                <span class="input-group-text" for="series">Series</span>
                <select class="form-control" name="series">
                    <option value="">Choose...</option>
                    <?php foreach ($model->series as $series) : $selected = $series == Arr::get($model->filters, "series") ? "selected" : ""; ?>
                        <option value="<?php _e(htmlentities($series)); ?>" <?php _e($selected); ?>><?php _e($series); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6 mb-2 d-flex align-items-center">
            <div class="input-group">
                <span class="input-group-text" for="collect">Collects</span>
                <select class="form-control" name="collect">
                    <?php $show = Arr::get($model->filters, "collect"); ?>
                    <option value="" <?php if ($show != "yes") _e("selected"); ?>>Hide</option>
                    <option value="yes" <?php if ($show == "yes") _e("selected"); ?>>Show</option>
                </select>
            </div>
        </div>
        <div class="col-12 col-lg-6 d-flex justify-content-end align-items-center">
            <?php if (Request::$session->is_admin) : ?>
                <div class="form-check d-none d-sm-block me-4">
                    <?php $checked = Request::$debug ? "checked" : ""; ?>
                    <input class="form-check-input" type="checkbox" value="true" name="debug" id="debug" <?php _e($checked); ?> />
                    <label class="form-check-label" for="debug">Debug</label>
                </div>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary me-1">Apply</button>
            <a href="/rota/" class="btn btn-danger me-3">Reset</a>
            <a href="javascript:void(0)" data-clipboard-text="<?php _e($ics_link); ?>" class="copy btn btn-secondary me-1" target="_blank">ICS</a>
            <a href="/rota/json/?<?php _e($query_with_api); ?>" class="btn btn-secondary d-none d-sm-inline-block me-1" target="_blank">JSON</a>
            <a href="/rota/print/?<?php _e($query); ?>" class="btn btn-secondary d-none d-sm-inline-block" target="_blank">Print</a>
        </div>
    </div>
</form>

<!-- Rota -->
<h2>
    <?php _e($header->title); ?>
    <a class="ps-3 fs-6" data-bs-toggle="collapse" data-bs-target=".people" href="#collapsePeople" role="button" aria-expanded="true">show / hide people</a>
</h2>

<?php

$this->part("services", model: $model->days);

$this->footer();
