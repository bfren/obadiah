<?php

namespace Obadiah\Pages\Settings;

use Obadiah\App;
use Obadiah\Pages\Settings\Index_Model;
use Obadiah\Pages\Parts\Header\Header_Model;
use Obadiah\Response\View;

App::check();

/** @var View $this */
/** @var Index_Model $model */

// output header
$this->header(new Header_Model("Settings", subtitle: "Configure the various settings controlling how Obadiah works."));

?>

<?php

$this->footer();
