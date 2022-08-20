<?php

namespace Feeds\Pages;

use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;

/** @var \Feeds\Rota\Combined_Day[] $combined_days */

$person = Arr::get($filters, "person");

?>

<div class="row rota-services">
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
                                <?php if ($combined_service->sermon_num) echo sprintf("(%s)", $combined_service->sermon_num); ?>
                                <?php if ($combined_service->sermon_title) echo sprintf(" - &ldquo;%s&rdquo;", $combined_service->sermon_title); ?>
                            </p>
                            <p class="mb-0">
                                <?php if ($combined_service->main_reading) echo $combined_service->main_reading; ?>
                                <?php if ($combined_service->additional_reading) echo sprintf("<em>%s</em>", $combined_service->additional_reading); ?>
                            </p>

                            <!-- Rota Roles -->
                            <div class="people collapse show mt-2">
                                <?php foreach ($combined_service->roles as $role => $people) : ?>
                                    <p class="mb-0">
                                        <?php
                                        $names = join(", ", $people);
                                        $highlighted = str_replace($person, sprintf("<span class=\"bg-warning\" style=\"--bs-bg-opacity: .5;\">%s</span>", $person), $names);
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
