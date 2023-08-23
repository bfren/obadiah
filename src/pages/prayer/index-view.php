<?php

namespace Feeds\Pages\Prayer;

use Feeds\App;
use Feeds\Pages\Parts\Header\Header_Model;
use Feeds\Request\Request;
use Feeds\Response\View;

App::check();

/** @var View $this */
/** @var Index_Model $model */

// output header
$this->header(new Header_Model("Prayer Calendar"));

// output alert
$this->alert($model->result);

?>

<h2>Months</h2>

<?php if ($model->months) : ?>
    <ul>
        <?php foreach ($model->months as $month) : ?>
            <?php
            $view_query = array("month" => $month);
            $edit_query = array("from" => $month, "for" => $month);
            $delete_query = array("file" => sprintf("%s.month", $month));
            ?>
            <li>
            <a href="/refresh/print/?<?php _e(http_build_query($view_query)); ?>" target="_blank"><?php _e($month); ?></a>
                <?php if (Request::$session->is_admin) : ?>
                    <a class="badge rounded-pill text-bg-warning fw-bold" href="/prayer/edit/?<?php _e(http_build_query($edit_query)); ?>">edit</a>
                    <a class="badge rounded-pill text-bg-danger fw-bold check-first" href="/prayer/delete/?<?php _e(http_build_query($delete_query)); ?>">delete</a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <p>Nothing to see here - please try again later!</p>
<?php endif; ?>

<?php if (Request::$session->is_admin) : ?>
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
<?php endif;

$this->footer();
