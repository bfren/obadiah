<?php

namespace Obadiah\Pages\Home;

use Obadiah\App;
use Obadiah\Config\Config as C;
use Obadiah\Pages\Home\Index_Model;
use Obadiah\Pages\Parts\Header\Header_Model;
use Obadiah\Request\Request;
use Obadiah\Response\View;

App::check();

/** @var View $this */
/** @var Index_Model $model */

// get refresh links
$refresh_print_uri = sprintf("/refresh/print/?%s", http_build_query($model->refresh_print));
$refresh_feed_uri = sprintf("webcal://%s/refresh/ics/?%s", Request::$host, http_build_query($model->refresh_feed));

// output header
$this->header(new Header_Model("Home", subtitle: "These pages house the various feeds generated from Church Suite."));

?>

<h2>Rota</h2>
<p>To view this week&rsquo;s services, please click <a href="/rota/?<?php _e(http_build_query($model->this_week)); ?>">here</a>.</p>
<p>The following links will give you quick and printable rotas for upcoming services.</p>
<p><a href="/rota/notices/?<?php _e(http_build_query($model->upcoming)); ?>">Sunday services for the next four weeks</a></p>

<h2>Refresh</h2>
<p>View a printable version of this month&rsquo;s refresh calendar <a href="<?php _e($refresh_print_uri); ?>" target="_blank">here</a>.</p>
<p>Use <a href="<?php _e($refresh_feed_uri); ?>">this link</a> to subscribe to the Refresh calendar feed.</p>

<?php if (Request::$session->is_admin) : ?>
    <h2>Caches</h2>
    <p><a href="/api/preload?debug=true">Reload</a> caches (this happens automatically every <?php _e("%s", C::$cache->duration_in_seconds / 60); ?> minutes).</p>
<?php endif; ?>

<?php

$this->footer();
