<?php

namespace Feeds\Pages;

use Feeds\App;

App::check();

/** @var \Feeds\Admin\Result $result */

?>

<?php if (isset($result)) : $alert = $result->success ? "success" : "warning"; ?>
    <div class="alert alert-<?php echo $alert; ?> mt-2"><?php echo $result->message; ?></div>
<?php endif; ?>
