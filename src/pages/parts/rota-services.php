<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;
use Feeds\Request\Request;

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

<?php if(count($combined_days) == 0): ?>
    <p>No services were found matching the current filters.</p>
<?php endif; ?>

<div class="row rota-services d-print-block">
    <?php foreach ($combined_days as $combined_day) : ?>

        <div class="col-12 col-md-6 col-xl-4 col-xxl-3">
            <div class="card mb-3">
                <div class="card-body">

                    <!-- Date -->
                    <h5 class="card-title"><?php _e($combined_day->date->format(C::$formats->display_date)); ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php _e($combined_day->name); ?></h6>

                    <!-- Services -->
                    <?php foreach ($combined_day->services as $combined_service) : ?>

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
                                    <?php if ($combined_service->sermon_title) _e(" - “%s”", $combined_service->sermon_title); ?>
                                </p>
                                <p class="bible mb-0">
                                    <?php if (count($combined_service->psalms)) _h("%s, ", get_bible_reading(sprintf("Psalm%s %s", count($combined_service->psalms) > 1 ? "s" : "", join("; ", $combined_service->psalms)))); ?>
                                    <?php if ($combined_service->main_reading) _h(get_bible_reading($combined_service->main_reading)); ?>
                                    <?php if ($combined_service->additional_reading) _h("&amp; %s", get_bible_reading($combined_service->additional_reading)); ?>
                                </p>
                            </div>

                            <!-- Rota Roles -->
                            <div class="people collapse show mt-2">
                                <?php foreach ($combined_service->roles as $role => $service_role) : ?>
                                    <p class="mb-0">
                                        <?php
                                        $names = join(", ", $service_role->people);
                                        $highlighted = str_replace($person, sprintf("<span class=\"bg-warning\" style=\"--bs-bg-opacity: .5;\">%s</span>", $person), $names);
                                        ?>
                                        <span class="role-name"><?php _e($role); ?></span>:
                                        <span class="role-people text-muted"><?php _h($highlighted); ?></span>
                                    </p>
                                <?php endforeach; ?>
                            </div>

                            <?php if (Request::$get->bool("collect")) : ?>
                                <!-- Collect -->
                                <div class="collect">
                                    <p class="mt-2 mb-0"><?php _h(str_replace("\n", "<br/>", $combined_day->collect)); ?></p>
                                </div>
                            <?php endif; ?>

                        </div>

                    <?php endforeach; ?>

                </div>
            </div>
        </div>

    <?php endforeach; ?>
</div>