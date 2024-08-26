<?php

namespace Obadiah;

use Obadiah\Api as A;
use Obadiah\Pages as P;
use Obadiah\Router\Router;

// initialise app
require_once "../app.class.php";
App::init();

// map api
Router::map_endpoint(A\Safeguarding\Safeguarding::class);

// map pages
Router::map_endpoint(P\Ajax\Ajax::class);
Router::map_endpoint(P\Auth\Auth::class, requires_auth: false);
Router::map_endpoint(P\Events\Events::class, requires_auth: false);
Router::map_endpoint(P\Prayer\Prayer::class, requires_admin: true);
Router::map_endpoint(P\Preload\Preload::class, requires_auth: false);
Router::map_endpoint(P\Refresh\Refresh::class);
Router::map_endpoint(P\Rota\Rota::class);
Router::map_endpoint(P\Services\Services::class, requires_auth: false);
Router::map_endpoint(P\Upload\Upload::class, requires_admin: true);

// get and execute page action
$action = Router::get_action();
$action->send_headers();
$action->execute();
