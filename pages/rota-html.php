<?php

namespace Feeds\Pages;

use Feeds\Config\Config as C;
use Feeds\Rota\Builder;

defined("IDX") || die("Nice try.");

/** @var string $title */
/** @var \Feeds\Lectionary\Lectionary $lectionary */
/** @var \Feeds\Rota\Service[] $services */

// build rota
$builder = new Builder();
$combined_days = $builder->build_combined_rota($lectionary, $services);

// output header
$title = "Rota";
require_once("parts/header.php"); ?>

<h1><?php echo $title; ?></h1>

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
                        <p class="fw-bold mb-1">
                            <?php echo $combined_service->time; ?> <?php echo $combined_service->description ?>
                        </p>
                        <div class="small">

                            <!-- Teaching Details -->
                            <p class="mb-0">
                                <?php if ($combined_service->series_title) echo $combined_service->series_title; ?>
                                <?php if ($combined_service->sermon_num) echo "(" . $combined_service->sermon_num . ")"; ?>
                                <?php if ($combined_service->sermon_title) echo " - &ldquo;" . $combined_service->sermon_title . "&rdquo;"; ?>
                            </p>
                            <p class="mb-2">
                                <?php if ($combined_service->main_reading) echo $combined_service->main_reading; ?>
                                <?php if ($combined_service->additional_reading) echo "<em>" . $combined_service->additional_reading . "</em>"; ?>
                            </p>

                            <!-- Rota Roles -->
                            <?php foreach ($combined_service->roles as $role => $people) : ?>
                                <p class="mb-0">
                                    <?php echo $role; ?>: <?php echo join(", ", $people); ?>
                                </p>
                            <?php endforeach; ?>

                        </div>

                    <?php endforeach; ?>

                </div>
            </div>
        </div>

    <?php endforeach; ?>
</div>

<?php require_once("parts/footer.php"); ?>
