<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Helpers\Arr;
use Feeds\Rota\Builder;

App::check();

/** @var array $filters */
/** @var \Feeds\Rota\Combined_Day[] $combined_days */

// look for standard filters
$start = Arr::get($filters, "start");
$day = Builder::get_day(Arr::get($filters, "day", 0));
$person = Arr::get($filters, "person");

// output header
$title = "Rota";
require_once("parts/header-print.php"); ?>

<h2>
    Christ Church Rota
    <?php if ($start) echo sprintf(" - %s", $start); ?>
    <?php if ($day) echo $day; ?>
    <?php if ($person) echo sprintf(" - %s", $person); ?>
    <a class="ps-3 fs-6 d-print-none" data-bs-toggle="collapse" data-bs-target=".people" href="#collapsePeople" role="button" aria-expanded="true">show / hide people</a>
</h2>
<?php require_once("parts/rota-services.php"); ?>

<?php require_once("parts/footer-print.php"); ?>
