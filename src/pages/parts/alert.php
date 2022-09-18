<?php

namespace Feeds\Pages;

use Feeds\App;

App::check();

/** @var \Feeds\Admin\Result $result */

?>

<?php if (isset($result)) : $alert = $result->success ? "success" : "warning"; ?>
    <div class="alert alert-<?php _e($alert); ?> mt-2"><?php _e($result->message); ?></div>
<?php endif; ?>
