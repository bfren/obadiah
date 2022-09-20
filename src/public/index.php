<?php

namespace Feeds;

use Feeds\Router\Router;

// initialise app
require_once "../app.class.php";
App::init();

// map pages
Router::map_page("Ajax");
Router::map_page("Auth", requires_auth: false);
Router::map_page("Events", requires_auth: false);
Router::map_page("Prayer");
Router::map_page("Preload", requires_auth: false);
Router::map_page("Refresh");
Router::map_page("Rota");
Router::map_page("Services", requires_auth: false);
Router::map_page("Upload", requires_admin: true);

// get and execute page action
$action = Router::get_action();
$action->send_headers();
$action->execute();
