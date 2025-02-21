<?php

namespace Obadiah\Pages\Settings;

use Obadiah\App;
use Obadiah\Config\Config as C;
use Obadiah\Pages\Settings\Index_Model;
use Obadiah\Pages\Parts\Header\Header_Model;
use Obadiah\Pages\Settings\Form_Model;
use Obadiah\Response\View;

App::check();

/** @var View $this */
/** @var Index_Model $model */

// output header
$this->header(new Header_Model("Settings", subtitle: "Configure the various settings controlling how Obadiah works."));

?>

<h2>General</h2>
<?php $this->part("form", model: new Form_Model("general", values: C::$general)); ?>

<h2>Baserow</h2>
<?php $this->part("form", model: new Form_Model("baserow", values: C::$baserow)); ?>

<h2>Cache</h2>
<?php $this->part("form", model: new Form_Model("cache", values: C::$cache)); ?>

<h2>Church Suite</h2>
<?php $this->part("form", model: new Form_Model("churchsuite", values: C::$churchsuite)); ?>

<h2>Events</h2>
<?php $this->part("form", model: new Form_Model("events", values: C::$events)); ?>

<h2>Formats</h2>
<?php $this->part("form", model: new Form_Model("formats", values: C::$formats)); ?>

<h2>Login</h2>
<?php $this->part("form", model: new Form_Model("login", values: C::$login)); ?>

<h2>Prayer</h2>
<?php $this->part("form", model: new Form_Model("prayer", values: C::$prayer)); ?>

<h2>Refresh</h2>
<?php $this->part("form", model: new Form_Model("refresh", values: C::$refresh)); ?>

<h2>Rota</h2>
<?php $this->part("form", model: new Form_Model("rota", values: C::$rota)); ?>

<?php

$this->footer();
