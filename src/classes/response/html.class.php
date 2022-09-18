<?php

namespace Feeds\Response;

use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Pages\Parts\Header\Header;

App::check();

class Html extends View
{
    public function __construct(string $page, string $name = "index", mixed $model = null)
    {
        parent::__construct($page, $name, $model);
    }

    public function part(string $name, ?string $variant = null, mixed $model = null): void
    {
        // use default if variant is not set
        $variant = $variant ?: "default";

        // get path to part
        $path = sprintf("%s/parts/%s/views/%s.php", C::$dir->pages, $name, $variant);

        // require part with model
        $this->require($path, $model);
    }

    public function header(Header $model, ?string $variant = null): void
    {
        $this->part("header", $variant, $model);
    }

    public function footer(?string $variant = null): void
    {
        $this->part("footer", variant: $variant);
    }
}
