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

<?php if(count($model) == 0): ?>
    <p>No services were found matching the current filters.</p>
<?php endif; ?>

<div class="row rota-services d-print-block">
    <?php foreach ($model as $day) : ?>

        <div class="col-12 col-md-6 col-xl-4 col-xxl-3">
            <div class="card mb-3">
                <div class="card-body">

                    <!-- Date -->
                    <h5 class="card-title"><?php _e($day->date->format(C::$formats->display_date)); ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php _e($day->name); ?></h6>

                    <!-- Services -->
                    <?php foreach ($day->services as $combined_service) : ?>

                        <hr class="mt-3 mb-3" />
                        <p class="fw-bold mb-0">
                            <?php _e($combined_service->time); ?> <?php _e($combined_service->name); ?>
                        </p>

                        <div class="details small">

                            <!-- Teaching Details -->
                            <div class="teaching">
                                <p class="series mb-0">
                                    <?php if ($combined_service->series_title) _e($combined_service->series_title); ?>
                                    <?php if ($combined_service->sermon_num) _e("(%d)", $combined_service->sermon_num); ?>
                                    <?php if ($combined_service->sermon_title) _e("‘%s’", $combined_service->sermon_title); ?>
                                </p>
                                <p class="bible mb-0">
                                    <?php
                                    if (count($combined_service->psalms)) {
                                        $this->part("reading", model: sprintf("Psalms %s", join("; ", $combined_service->psalms)));
                                        _e(", ");
                                    }

                                    if ($combined_service->main_reading) {
                                        $this->part("reading", model: $combined_service->main_reading);
                                    }

                                    if ($combined_service->additional_reading) {
                                        _e(" & ");
                                        $this->part("reading", model: $combined_service->additional_reading);
                                    }
                                    ?>
                                </p>
                            </div>

                            <!-- Rota Roles -->
                            <div class="people collapse show mt-2">
                                <?php foreach ($combined_service->ministries as $ministry => $service_ministry) : ?>
                                    <p class="mb-0">
                                        <?php
                                        $names = join(", ", $service_ministry->people);
                                        $person = Request::$get->string("person");
                                        $highlighted = str_replace($person, sprintf("<span class=\"bg-warning\" style=\"--bs-bg-opacity: .5;\">%s</span>", $person), $names);
                                        ?>
                                        <span class="ministry-name"><?php _e($ministry); ?></span>:
                                        <span class="ministry-people text-muted"><?php _h($highlighted); ?></span>
                                    </p>
                                <?php endforeach; ?>
                            </div>

                            <?php if (Request::$get->bool("collect")) : ?>
                                <!-- Collect -->
                                <div class="collect">
                                    <p class="mt-2 mb-0"><?php _h(str_replace("\n", "<br/>", $day->collect)); ?></p>
                                </div>
                            <?php endif; ?>

                        </div>

                    <?php endforeach; ?>

                </div>
            </div>
        </div>

    <?php endforeach; ?>
</div>