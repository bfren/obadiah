<?php

namespace Obadiah\Admin;

use Obadiah\App;
use Obadiah\Cache\Cache;
use Obadiah\Config\Config as C;
use Obadiah\Helpers\Arr;
use Obadiah\Request\Request;

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
        $info = Arr::get(Request::$files, "file");
        in_array(Arr::get($info, "type"), array("text/csv", "application/vnd.ms-excel")) || App::die("You may only upload CSV files.");

        // make sure the name was set
        $name = Request::$post->string("name");
        if (!$name) App::die("You must enter the rota name, e.g. 22-2.");

        // get paths
        $tmp_path = Arr::get($info, "tmp_name");
        $csv_path = sprintf("%s/%s.csv", C::$dir->rota, $name);

        // move file to the correct location, overwriting whatever is already there
        if (is_string($tmp_path) && move_uploaded_file($tmp_path, $csv_path)) {
            Cache::clear_rota();
            return Result::success(sprintf("The rota file '%s' was uploaded successfully.", $name));
        }

        return Result::failure("Something went wrong uploading the rota file, please try again.");
    }

    /**
     * Get the last modified date of the specified rota CSV file.
     *
     * @param string $filename                      Rota CSV file name (without path).
     * @return string                               Formatted date time string.
     */
    public static function get_last_modified($filename): string
    {
        return File::get_last_modified(sprintf("%s/%s", C::$dir->rota, $filename));
    }

    /**
     * Delete a rota CSV file.
     *
     * @param string $filename                      Rota CSV file name (without path).
     * @return Result
     */
    public static function delete(string $filename): Result
    {
        // delete the file
        $result = File::delete($filename, sprintf("%s/%s", C::$dir->rota, $filename));

        // clear cache on success
        if ($result->success) {
            Cache::clear_rota();
        }

        // return result
        return $result;
    }
}
