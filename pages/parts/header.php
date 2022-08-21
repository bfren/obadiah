<?php

namespace Feeds\Pages;

defined("IDX") || die("Nice try.");

/** @var string $title */

?>
<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php echo $title; ?> | Church Suite Feeds</title>
    <link href="/resources/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/resources/css/feeds.min.css" rel="stylesheet" />
</head>

<body class="d-flex flex-column h-100">

    <?php require_once("header-nav.php"); ?>

    <main class="flex-shrink-0">
        <div class="container-fluid">
