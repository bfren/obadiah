<?php

namespace Feeds\Pages;

defined("IDX") || die("Nice try.");

// output header
$title = "Rota";
require_once("parts/header.php"); ?>

<h1><?php echo $title; ?></h1>

<?php print_r($services); ?>

<?php require_once("parts/footer.php"); ?>
