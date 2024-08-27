<?php

namespace Obadiah\Pages\Parts\Reading;

use Obadiah\App;
use Obadiah\Config\Config as C;

App::check();

/** @var View $this */
/** @var string $model */

$param = array(
    "search" => $model,
    "version" => C::$rota->bible_version
);

$url = sprintf("https://www.biblegateway.com/passage/?%s", http_build_query($param));

?>

<a href="<?php _e($url) ?>" target="_blank"><?php _h(str_replace(" ", "&nbsp;", $model)) ?></a>