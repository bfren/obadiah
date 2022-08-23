<?php

namespace Feeds\Admin;

use Feeds\App;
use Feeds\Cache\Cache;
use Feeds\Config\Config as C;

App::check();

class Prayer_File
{
    /**
     * Adults file name.
     */
    private const ADULTS = "adults.csv";

    /**
     * Children file name.
     */
    private const CHILDREN = "children.csv";

    /**
     * Handle a file upload.
     *
     * @param string $type              Upload type, i.e. 'adults' or 'children'.
     * @param string $filename          The filename to save - use one of the class constants.
     * @return Result
     */
    private static function upload(string $type, string $filename):Result
    {
        // only allow CSV files
        in_array($_FILES["file"]["type"], array("text/csv", "application/vnd.ms-excel")) || die("You may only upload CSV files.");

        // get paths
        $tmp_path = $_FILES["file"]["tmp_name"];
        $csv_path = sprintf("%s/%s", C::$dir->prayer, $filename);

        // move file to the correct location, overwriting whatever is already there
        if (move_uploaded_file($tmp_path, $csv_path)) {
            Cache::clear_prayer_calendar();
            return Result::success(sprintf("The %s prayer calendar file was uploaded successfully.", $type));
        } else {
            return Result::failure(sprintf("Something went wrong uploading the %s prayer calendar file, please try again.", $type));
        }
    }

    /**
     * Handle an adults prayer calendar CSV file upload.
     *
     * @return Result
     */
    public static function upload_adults(): Result
    {
        return self::upload("adults", self::ADULTS);
    }

    /**
     * Handle a children prayer calendar CSV file upload.
     *
     * @return Result
     */
    public static function upload_children(): Result
    {
        return self::upload("children", self::CHILDREN);
    }

    /**
     * Get the last modified date of the specified prayer calendar CSV file.
     *
     * @param string $filename          Prayer Calendar CSV file name (without path).
     * @return string                   Formatted date time string.
     */
    public static function get_last_modified($filename): string
    {
        return File::get_last_modified(sprintf("%s/%s", C::$dir->prayer, $filename));
    }

    /**
     * Delete a prayer calendar CSV file.
     *
     * @param string $filename          Prayer calendar CSV file name (without path).
     * @return Result
     */
    public static function delete(string $filename): Result
    {
        // delete the file
        $result = File::delete($filename, sprintf("%s/%s", C::$dir->prayer, $filename), "prayer");

        // clear cache on success
        if($result->success){
            Cache::clear_prayer_calendar();
        }

        // return result
        return $result;
    }
}
