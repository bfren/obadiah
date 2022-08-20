<?php

namespace Feeds\Pages;

use Feeds\Request\Request;

defined("IDX") || die("Nice try.");
Request::is_admin() || Request::redirect("/logout.php");

// output header
$title = "Admin";
require_once("parts/header.php");

?>

<h1><?php echo $title; ?></h1>
<p>Update settings and upload files.</p>

<h2>Rota</h2>

<?php require_once("parts/footer.php"); ?>
