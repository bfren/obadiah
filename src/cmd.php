<?php

use Obadiah\App;
use Obadiah\Cli\Cli;
use Obadiah\Cli\Commands as C;

// initialise app
require_once "app.class.php";
App::init(false);

// map commands
Cli::map_command("preload", C\Preload::class);
Cli::map_command("pwhash", C\Password_Hash::class);
Cli::map_command("say:hello", C\Hello_World::class);

// parse and execute commandline
$command = Cli::get_command($argv);
$command->try_execute();
