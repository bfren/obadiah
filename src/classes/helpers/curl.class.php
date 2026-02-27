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
                $wait_time = pow(2, $attempt); // 1s, 2s, 4s, 8s
                _l("Retry attempt %d (waiting %ds).", $attempt + 1, $wait_time);
                usleep($wait_time * 1000000); // Convert to microseconds
            }
        }

        return false;
    }
}
