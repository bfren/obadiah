<?php

namespace Feeds\Admin;

use Feeds\App;
use Feeds\Cache\Cache;
use Feeds\Config\Config as C;

App::check();

class Bible_File
{
    /**
     * The name of the Bible plan file.
     */
    public const NAME = "plan";

    /**
     * Handle a Bible plan CSV file upload.
     *
     * @return Result
     */
    public static function upload(): Result
    {
        // only allow CSV files
        in_array($_FILES["file"]["type"], array("text/plain")) || die("You may only upload text files.");

        // get paths
        $tmp_path = $_FILES["file"]["tmp_name"];
        $csv_path = sprintf("%s/%s.txt", C::$dir->bible, self::NAME);

        // move file to the correct location, overwriting whatever is already there
        if (move_uploaded_file($tmp_path, $csv_path)) {
            Cache::clear_bible_plan();
            return Result::success("The Bible plan file was uploaded successfully.");
        } else {
            return Result::failure("Something went wrong uploading the Bible plan file, please try again.");
        }
    }

    /**
     * Get the last modified date of the Bible plan CSV file.
     *
     * @return string                   Formatted date time string.
     */
    public static function get_last_modified(): string
    {
        return File::get_last_modified(sprintf("%s/%s.txt", C::$dir->bible, self::NAME));
    }

    /**
     * Delete the Bible plan CSV file.
     *
     * @return Result
     */
    public static function delete(): Result
    {
        // delete the file
        $result = File::delete(sprintf("%s.txt", self::NAME), sprintf("%s/%s.txt", C::$dir->bible, self::NAME));

        // clear cache on success
        if ($result->success) {
            Cache::clear_bible_plan();
        }

        // return result
        return $result;
    }
}
