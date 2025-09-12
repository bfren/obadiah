<?php

namespace Obadiah\NetBible;

use Obadiah\App;
use Obadiah\Helpers\Log;

App::check();

class Api
{
    /**
     * Create API object.
     *
     */
    public function __construct() {}

    /**
     * Get a passage from the NET Bible API.
     *
     * @param string $passage                       The passage to retrieve - multiple passages should be separated by a semi-colon.
     * @param string $type                          The return type - either 'text' (default) or 'json'.
     * @return mixed                                If $passage is a valid Bible reference, the NET Bible text of that passage.
     */
    private function get(string $passage, string $type = "text"): mixed
    {
        // build URL from data
        $formatting = match($type) {
            "text" => "para",
            default => "plain"
        };
        $url = sprintf("https://labs.bible.org/api/?passage=%s&type=%s&formatting=%s", urlencode($passage), $type, $formatting);

        // create curl request
        $handle = curl_init($url);
        if ($handle === false) {
            _l("Unable to create cURL request for %s.", $url);
            return null;
        }

        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

        // make request - on error log and return null
        $response = curl_exec($handle);
        if (!is_string($response)) {
            _l(print_r(curl_error($handle), true));
            return null;
        }

        // if type is JSON, decode and return
        if ($type === "json") {
            $json = json_decode($response);
            if (!$json) {
                _l("Unable to decode JSON response from %s.", $url);
                return null;
            }

            return $json;
        }

        // otherwise return as text
        return $response;
    }

    /**
     * Get Bible passage as JSON.
     *
     * @param string $passage                       The passage to retrieve - multiple passages should be separated by a semi-colon.
     * @return mixed                                If $passage is a valid Bible reference, the NET Bible text of that passage.
     */
    public function get_json(string $passage): mixed
    {
        return $this->get($passage, "json");
    }

    /**
     * Get Bible passage as (HTML-formatted) text.
     *
     * @param string $passage                       The passage to retrieve - multiple passages should be separated by a semi-colon.
     * @return ?string                              If $passage is a valid Bible reference, the NET Bible text of that passage.
     */
    public function get_text(string $passage): ?string
    {
        return $this->get($passage, "text");
    }
}
