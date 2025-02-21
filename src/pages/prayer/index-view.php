<?php

namespace Obadiah\Pages\Prayer;

use Obadiah\App;
use Obadiah\Config\Config as C;
use Obadiah\Pages\Parts\Header\Header_Model;
use Obadiah\Response\View;

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
    <?php if ($model->all) : ?>
        <p>Showing all months.  Click <a href="?all=false">here</a> to see the most recent <?php _e("%s", C::$prayer->show_recent_months); ?>.</p>
    <?php else: ?>
        <p>Showing the <?php _e("%s", C::$prayer->show_recent_months); ?> most recent months.  Click <a href="?all=true">here</a> to see them all.</p>
    <?php endif; ?>
    <ul>
        <?php foreach ($model->months as $month) : ?>
            <?php
            $view_query = array("month" => $month);
            $edit_query = array("from" => $month, "for" => $month);
            $delete_query = array("file" => sprintf("%s.month", $month));
            ?>
            <li>
                <a href="/refresh/print/?<?php _e(http_build_query($view_query)); ?>" target="_blank"><?php _e($month); ?></a>
                <a class="badge rounded-pill text-bg-warning fw-bold" href="/prayer/edit/?<?php _e(http_build_query($edit_query)); ?>">edit</a>
                <a class="badge rounded-pill text-bg-danger fw-bold check-first" href="/prayer/delete/?<?php _e(http_build_query($delete_query)); ?>">delete</a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <p>Nothing to see here - please try again later!</p>
<?php endif; ?>

<h2>Create</h2>
<p>Please enter the month to create a calendar for, and click 'Create'.</p>
<form class="row row-cols-lg-auto g-3 mb-3 align-items-center needs-validation" method="GET" action="/prayer/edit/" novalidate>
    <div class="col-12 position-relative">
        <label class="visually-hidden" for="for">Month</label>
        <input type="text" class="form-control" id="for" name="for" placeholder="Month e.g. '<?php _e($model->next_month); ?>'" required />
        <div class="invalid-tooltip">Please enter the month to create a prayer calendar for.</div>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Create</button>
    </div>
</form>

<?php

$this->footer();
