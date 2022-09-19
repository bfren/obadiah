<?php

namespace Feeds\Pages\Rota;

use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Request\Request;
use Feeds\Response\View;
use Feeds\Rota\Combined_Day;

App::check();

/** @var View $this */
/** @var Combined_Day[] $model */

?>

<div class="row rota-services">
    <?php foreach ($model as $day) : ?>

        <div class="col-12 col-md-6 col-xl-4 col-xxl-3">
            <div class="card mb-3">
                <div class="card-body">

                    <!-- Date -->
                    <h5 class="card-title"><?php _e($day->date->format(C::$formats->display_date)); ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php _e($day->name); ?></h6>

                    <!-- Services -->
                    <?php foreach ($day->services as $combined_service) : ?>

                        <hr class="mt-1 mb-1" />
                        <div class="details">
                            <div class="col">
                                <p class="fw-bold mb-0">
                                    <?php _e($combined_service->time); ?> <?php _e($combined_service->name); ?>
                                </p>
                                <!-- Teaching Details -->
                                <div class="teaching small">
                                    <div class="series">
                                        <p>
                                            <?php if ($combined_service->series_title) _e($combined_service->series_title); ?>
                                            <?php if ($combined_service->sermon_num) _e("(%d)", $combined_service->sermon_num); ?>
                                        </p>
                                        <?php if ($combined_service->sermon_title) _h("<p>&ldquo;%s&rdquo;</p>", $combined_service->sermon_title); ?>
                                    </div>
                                    <div class="bible">
                                        <?php if (count($combined_service->psalms)) _h("<p>Psalm%s %s</p>", count($combined_service->psalms) > 1 ? "s" : "", join("; ", $combined_service->psalms)); ?>
                                        <?php if ($combined_service->main_reading) _h("<p>%s</p>", $combined_service->main_reading); ?>
                                        <?php if ($combined_service->additional_reading) _h("<p>%s</p>", $combined_service->additional_reading); ?>
                                    </div>
                                </div>
                                <?php if (Request::$get->bool("collect")) : ?>
                                    <!-- Collect -->
                                    <div class="collect small">
                                        <p class="mt-2 mb-0"><?php _h(str_replace("\n", "<br/>", $day->collect)); ?></p>
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
                                            $person = Request::$get->string("person");
                                            $highlighted = str_replace($person, sprintf("<span class=\"bg-warning\" style=\"--bs-bg-opacity: .5;\">%s</span>", $person), $names);
                                            ?>
                                            <span class="role-name"><?php _e($role); ?></span>:
                                            <span class="role-people text-muted"><?php _h($highlighted); ?></span>
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
