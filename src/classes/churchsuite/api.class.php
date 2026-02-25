<?php

namespace Obadiah\ChurchSuite;

use Obadiah\App;
use Obadiah\Config\Config as C;
use Obadiah\Helpers\Arr;
use Obadiah\Helpers\Hash;
use Obadiah\Prayer\Person;
use Obadiah\Prayer\Prayer_Calendar;

App::check();

class Api
{
    /**
     * ChurchSuite API version.
     *
     * @var string
     */
    private readonly string $version;

    /**
     * Set the version to use for all API requests.
     *
     * @return void
     */
    public function __construct()
    {
        $this->version = "v1";
    }

    /**
     * Execute a CURL request with retry logic and exponential backoff.
     *
     * @param resource $handle                      CURL handle.
     * @param int $max_retries                      Maximum number of retries.
     * @return string|bool                          Response string or false on failure.
     */
    private function execute_with_retry($handle, int $max_retries = 3): string|bool
    {
        for ($attempt = 0; $attempt <= $max_retries; $attempt++) {
            $response = curl_exec($handle);
            $http_code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

            // Success on 2xx responses
            if (is_string($response) && $http_code >= 200 && $http_code < 300) {
                return $response;
            }

            // Don't retry on 4xx errors (client errors)
            if ($http_code >= 400 && $http_code < 500) {
                return $response;
            }

            // Retry on network errors or 5xx errors
            if ($attempt < $max_retries) {
                $wait_time = pow(2, $attempt); // 1s, 2s, 4s, 8s
                _l("Retry attempt %d for ChurchSuite API (waiting %ds).", $attempt + 1, $wait_time);
                usleep($wait_time * 1000000); // Convert to microseconds
            }
        }

        return false;
    }

    /**
     * Make a request to the ChurchSuite API and return the response - logging any errors.
     *
     * @param string $endpoint          ChurchSuite API endpoint.
     * @param mixed[] $data             Request data.
     * @return mixed                    API response.
     */
    private function get(string $endpoint, array $data): mixed
    {
        // build URL from data
        $url = sprintf("https://api.churchsuite.com/%s/%s?%s", $this->version, $endpoint, http_build_query($data));

        // create curl request
        $handle = curl_init($url);
        if ($handle === false) {
            _l("Unable to create cURL request for %s.", $url);
            return null;
        }

        curl_setopt($handle, CURLOPT_HTTPHEADER, array(
            sprintf("X-Account: %s", C::$churchsuite->org),
            sprintf("X-Application: %s", C::$churchsuite->api_application),
            sprintf("X-Auth: %s", C::$churchsuite->api_key)
        ));
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($handle, CURLOPT_TCP_KEEPALIVE, 1);

        // make request with retry logic - on error log and return null
        $json = $this->execute_with_retry($handle);
        if (!is_string($json)) {
            _l(print_r(curl_error($handle), true));
            curl_close($handle);
            return null;
        }
        curl_close($handle);

        // decode JSON response - on error log and return null
        $result = json_decode($json, true);
        if (!$result) {
            _l("Unable to decode JSON response from %s.", $url);
            return null;
        } elseif (isset($result["error"])) {
            _l("Error retrieving %s: %s.", $url, $result["error"]["message"]);
            return null;
        }

        return $result;
    }

    /**
     * Make a request to the ChurchSuite API to get people.
     *
     * @param string $endpoint          API endpoint (e.g. 'addressbook/contacts').
     * @param string $kind              The kind of people being requested ('contacts' or 'children').
     * @param bool $are_children        Whether or not the people being requested are children.
     * @return Person[]                 Array of Person objects where the key is a unique hash (see Hash::person).
     */
    private function get_people(string $endpoint, string $kind, bool $are_children): array
    {
        // make request and return empty array on failure
        $response = $this->get($endpoint, array($kind => "true"));
        if ($response === null) {
            return [];
        }

        // build array of People from the response
        $people = [];
        foreach ($response[$kind] as $person) {
            $thumb_url = Arr::get(Arr::get(Arr::get($person, "images", []), "md", []), "url");
            $person = new Person(
                first_name: $person["first_name"],
                last_name: $person["last_name"],
                is_child: $are_children,
                image_url: $thumb_url
            );
            $people[Hash::person($person)] = $person;
        }

        // return - the list is returned sorted by ChurchSuite
        return $people;
    }

    /**
     * Get everyone who has consented to being in the Prayer Calendar.
     *
     * @return Person[]                 Array of Person objects where the key is a unique hash (see Hash::person).
     */
    public static function get_prayer_calendar_people(): array
    {
        // create API object
        $api = new Api();

        // get adults and children with the Prayer Calendar tag
        $contacts = $api->get_people(sprintf("addressbook/tag/%s", C::$churchsuite->tag_id_adults), "contacts", false);
        $children = $api->get_people(sprintf("children/tag/%s", C::$churchsuite->tag_id_children), "children", true);

        // merge and sort array
        $people = array_merge($contacts, $children);
        Prayer_Calendar::sort_people($people);

        return $people;
    }
}
