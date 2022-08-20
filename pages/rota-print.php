<?php

namespace Feeds\Pages;

use Feeds\Helpers\Arr;
use Feeds\Rota\Builder;

defined("IDX") || die("Nice try.");

/** @var array $filters */
/** @var \Feeds\Rota\Combined_Day[] $combined_days */

// look for start time
$start = Arr::get($filters, "start");
$day = Builder::get_day(Arr::get($filters, "day"));

// output header
$title = "Rota";
require_once("parts/header-print.php"); ?>

<h3>
    Christ Church Rota
    <?php if ($start) echo sprintf(" - %s", $start); ?>
    <?php if ($day) echo $day; ?>
</h3>
<?php require_once("parts/rota-services.php"); ?>

<?php require_once("parts/footer-print.php"); ?>
