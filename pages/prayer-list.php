<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Prayer\Prayer_Calendar;
use Feeds\Request\Request;

App::check();

// get months with prayer calendar data
$months = Prayer_Calendar::get_months();

// get the last month for which there is data
$last_month = end($months);

// output header
$title = "Prayer";
require_once("parts/header.php");

?>

<h2>Months</h2>

<?php if ($months) : ?>
    <ul>
        <?php foreach ($months as $month) : ?>
            <li><?php echo $month; ?></li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <p>Nothing to see here - please try again later!</p>
<?php endif; ?>

<?php if (Request::is_admin()) : ?>
    <form class="row row-cols-lg-auto g-3 mb-3 align-items-center needs-validation" method="GET" action="/admin/prayer/" novalidate>
        <input type="hidden" name="from" value="<?php echo $last_month; ?>" />
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
