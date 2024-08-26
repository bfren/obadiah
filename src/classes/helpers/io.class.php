<?php

namespace Obadiah\Helpers;

use Obadiah\App;
use SplFileInfo;

App::check();

class IO
{
    /**
     * Safely read the contents of a file, trimming results.
     *
     * @param string|SplFileInfo $file      Path to file, or file info.
     * @return string                       File contents or empty string.
     */
    public static function file_get_contents(string|SplFileInfo $file): string
    {
        // if $file is not a string create SplFileInfo
        $info = is_string($file) ? new SplFileInfo($file) : $file;

        // ensure the file exists
        $path = $info->getRealPath();
        if ($path === false) {
            _l("File does not exist: %s.", $info->getPathname());
            return "";
        }

        // read file contents
        $contents = file_get_contents($path);
        if ($contents === false) {
            return "";
        }

        // return contents trimmed
        return trim($contents);
    }

    /**
     * Get posted PHP input (e.g. JSON).
     *
     * @return string                       PHP input contents or empty string.
     */
    public static function php_input(): string
    {
        $contents = file_get_contents("php://input");
        if ($contents === false) {
            return "";
        }

        return $contents;
    }
}
