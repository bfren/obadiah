<?php

namespace Obadiah\Request;

use Obadiah\App;
use Obadiah\Helpers\Arr;

App::check();

class File
{
    /**
     * Construct an object representing an uploaded file.
     *
     * @param string $original_name             The original name of the file on the client machine.
     * @param null|string $mime_type            The mime type of the file, if the browser provided this information.
     * @param int $size_in_bytes                The size, in bytes, of the uploaded file.
     * @param string $tmp_name                  The temporary filename of the file in which the uploaded file was stored on the server.
     * @param null|string $error                The error associated with this file upload, or null on success.
     */
    public function __construct(
        public readonly string $original_name,
        public readonly ?string $mime_type,
        public readonly int $size_in_bytes,
        public readonly string $tmp_name,
        public readonly ?string $error

    ) {}

    /**
     * Create File object from $_FILES input array.
     *
     * @param mixed[] $input                    Input array from $_FILES object.
     * @return File                             File object.
     */
    public static function create(array $input): self
    {
        return new self(
            Arr::get($input, "name", ""),
            Arr::get($input, "type"),
            Arr::get_integer($input, "size", 0),
            Arr::get($input, "tmp_name", ""),
            self::$errors[Arr::get_integer($input, "error", 0)],
        );
    }

    /**
     * Text representations of file upload error codes.
     * See https://www.php.net/manual/en/features.file-upload.errors.php.
     *
     * @var array<null|string>
     */
    private static array $errors = array(
        0 => null,
        1 => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
        2 => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
        3 => "The uploaded file was only partially uploaded",
        4 => "No file was uploaded",
        6 => "Missing a temporary folder",
        7 => "Failed to write file to disk.",
        8 => "A PHP extension stopped the file upload.",
    );
}
