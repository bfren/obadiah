<?php

namespace Obadiah\Pages\Settings;

use Obadiah\App;
use Obadiah\Pages\Parts\Input\Input_Model;
use Obadiah\Response\View;

App::check();

/** @var View $this */
/** @var Form_Model $model */

$values = $model->values->as_array();

?>

<form id="<?php _e("%s", $model->id); ?>" class="settings">
    <?php foreach ($values as $key => $value): $type = gettype($value); ?>
        <div class="row mb-1">
            <label for="<?php _e("%s", $key); ?>" class="col-sm-3 col-form-label"><?php _e("%s", $key); ?></label>
            <div class="col-sm-9">
                <?php if ($model->is_supported($type)): ?>
                <?php $this->part("input", $type, new Input_Model($key, $value)); ?>
                <?php else: ?>
                <?php $this->part("input", "unsupported"); ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
    <button type="submit" class="btn btn-primary">Save</button>
    <span class="ps-3 error text-danger"></span>
</form>
