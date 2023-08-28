<?php

namespace Feeds\ChurchSuite;

use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Helpers\Hash;
use Feeds\Prayer\Person;
use Feeds\Prayer\Prayer_Calendar;

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
     * Make a request to the ChurchSuite API and return the response - logging any errors.
     *
     * @param string $endpoint          ChurchSuite API endpoint.
     * @param array $data               Request data.
     * @return mixed                    API response.
     */
    private function make_request(string $endpoint, array $data): mixed
    {
        // build URL from data
        $url = sprintf("https://api.churchsuite.com/%s/%s?%s", $this->version, $endpoint, http_build_query($data));

        // create curl request
        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_HTTPHEADER, array(
            sprintf("X-Account: %s", C::$churchsuite->org),
            sprintf("X-Application: %s", C::$churchsuite->api_application),
            sprintf("X-Auth: %s", C::$churchsuite->api_key)
        ));
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);

        // make request - on error log and return null
        $json = curl_exec($handle);
        if (!$json) {
            _l(print_r(curl_error($handle), true));
            return null;
        }

        // decode JSON response - on error log and return null
        $result = json_decode($json, true);
        if (!$result) {
            _l("Unable to decode JSON response from %s", $url);
            return null;
        } elseif (isset($result["error"])) {
            _l("Error retrieving %s: %s", $url, $result["error"]["message"]);
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
        $response = $this->make_request($endpoint, array($kind => "true"));
        if ($response === null) {
            return array();
        }

        // build array of People from the response
        $people = array();
        foreach ($response[$kind] as $person) {
            $person = new Person($person["first_name"], $person["last_name"], $are_children);
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
