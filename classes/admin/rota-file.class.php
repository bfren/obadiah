<?php

namespace Feeds\Admin;

use DateTimeImmutable;
use Feeds\App;
use Feeds\Config\Config as C;

App::check();

class Rota_File
{
    /**
     * Handle a rota CSV file upload.
     *
     * @return Result
     */
    public static function upload(): Result
    {
        // only allow CSV files
        in_array($_FILES["file"]["type"], array("text/csv", "application/vnd.ms-excel")) || die("You may only upload CSV files.");

        // make sure the name was set
        $name = $_POST["name"];
        if (!$name) die("You must enter the rota name, e.g. 22-2.");

        // get paths
        $tmp_path = $_FILES["file"]["tmp_name"];
        $csv_path = sprintf("%s/%s.csv", C::$dir->rota, $name);

        // move file to the correct location, overwriting whatever is already there
        if (move_uploaded_file($tmp_path, $csv_path)) {
            unlink(sprintf("%s/rota.cache", C::$dir->cache));
            return Result::success(sprintf("The rota file '%s' was uploaded successfully.", $name));
        } else {
            return Result::failure("Something went wrong uploading the rota file, please try again.");
        }
    }

    /**
     * Get the last modified date of the specified rota CSV file.
     *
     * @param string $filename          Rota CSV file name (without path).
     * @return string                   Formatted date time string.
     */
    public static function get_last_modified($filename): string
    {
        $path = sprintf("%s/%s", C::$dir->rota, $filename);
        $modified = new DateTimeImmutable(sprintf("@%s", filemtime($path)));
        $modified->setTimezone(C::$events->timezone);
        return $modified->format(C::$formats->sortable_datetime);
    }

    /**
     * Delete a rota CSV file.
     *
     * @param string $filename          Rota CSV file name (without path).
     * @return Result
     */
    public static function delete(string $filename): Result
    {
        $path = sprintf("%s/%s", C::$dir->rota, $filename);
        if (file_exists($path)) {
            unlink($path);
            unlink(sprintf("%s/rota.cache", C::$dir->cache));
            return Result::success(sprintf("CSV file '%s' was deleted.", $filename));
        } else {
            return  Result::failure(sprintf("Unable to find CSV file '%s'.", $filename));
        }
    }
}
