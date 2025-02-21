<?php

namespace Obadiah\Pages\Parts\Input;

use Obadiah\App;
use Obadiah\Response\View;

App::check();

/** @var View $this */
/** @var Input_Model<integer> $model */

?>

<input type="number"
    class="form-control"
    id="<?php _e("%s", $model->name); ?>"
    name="<?php _e("%s", $model->name); ?>"
    value="<?php _h("%s", $model->value) ?>" />
