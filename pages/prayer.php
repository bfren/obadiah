<?php

namespace Feeds\Pages;

use Feeds\App;

App::check();

// output header
$title = "Prayer";
require_once("parts/header.php");

?>

<h2 class="border-bottom"><?php echo $title; ?></h2>

<?php require_once("parts/footer.php"); ?>
