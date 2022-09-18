<?php

namespace Feeds\Response;

use Feeds\App;
use Feeds\Config\Config as C;
use SplFileInfo;

App::check();

abstract class View extends Action
{
    protected function __construct(
        protected readonly string $page,
        protected readonly string $name,
        protected readonly mixed $model
    ) {
    }

    public function execute(): void
    {
        // get path to view
        $path = sprintf("%s/%s/views/%s.php", C::$dir->pages, $this->page, $this->name);

        // show view
        $this->require($path, $this->model);
    }

    protected function require(string $path, mixed $model): void
    {
        $script = new SplFileInfo($path);
        if ($script->isFile()) {
            require $path;
            return;
        }

        App::die("Unable to find view %s.", $path);
    }
}
