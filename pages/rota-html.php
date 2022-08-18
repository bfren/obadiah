<?php

namespace Feeds\Pages;

use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;
use Feeds\Rota\Builder;

defined("IDX") || die("Nice try.");

/** @var string $title */
/** @var \Feeds\Rota\Combined_Day[] $combined_days */
/** @var array $filters */

// get days of the week
$days_of_the_week = array(
    7 => "Sunday",
    1 => "Monday",
    2 => "Tuesday",
    3 => "Wednesday",
    4 => "Thursday",
    5 => "Friday",
    6 => "Saturday",
);

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
                <option value="">Please select a person</option>
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
                <option value="">Please select a day of the week</option>
                <?php foreach ($days_of_the_week as $num => $txt) :  $selected = $num == Arr::get($filters, "day") ? "selected" : ""; ?>
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
            <a href="/rota/ics/?<?php echo $_SERVER["QUERY_STRING"]; ?>" class="btn btn-primary" target="_blank">ICS Feed</a>
            <a href="/rota/" class="btn btn-danger ms-4">Reset</a>
        </div>
    </div>
</form>

<!-- Rota -->
<h2 class="border-bottom">
    <?php echo $title; ?>
    <a class="ps-3 fs-6" data-bs-toggle="collapse" data-bs-target=".people" href="#collapsePeople" role="button" aria-expanded="true">show / hide people</a>
</h2>
<div class="row">
    <?php foreach ($combined_days as $date => $combined_day) : ?>

        <div class="col-12 col-md-6 col-xl-4 col-xxl-3">
            <div class="card mb-3">
                <div class="card-body">

                    <!-- Date -->
                    <h5 class="card-title"><?php echo $combined_day->dt->format(C::$formats->display_date); ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $combined_day->name; ?></h6>

                    <!-- Services -->
                    <?php foreach ($combined_day->services as $combined_service) : ?>

                        <hr class="mt-3 mb-3" />
                        <p class="fw-bold mb-0">
                            <?php echo $combined_service->time; ?> <?php echo $combined_service->name ?>
                        </p>

                        <div class="small">

                            <!-- Teaching Details -->
                            <p class="mb-0">
                                <?php if ($combined_service->series_title) echo $combined_service->series_title; ?>
                                <?php if ($combined_service->sermon_num) echo "(" . $combined_service->sermon_num . ")"; ?>
                                <?php if ($combined_service->sermon_title) echo " - &ldquo;" . $combined_service->sermon_title . "&rdquo;"; ?>
                            </p>
                            <p class="mb-0">
                                <?php if ($combined_service->main_reading) echo $combined_service->main_reading; ?>
                                <?php if ($combined_service->additional_reading) echo "<em>" . $combined_service->additional_reading . "</em>"; ?>
                            </p>

                            <!-- Rota Roles -->
                            <div class="people collapse show mt-2">
                                <?php foreach ($combined_service->roles as $role => $people) : ?>
                                    <p class="mb-0">
                                        <?php
                                        $names = join(", ", $people);
                                        $highlighted = str_replace(Arr::get($filters, "person"), "<span class=\"bg-warning\" style=\"--bs-bg-opacity: .5;\">" . Arr::get($filters, "person") . "</span>", $names);
                                        ?>
                                        <?php echo $role; ?>: <span class="text-muted"><?php echo $highlighted; ?></span>
                                    </p>
                                <?php endforeach; ?>
                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>
            </div>
        </div>

    <?php endforeach; ?>
</div>

<?php require_once("parts/footer.php"); ?>
