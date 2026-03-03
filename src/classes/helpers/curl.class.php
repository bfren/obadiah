<?php

namespace Obadiah\Helpers;

use CurlHandle;
use Obadiah\App;

App::check();

class Curl
{
    /**
     * Execute a CURL request with retry logic and exponential backoff.
     *
     * @param CurlHandle $handle                    CURL handle.
     * @param int $max_retries                      Maximum number of retries.
     * @return string|bool                          Response string or false on failure.
     */
    public static function execute_with_retry($handle, int $max_retries = 3): string|bool
    {
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($handle, CURLOPT_TCP_KEEPALIVE, 1);

        for ($attempt = 0; $attempt <= $max_retries; $attempt++) {
            $response = curl_exec($handle);
            $http_code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

            // success on 2xx responses
            if (is_string($response) && $http_code >= 200 && $http_code < 300) {
                return $response;
            }

            // don't retry on 4xx errors (client errors)
            if ($http_code >= 400 && $http_code < 500) {
                return $response;
            }

            // retry on network errors or 5xx errors
            if ($attempt < $max_retries) {
                $wait_time = intval((pow(2, $attempt) + random_int(0, 1000) / 1000) * 1000000);
                _l("Retry attempt %d (waiting %ds).", $attempt + 1, $wait_time);
                usleep($wait_time); // Convert to microseconds
            }
        }

        return false;
    }

    /**
     * Safely log curl errors, removing sensitive information first.
     *
     * @param CurlHandle $handle                    CURL handle.
     * @return void
     */
    public static function log_error(CurlHandle $handle): void
    {
        $error = print_r(curl_error($handle), true);

        // remove Authorization tokens from error messages
        $sanitised = preg_replace('/Authorization:\s*Token\s*\S+/i', 'Authorization: Token [REDACTED]', $error) ?? $error;

        // semove API URIs that might contain sensitive info
        $sanitised = preg_replace('/https?:\/\/[^\s]+/i', '[URL REDACTED]', $sanitised) ?? $sanitised;

        // log sanitised error
        _l($sanitised);
    }
}
