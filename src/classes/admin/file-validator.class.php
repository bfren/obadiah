<?php

namespace Obadiah\Admin;

use Obadiah\App;

App::check();

class File_Validator
{
    /**
     * Maximum file size in bytes (10MB).
     */
    private const MAX_FILE_SIZE = 10 * 1024 * 1024;

    /**
     * Validate a CSV file upload.
     *
     * @param mixed[] $file_info            File info from $_FILES array.
     * @return array{valid: bool, error: ?string}  Validation result.
     */
    public static function validate_csv(array $file_info): array
    {
        // check file was uploaded without errors
        $error_code = $file_info["error"] ?? UPLOAD_ERR_NO_FILE;
        if ($error_code !== UPLOAD_ERR_OK) {
            return ["valid" => false, "error" => self::get_upload_error_message($error_code)];
        }

        // check file size
        $size = $file_info["size"] ?? 0;
        if ($size > self::MAX_FILE_SIZE) {
            return ["valid" => false, "error" => "File is too large (maximum 10MB)."];
        }

        // check file extension
        $name = $file_info["name"] ?? "";
        if (!str_ends_with(strtolower($name), ".csv")) {
            return ["valid" => false, "error" => "File must have .csv extension."];
        }

        // check MIME type (note: can be spoofed, so check magic bytes too)
        $type = $file_info["type"] ?? "";
        if (!in_array($type, ["text/csv", "text/plain", "application/vnd.ms-excel"])) {
            return ["valid" => false, "error" => "File must be a CSV file."];
        }

        // check magic bytes - CSV files should be text
        $tmp_path = $file_info["tmp_name"] ?? "";
        if (is_string($tmp_path) && file_exists($tmp_path)) {
            if (!self::is_text_file($tmp_path)) {
                return ["valid" => false, "error" => "File does not appear to be a text file."];
            }
        }

        return ["valid" => true, "error" => null];
    }

    /**
     * Validate a text file upload.
     *
     * @param mixed[] $file_info            File info from $_FILES array.
     * @param string $extension             Required file extension (e.g. "txt").
     * @return array{valid: bool, error: ?string}  Validation result.
     */
    public static function validate_text(array $file_info, string $extension = "txt"): array
    {
        // check file was uploaded without errors
        $error_code = $file_info["error"] ?? UPLOAD_ERR_NO_FILE;
        if ($error_code !== UPLOAD_ERR_OK) {
            return ["valid" => false, "error" => self::get_upload_error_message($error_code)];
        }

        // check file size
        $size = $file_info["size"] ?? 0;
        if ($size > self::MAX_FILE_SIZE) {
            return ["valid" => false, "error" => "File is too large (maximum 10MB)."];
        }

        // check file extension
        $name = $file_info["name"] ?? "";
        if (!str_ends_with(strtolower($name), "." . $extension)) {
            return ["valid" => false, "error" => "File must have ." . $extension . " extension."];
        }

        // check MIME type
        $type = $file_info["type"] ?? "";
        if (!in_array($type, ["text/plain", "text/txt", "application/octet-stream"])) {
            return ["valid" => false, "error" => "File must be a text file."];
        }

        // check magic bytes - text files should not contain null bytes
        $tmp_path = $file_info["tmp_name"] ?? "";
        if (is_string($tmp_path) && file_exists($tmp_path)) {
            if (!self::is_text_file($tmp_path)) {
                return ["valid" => false, "error" => "File does not appear to be a text file."];
            }
        }

        return ["valid" => true, "error" => null];
    }

    /**
     * Check if a file is text-based (not binary).
     *
     * @param string $filepath              Path to file to check.
     * @return bool                         True if file appears to be text.
     */
    private static function is_text_file(string $filepath): bool
    {
        // read first 512 bytes
        $handle = fopen($filepath, "rb");
        if ($handle === false) {
            return false;
        }

        $chunk = fread($handle, 512);
        fclose($handle);

        if ($chunk === false || strlen($chunk) === 0) {
            return true; // empty file is text
        }

        // check for null bytes (indicator of binary file)
        if (strpos($chunk, "\0") !== false) {
            return false;
        }

        // check for UTF-8 BOM
        if (substr($chunk, 0, 3) === "\xEF\xBB\xBF") {
            return true;
        }

        // check if mostly printable characters
        $printable_count = 0;
        for ($i = 0; $i < strlen($chunk); $i++) {
            $byte = ord($chunk[$i]);
            // allow common text bytes and UTF-8 sequences
            if (($byte >= 0x20 && $byte <= 0x7E) ||  // printable ASCII
                ($byte >= 0x09 && $byte <= 0x0D) ||  // whitespace (tab, newline, etc)
                $byte >= 0x80) {                       // UTF-8 multi-byte
                $printable_count++;
            }
        }

        return ($printable_count / strlen($chunk)) > 0.75;
    }

    /**
     * Get a user-friendly error message for upload errors.
     *
     * @param int $error_code               PHP upload error code.
     * @return string                       Error message.
     */
    private static function get_upload_error_message(int $error_code): string
    {
        return match($error_code) {
            UPLOAD_ERR_INI_SIZE => "File exceeds upload_max_filesize.",
            UPLOAD_ERR_FORM_SIZE => "File exceeds MAX_FILE_SIZE.",
            UPLOAD_ERR_PARTIAL => "File was only partially uploaded.",
            UPLOAD_ERR_NO_FILE => "No file was uploaded.",
            UPLOAD_ERR_NO_TMP_DIR => "Missing temporary folder.",
            UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
            UPLOAD_ERR_EXTENSION => "File upload stopped by extension.",
            default => "Unknown upload error.",
        };
    }
}
