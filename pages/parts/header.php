<?php

namespace Feeds\Pages;

defined("IDX") || die("Nice try.");

// build navigation links
$links = array(
    "Home" => "/",
    "Rota" => "/rota",
    "Prayer Calendar" => "/prayer"
);

?><!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title; ?> | Church Suite Feeds</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
</head>

<body class="d-flex flex-column h-100">
    <div class="container-fluid">
        <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                <img class="logo me-4" style="max-height: 40px;" src="/img/logo-small.png" alt="Christ Church Selly Park" />
                <span class="fs-4">Church Suite Feeds</span>
            </a>

            <ul class="nav nav-pills">
                <?php foreach($links as $link_title => $link): ?>
                    <li class="nav-item">
                        <?php if($title == $link_title): ?>
                            <a href="<?php echo $link; ?>" class="nav-link active" aria-current="page"><?php echo $link_title; ?></a>
                        <?php else: ?>
                            <a href="<?php echo $link; ?>" class="nav-link"><?php echo $link_title; ?></a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </header>
    </div>

    <main class="flex-shrink-0">
        <div class="container-fluid">
