<?php

namespace Feeds\Pages\Parts\Header;

use Feeds\App;
use Feeds\Response\Html;

App::check();

/** @var Html $this */
/** @var Header $model */

?>
<!DOCTYPE html>
<html lang="en" class="vh-100">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php _e($model->title); ?> | Church Suite Feeds</title>
    <link href="/resources/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/resources/css/dragula.min.css" rel="stylesheet" />
    <link href="/resources/css/feeds.min.css" rel="stylesheet" />
</head>

<body class="d-flex flex-column h-100">

    <?php $this->header($model, "nav"); ?>

    <?php $this->header($model, "title"); ?>

    <?php if ($model->overflow_scroll) : ?>

        <main class="flex-grow-1 h-100" style="overflow-y: auto">
            <div class="container-fluid h-100">

        <?php else : ?>

        <main class="flex-grow-1">
            <div class="container-fluid">

        <?php endif; ?>
