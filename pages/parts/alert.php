<?php

namespace Feeds\Pages;

use Feeds\App;

App::check();

/** @var \Feeds\Admin\Result $result */

?>

<?php if (isset($result)) : $alert = $result->success ? "success" : "warning"; ?>
    <div class="alert alert-<?php echo $alert; ?>"><?php echo $result->message; ?></div>
<?php endif; ?>
