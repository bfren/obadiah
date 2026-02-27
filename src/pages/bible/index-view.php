<?php

namespace Obadiah\Pages\Bible;

use Obadiah\App;
use Obadiah\Pages\Bible\Index_Model;
use Obadiah\Pages\Parts\Header\Header_Model;
use Obadiah\Response\View;

App::check();

/** @var View $this */
/** @var Index_Model $model */

// output header
$this->header(new Header_Model("Bible", subtitle: "Access the Bible text using the New English Translation (NET)."));

?>

<div>
    <h2><?php _e($model->ref); ?></h2>
    <?php _h($model->text); ?>
</div>

<?php

$this->footer();
