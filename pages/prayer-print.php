<?php

namespace Feeds\Pages;

use DateTimeImmutable;
use Feeds\App;
use Feeds\Cache\Cache;
use Feeds\Config\Config as C;
use Feeds\Config\Config_Cache;
use Feeds\Helpers\Arr;
use Feeds\Helpers\Image;
use Feeds\Lectionary\Lectionary;
use Feeds\Prayer\Month;
use Feeds\Prayer\Person;
use Feeds\Prayer\Prayer_Calendar;

App::check();

// get prayer calendar and lectionary
$prayer_calendar = Cache::get_prayer_calendar(fn () => new Prayer_Calendar());
$lectionary = Cache::get_lectionary(fn () => new Lectionary());

// get requested month
$month_id = Arr::get($_GET, "month");
$month = Month::load($month_id);

// get the first day of this month
$current = $month->get_first_day_of_month();

// output header
$title = "Prayer";
require_once("parts/header-print.php");

function output_day(DateTimeImmutable $date)
{
    global $month, $prayer_calendar, $lectionary;

    // if this is a Sunday, get the Bible passage from the lectionary, leader and preacher
    // otherwise, get the people on the prayer calendar for this day
    if ($date->format("N") == 7) {
        $lectionary_day = $lectionary->get_day($date);
        $services = $lectionary_day?->services;
    } elseif ($hashes = Arr::get($month->days, $date->format(C::$formats->sortable_date))) {
        $people = array_map(fn (Person $person) => $person->get_full_name(), $prayer_calendar->get_people($hashes));
    } elseif (($num = $date->format("j")) && in_array($num, array(29,30,31))) {
        $prop = sprintf("day_%s", $num);
        $people = C::$prayer->$prop;
    } else {
        $people = array();
    }
?>
    <div class="day">
        <div class="date rounded-3 d-flex flex-column">
            <div class="month rounded-top"><?php echo strtolower($date->format("M")); ?></div>
            <div class="d-flex justify-content-center flex-row flex-grow-1">
                <div class="d-flex align-items-center">
                    <span class="num"><?php echo $date->format("j"); ?></span>
                    <span class="dow"><?php echo strtolower($date->format("D")); ?></span>
                </div>
            </div>
        </div>
        <div class="content">
            <?php if (isset($people)) : ?>
                <div class="people">
                    <?php echo join(", ", $people); ?>
                </div>
            <?php elseif (isset($lectionary_day)) : ?>
                <div class="services">
                    <div class="fw-bold"><?php echo $lectionary_day->name; ?></div>
                    <?php foreach ($services as $service) : ?>
                        <div class="service d-flex">
                            <div class="time"><?php echo $service->time; ?></div>
                            <div class="teaching">
                                <div>
                                    <?php if ($service->main_reading) : ?>
                                        <?php echo $service->main_reading; ?>
                                        <?php if ($service->additional_reading) echo sprintf("&amp; %s", $service->additional_reading); ?>
                                    <?php else : ?>
                                        <?php echo $service->title; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="clear"></div>
    </div>
<?php }

?>

<?php if ($month->people) : ?>

    <h2 class="prayer-calendar-title row">
        <div class="col-4"><?php echo Image::get_logo("logo"); ?></div>
        <div class="col-4 text-center">Prayer Calendar</div>
        <div class="col-4 text-end"><?php echo $month->get_display_month(); ?></div>
    </h2>

    <div class="row prayer-calendar-days">

        <div class="col-12 verse">
            Devote yourselves to prayer, being watchful and thankful.
            <span class="ref">Colossians 4.2 (NIV)</span>
        </div>

        <!-- Left-hand column -->
        <div class="col-4">
            <?php
            while ($current->format("j") <= 10) {
                output_day($current);
                $current = $current->modify("+1 day");
            }
            ?>
            <div class="guidance rounded">
                <p>
                    If you don&rsquo;t know someone you can still pray...<br />
                    And then, why not try to find out who they are?
                </p>
            </div>
        </div>

        <!-- Middle column -->
        <div class="col-4">
            <?php
            while ($current->format("j") <= 20) {
                output_day($current);
                $current = $current->modify("+1 day");
            }
            ?>
            <div class="guidance rounded">
                <p class="fw-bold">&lsquo;Father, may <em>N</em> know your presence today, and as you bless them may they be a blessing to others.&rsquo;</p>
            </div>
        </div>

        <!-- Right-hand column -->
        <div class="col-4">
            <?php
            $current_month = $current->format("n");
            while ($current->format("n") == $current_month) {
                output_day($current);
                $current = $current->modify("+1 day");
            }
            ?>
        </div>

    </div>

<?php else : ?>
    <p class="mt-2">There is no-one on this prayer calendar yet.</p>
<?php endif; ?>

<?php require_once("parts/footer-prayer.php"); ?>
