<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;

App::check();

/** @var array $filters */
/** @var \Feeds\Rota\Combined_Day[] $combined_days */

$person = Arr::get($filters, "person");

/**
 * Return link to Bible Gateway to display text of a Bible reading.
 *
 * @param string $passage               Bible passage to link to.
 * @return string                       Anchor tag with link to Bible Gateway and passage as text.
 */
function get_bible_reading(string $passage): string
{
    $param = array(
        "search" => $passage,
        "version" => "NIVUK"
    );
    $url = sprintf("https://www.biblegateway.com/passage/?%s", http_build_query($param));
    return sprintf("<a href=\"%s\" target=\"_blank\">%s</a>", $url, $passage);
}

?>

<div class="row rota-services">
    <?php foreach ($combined_days as $date => $combined_day) : ?>

        <div class="col-12 col-md-6 col-xl-4 col-xxl-3">
            <div class="card mb-3">
                <div class="card-body">

                    <!-- Date -->
                    <h5 class="card-title"><?php echo $combined_day->date->format(C::$formats->display_date); ?></h5>
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
                                <?php if ($combined_service->sermon_num) echo sprintf("(%d)", $combined_service->sermon_num); ?>
                                <?php if ($combined_service->sermon_title) echo sprintf(" - &ldquo;%s&rdquo;", $combined_service->sermon_title); ?>
                            </p>
                            <p class="mb-0">
                                <?php if ($combined_service->main_reading) echo get_bible_reading($combined_service->main_reading); ?>
                                <?php if ($combined_service->additional_reading) echo sprintf("&amp; %s", get_bible_reading($combined_service->additional_reading)); ?>
                            </p>

                            <!-- Rota Roles -->
                            <div class="people collapse show mt-2">
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

                    <?php endforeach; ?>

                </div>
            </div>
        </div>

    <?php endforeach; ?>
</div>
