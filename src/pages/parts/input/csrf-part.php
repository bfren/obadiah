<?php

namespace Obadiah\Pages\Parts\Input;

use Obadiah\App;
use Obadiah\Response\View;

App::check();

/** @var View $this */
/** @var Input_Model<string> $model */

?>

<input type="hidden"
    id="<?php _e($model->name); ?>"
    name="<?php _e($model->name); ?>"
    value="<?php _e($model->value); ?>"
    />
