<?php

namespace Obadiah;

use Obadiah\Api as A;
use Obadiah\Pages as P;
use Obadiah\Router\Router;

// initialise app
require_once "../app.class.php";
App::init();

// map api
define("API_PREFIX", "api");
Router::map_endpoint(A\Ajax\Ajax::class, uri_prefix: API_PREFIX);
Router::map_endpoint(A\Preload\Preload::class, uri_prefix: API_PREFIX, requires_auth: false);
Router::map_endpoint(A\Safeguarding\Safeguarding::class, uri_prefix: API_PREFIX);

// map pages
Router::map_endpoint(P\About\About::class);
Router::map_endpoint(P\Auth\Auth::class, requires_auth: false);
Router::map_endpoint(P\Bible\Bible::class);
Router::map_endpoint(P\Events\Events::class, requires_auth: false);
Router::map_endpoint(P\Prayer\Prayer::class, requires_admin: true);
Router::map_endpoint(P\Refresh\Refresh::class);
Router::map_endpoint(P\Robots\Robots::class, uri_path: "robots.txt", requires_auth: false);
Router::map_endpoint(P\Rota\Rota::class);
Router::map_endpoint(P\Services\Services::class, requires_auth: false);
Router::map_endpoint(P\Settings\Settings::class, requires_admin: true);
Router::map_endpoint(P\Upload\Upload::class, requires_admin: true);

// get and execute page action
$action = Router::get_action();
$action->send_headers();
$action->try_execute();
