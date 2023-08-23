<?php

namespace Feeds\Pages\Refresh;

use DateTimeImmutable;
use Feeds\App;
use Feeds\Cache\Cache;
use Feeds\Response\View;

App::check();

/** @var View $this */
/** @var DateTimeImmutable $model */

// get prayer calendar and lectionary
$bible_plan = Cache::get_bible_plan();
$lectionary = Cache::get_lectionary();
$prayer_calendar = Cache::get_prayer_calendar();

// if this is a Sunday, get the Bible passage from the lectionary, leader and preacher
// otherwise, get the people on the prayer calendar for this day
if ($model->format("N") == 7) {
    $lectionary_day = $lectionary->get_day($model);
    $services = $lectionary_day?->services;
} else {
    $people = $prayer_calendar->get_day($model);
    $readings = $bible_plan->get_day($model);
    $services = array();
}

?>

<div class="day">
    <div class="date rounded-3 d-flex flex-column">
        <div class="month rounded-top"><?php _e(strtolower($model->format("M"))); ?></div>
        <div class="d-flex justify-content-center flex-row flex-grow-1">
            <div class="d-flex align-items-start mt-1">
                <span class="dow"><?php _e(substr(strtolower($model->format("D")), 0, 2)); ?></span>
                <span class="num"><?php _e($model->format("j")); ?></span>
            </div>
        </div>
    </div>
    <div class="content">
        <?php if (isset($lectionary_day)) : ?>
            <div class="services">
                <div class="fw-bold"><?php _e($lectionary_day->name); ?></div>
                <?php foreach ($services as $service) : ?>
                    <div class="service d-flex">
                        <div class="time"><?php _e($service->time); ?></div>
                        <div class="teaching">
                            <?php if ($service->main_reading) : ?>
                                <?php _e($service->main_reading); ?>
                                <?php if ($service->additional_reading) _e("& %s", $service->additional_reading); ?>
                                <br/>
                            <?php endif; ?>
                            <?php _e($service->title); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <?php if (isset($people)) : ?>
                <div class="people">
                    <?php _h(join(", ", array_map(function ($name) { return str_replace(" ", "&nbsp;", $name); }, $people))); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($readings)) : ?>
                <div class="readings text-body-secondary">
                    <?php
                        $this->part("reading", model: sprintf("Psalms %s", $readings->ot_psalms));
                        $this->part("reading", model: $readings->ot_1);
                        $this->part("reading", model: $readings->ot_2);
                        $this->part("reading", model: $readings->nt_gospels);
                        $this->part("reading", model: $readings->nt_epistles);
                    ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <div class="clear"></div>
</div>
