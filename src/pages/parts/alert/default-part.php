<?php

namespace Obadiah\Pages\Parts\Alert;

use Obadiah\App;
use Obadiah\Admin\Result;

App::check();

/** @var Result|null $model */

?>

<?php if ($model): $alert = $model->success ? "success" : "warning"; ?>
    <div class="alert alert-<?php _e($alert); ?> mt-2"><?php _e($model->message); ?></div>
<?php endif; ?>