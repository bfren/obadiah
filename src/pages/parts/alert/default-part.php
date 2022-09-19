<?php

namespace Feeds\Pages\Parts\Alert;

use Feeds\App;
use Feeds\Admin\Result;

App::check();

/** @var Result $model */

?>

<?php if (isset($model)) : $alert = $model->success ? "success" : "warning"; ?>
    <div class="alert alert-<?php _e($alert); ?> mt-2"><?php _e($model->message); ?></div>
<?php endif; ?>
