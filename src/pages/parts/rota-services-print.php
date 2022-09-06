<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;

App::check();

/** @var array $filters */
/** @var \Feeds\Rota\Combined_Day[] $combined_days */

$person = Arr::get($filters, "person");

?>

<div class="row rota-services">
    <?php foreach ($combined_days as $combined_day) : ?>

        <div class="col-12 col-md-6 col-xl-4 col-xxl-3">
            <div class="card mb-3">
                <div class="card-body">

                    <!-- Date -->
                    <h5 class="card-title"><?php echo $combined_day->date->format(C::$formats->display_date); ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $combined_day->name; ?></h6>

                    <!-- Services -->
                    <?php foreach ($combined_day->services as $combined_service) : ?>

                        <hr class="mt-1 mb-1" />
                        <div class="details">
                            <div class="col">
                                <p class="fw-bold mb-0">
                                    <?php echo $combined_service->time; ?> <?php echo $combined_service->name ?>
                                </p>
                                <!-- Teaching Details -->
                                <div class="teaching small">
                                    <div class="series">
                                        <p>
                                            <?php if ($combined_service->series_title) echo $combined_service->series_title; ?>
                                            <?php if ($combined_service->sermon_num) echo sprintf("(%d)", $combined_service->sermon_num); ?>
                                        </p>
                                        <?php if ($combined_service->sermon_title) echo sprintf("<p>%s</p>", $combined_service->sermon_title); ?>
                                    </div>
                                    <div class="bible">
                                        <?php if (count($combined_service->psalms)) echo sprintf("<p>%s</p>", sprintf("Psalm%s %s", count($combined_service->psalms) > 1 ? "s" : "", join("; ", $combined_service->psalms))); ?>
                                        <?php if ($combined_service->main_reading) echo sprintf("<p>%s</p>", $combined_service->main_reading); ?>
                                        <?php if ($combined_service->additional_reading) echo sprintf("<p>%s</p>", $combined_service->additional_reading); ?>
                                    </div>
                                </div>
                                <?php if (Arr::get($_GET, "collect") == "yes") : ?>
                                    <!-- Collect -->
                                    <div class="collect small">
                                        <p class="mt-2 mb-0"><?php echo str_replace("\n", "<br/>", $combined_day->collect); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col">
                                <!-- Rota Roles -->
                                <div class="people small">
                                    <?php foreach ($combined_service->roles as $role => $service_role) : ?>
                                        <p class="mb-0">
                                            <?php
                                            $names = join(", ", $service_role->people);
                                            $highlighted = str_replace($person, sprintf("<span class=\"bg-warning\" style=\"--bs-bg-opacity: .5;\">%s</span>", $person), $names);
                                            ?>
                                            <?php echo $role; ?>: <span class="text-muted"><?php echo $highlighted; ?></span>
                                        </p>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>

                </div>
            </div>
        </div>

    <?php endforeach; ?>
</div>