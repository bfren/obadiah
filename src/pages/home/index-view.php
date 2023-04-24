<?php

namespace Feeds\Pages\Home;

use Feeds\App;
use Feeds\Pages\Home\Index_Model;
use Feeds\Pages\Parts\Header\Header_Model;
use Feeds\Response\View;

App::check();

/** @var View $this */
/** @var Index_Model $model */

// output header
$this->header(new Header_Model("Home", subtitle: "These pages house the various feeds generated from Church Suite."));

?>

<h2>Rota</h2>
<p>To view this week&rsquo;s services, please click <a href="/rota/?<?php _e(http_build_query($model->this_week)); ?>">here</a>.</p>

<h3>Printable</h3>
<p>The following links will give you quick and printable rotas for upcoming services.</p>
<p><a href="/rota/notices/?<?php _e(http_build_query($model->upcoming)); ?>">Sunday services for the next four weeks</a></p>

<h2>Refresh</h2>
<p>Use <a href="/refresh/ics/?<?php _e(http_build_query($model->refresh)); ?>">this link</a> to subscribe to the Refresh calendar feed.</p>

<?php

$this->footer();
