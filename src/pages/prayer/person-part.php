<?php

namespace Feeds\Pages\Prayer;

use Feeds\App;
use Feeds\Cache\Cache;
use Feeds\Helpers\Hash;
use Feeds\Prayer\Person;

App::check();

/** @var View $this */
/** @var Person $model */

// get details
$colour = $model->is_child ? "info" : "warning";
$name = strtolower($model->get_full_name());
$hash = Hash::person($model);

// if this person is not in the prayer calendar, highlight them in red
$prayer_calendar = Cache::get_prayer_calendar();
if (!in_array($hash, array_keys($prayer_calendar->people))) {
    $colour = "danger";
}

// output button HTML
$html = "<button type=\"button\" class=\"btn btn-sm btn-%s m-1\" data-name=\"%s\" data-hash=\"%s\">%s</button>";
_h($html, $colour, $name, $hash, $model->get_full_name());
