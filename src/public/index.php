<?php

namespace Feeds;

use Feeds\Pages as P;
use Feeds\Router\Router;

// initialise app
require_once "../app.class.php";
App::init();

// map pages
Router::map_page(P\Ajax\Ajax::class);
Router::map_page(P\Auth\Auth::class, requires_auth: false);
Router::map_page(P\Events\Events::class, requires_auth: false);
Router::map_page(P\Prayer\Prayer::class, requires_admin: true);
Router::map_page(P\Preload\Preload::class, requires_auth: false);
Router::map_page(P\Refresh\Refresh::class);
Router::map_page(P\Rota\Rota::class);
Router::map_page(P\Services\Services::class, requires_auth: false);
Router::map_page(P\Upload\Upload::class, requires_admin: true);

// get and execute page action
$action = Router::get_action();
$action->send_headers();
$action->execute();
