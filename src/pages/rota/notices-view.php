<?php

namespace Obadiah\Pages\Rota;

use Obadiah\App;
use Obadiah\Config\Config as C;
use Obadiah\Pages\Parts\Header\Header_Model;
use Obadiah\Response\View;

App::check();

/** @var View $this */
/** @var Print_Model $model */

// output header
$this->header(new Header_Model("Rota", class: "notices"), variant: "print");

?>

<h2>
    <?php _e(C::$general->church_name); ?> Rota
    <?php if ($model->time) _e(" - %s", $model->time); ?>
    <?php if ($model->day) _e($model->day); ?>
    <?php if ($model->person) _e(" - %s", $model->person); ?>
</h2>

<?php

$this->part("services", variant: "print", model: $model->days);

$this->footer(variant: "print");
