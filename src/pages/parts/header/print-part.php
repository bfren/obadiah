<?php

namespace Obadiah\Pages\Parts\Header;

use Obadiah\App;

App::check();

/** @var View $this */
/** @var Header_Model $model */

?>
<!DOCTYPE html>
<html class="<?php _e($model->class ?: "print") ?>" lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?php _e($model->title); ?> | Obadiah</title>
    <link href="https://static.bcg.xyz/fonts/selly-park.css" rel="stylesheet" />
    <link href="/resources/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/resources/css/obadiah.css" rel="stylesheet" />
</head>

<body>

    <main>
        <div class="container-fluid">