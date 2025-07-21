<?php

namespace Obadiah\Pages\Parts\Input;

use Obadiah\App;
use Obadiah\Response\View;

App::check();

/** @var View $this */
/** @var Input_Model<boolean> $model */

?>

<input type="checkbox"
    class="form-check-input"
    id="<?php _e("%s", $model->name); ?>"
    name="<?php _e("%s", $model->name); ?>"
    value="true"
    <?php if($model->value) _h("checked") ?> />
