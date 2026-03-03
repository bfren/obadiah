<?php

namespace Obadiah\Admin;

use Obadiah\App;
use Obadiah\Cache\Cache;
use Obadiah\Config\Config as C;
use Obadiah\Helpers\Arr;
use Obadiah\Request\Request;

App::check();

class Bible_File
{
    /**
     * The name of the Bible plan file.
     */
    public const NAME = "plan";

    /**
     * Handle a Bible plan text file upload.
     *
     * @return Result
     */
    public static function upload(): Result
    {
        // only allow text files
        $info = Arr::get(Request::$files, "file");
        if ($info === null || $info->error) {
            _l("File upload error: '%s'.", $info->error);
            App::die("No file was uploaded.");
        }
        in_array($info->mime_type, array("text/plain")) || App::die("You may only upload text files.");

        // get paths
        $csv_path = sprintf("%s/%s.txt", C::$dir->bible, self::NAME);

        // move file to the correct location, overwriting whatever is already there
        if (is_string($info->tmp_name) && move_uploaded_file($info->tmp_name, $csv_path)) {
            Cache::clear_bible_plan();
            return Result::success("The Bible plan file was uploaded successfully.");
        }

        return Result::failure("Something went wrong uploading the Bible plan file, please try again.");
    }

    /**
     * Get the last modified date of the Bible plan text file.
     *
     * @return string                               Formatted date time string.
     */
    public static function get_last_modified(): string
    {
        return File::get_last_modified(sprintf("%s/%s.txt", C::$dir->bible, self::NAME));
    }

    /**
     * Delete the Bible plan text file.
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
