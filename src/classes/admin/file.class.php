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
        return $modified->setTimezone(C::$general->timezone)->format(C::$formats->sortable_datetime);
    }

    /**
     * Delete a file.
     *
     * @param string $filename          File name.
     * @param string $path              Absolute path to file.
     * @return Result
     */
    public static function delete(string $filename, string $path): Result
    {
        if (file_exists($path) && unlink($path)) {
            return Result::success(sprintf("File '%s' was deleted.", $filename));
        }

        return Result::failure(sprintf("Unable to delete file '%s'.", $filename));
    }
}
