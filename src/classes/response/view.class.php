<?php

namespace Feeds\Response;

use Feeds\Admin\Result;
use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Pages\Parts\Header\Header_Model;
use SplFileInfo;

App::check();

class View extends Action
{
    /**
     * Create View object and add headers.
     *
     * @param string $page              Page name.
     * @param string $name              View name ('index' by default).
     * @param mixed $model              Optional view model.
     * @param int $status               Optional HTTP status ('200' by default).
     * @return void
     */
    public function __construct(
        public readonly string $page,
        public readonly string $name = "index",
        public readonly mixed $model = null,
        int $status = 200
    ) {
        // add default headers
        parent::__construct($status);

        // add debug headers
        if ($this->add_debug_headers()) {
            return;
        }

        // add standard HTML headers
        $this->add_header("Content-Type", "text/html; charset=utf-8");
    }

    /**
     * Execute the view, requiring the view file and passing the model.
     *
     * @return void
     */
    public function execute(): void
    {
        // get path to view
        $path = sprintf("%s/%s/%s-view.php", C::$dir->pages, $this->page, $this->name);

        // show view
        $this->require($path, $this->model);
    }

    /**
     * Require a view file, to enforce model scope.
     *
     * @param string $path              Absolute path to view file.
     * @param mixed $model              View model.
     * @return void
     */
    protected function require(string $path, mixed $model): void
    {
        $script = new SplFileInfo($path);
        if (!$script->isFile()) {
            App::die("Unable to find view %s.", $path);
        }

        require $path;
    }

    /**
     * Output view part.
     *
     * @param string $name              Part name.
     * @param null|string $variant      Part variant ('default' used if not set).
     * @param mixed $model              Part model.
     * @return void
     */
    public function part(string $name, ?string $variant = null, mixed $model = null): void
    {
        // paths to search for part
        $path_page = sprintf("%s/%s/%s%s-part.php", C::$dir->pages, $this->page, $name, $variant ? sprintf("-%s", $variant) : "");
        $path_shared = sprintf("%s/parts/%s/%s-part.php", C::$dir->pages, $name, $variant ?: "default");

        // prefer page part
        $file_page = new SplFileInfo($path_page);
        if ($file_page->isFile()) {
            $this->require($file_page->getRealPath(), $model);
            return;
        }

        // get shared part
        $file_shared = new SplFileInfo($path_shared);
        if ($file_shared->isFile()) {
            $this->require($file_shared->getRealPath(), $model);
        }
    }

    /**
     * Output header part.
     *
     * @param Header_Model $model       Part model.
     * @param null|string $variant      Header variant name.
     * @return void
     */
    public function header(Header_Model $model, ?string $variant = null): void
    {
        $this->part("header", $variant ?: "default", $model);
    }

    /**
     * Output footer part.
     *
     * @param null|string $variant      Footer variant name.
     * @return void
     */
    public function footer(?string $variant = null): void
    {
        $this->part("footer", $variant ?: "default");
    }

    /**
     * Output alert part.
     *
     * @param null|Result $result       Result object to use as the model.
     * @return void
     */
    public function alert(?Result $result) :void
    {
        $this->part("alert", model: $result);
    }
}
