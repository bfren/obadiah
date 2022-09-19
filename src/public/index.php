<?php

namespace Feeds;

use Feeds\Router\Router;

// initialise app
require_once "../app.class.php";
App::init();

// map routes
Router::map("Ajax");
Router::map("Auth", requires_auth: false);
Router::map("Events", requires_auth: false);
Router::map("Prayer");
Router::map("Preload", requires_auth: false);
Router::map("Refresh");
Router::map("Rota");
Router::map("Services", requires_auth: false);
Router::map("Upload", requires_admin: true);

// dispatch route
$action = Router::dispatch();

// execute action
$action->send_headers();
$action->execute();
