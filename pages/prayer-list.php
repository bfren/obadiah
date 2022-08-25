<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Prayer\Prayer_Calendar;
use Feeds\Request\Request;

App::check();

// get months with prayer calendar data
$months = Prayer_Calendar::get_months();

// output header
$title = "Prayer";
require_once("parts/header.php");

// output alert
require_once("parts/alert.php"); ?>

<h2>Months</h2>

<?php if ($months) : ?>
    <ul>
        <?php foreach ($months as $month) : ?>
            <?php
            $view_query = array("month" => $month);
            $edit_query = array("from" => $month, "for" => $month);
            $delete_query = array("delete_month" => sprintf("%s.month", $month));
            ?>
            <li>
                <a href="/prayer/view/?<?php echo http_build_query($view_query); ?>"><?php echo $month; ?></a>
                <?php if (Request::is_admin()) : ?>
                    <a class="badge rounded-pill text-bg-warning fw-bold" href="/prayer/edit/?<?php echo http_build_query($edit_query); ?>">edit</a>
                    <a class="badge rounded-pill text-bg-danger fw-bold check-first" href="/prayer/?<?php echo http_build_query($delete_query); ?>">delete</a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <p>Nothing to see here - please try again later!</p>
<?php endif; ?>

<?php if (Request::is_admin()) : ?>
    <h2>Create</h2>
    <p>Please enter the month to create a calendar for, and click 'Create'.</p>
    <form class="row row-cols-lg-auto g-3 mb-3 align-items-center needs-validation" method="GET" action="/prayer/edit/" novalidate>
        <div class="col-12 position-relative">
            <label class="visually-hidden" for="for">Month</label>
            <input type="text" class="form-control" id="for" name="for" placeholder="Month e.g. '2022-12'" required />
            <div class="invalid-tooltip">Please enter the month to create a prayer calendar for.</div>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Create</button>
        </div>
    </form>
<?php endif; ?>

<?php require_once("parts/footer.php"); ?>
