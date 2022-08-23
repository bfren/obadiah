<?php

namespace Feeds\Admin;

use DateTimeImmutable;
use Feeds\App;
use Feeds\Config\Config as C;

App::check();

class File
{
    /**
     * Get the last modified date of the specified file.
     *
     * @param string $path              Absolute path to file.
     * @return string                   Formatted date time string.
     */
    public static function get_last_modified($path): string
    {
        $modified = new DateTimeImmutable(sprintf("@%s", filemtime($path)));
        return $modified->setTimezone(C::$events->timezone)->format(C::$formats->sortable_datetime);
    }

    /**
     * Delete a file.
     *
     * @param string $filename          File name.
     * @param string $path              Absolute path to file.
     * @param string $cache             The cache to clear.
     * @return Result
     */
    public static function delete(string $filename, string $path, string $cache): Result
    {
        if (file_exists($path)) {
            unlink($path);
            unlink(sprintf("%s/%s.cache", C::$dir->cache, $cache));
            return Result::success(sprintf("File '%s' was deleted.", $filename));
        } else {
            return  Result::failure(sprintf("Unable to find file '%s'.", $filename));
        }
    }
}
