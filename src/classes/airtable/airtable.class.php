<?php

namespace Feeds\Airtable;

use Feeds\App;
use Feeds\Config\Config as C;

App::check();

class Airtable
{
    /**
     * Constructed URL to the API for a specified table (see constructor).
     *
     * @var string
     */
    private readonly string $url;

    /**
     * Build URL to connect to the specified table.
     *
     * @param string $table             Table name.
     * @return void
     */
    public function __construct(string $table)
    {
        $this->url = sprintf("https://api.airtable.com/v0/%s/%s", C::$airtable->base, $table);
    }

    /**
     * Make a request to the Airtable API and return array of records.
     *
     * @param array $data               Request data.
     * @return array                    All records for the specified view.
     */
    public function make_request(array $data): array
    {
        // build HTTP query from data
        $query = http_build_query($data);

        // create curl request
        $handle = curl_init(sprintf("%s?%s", $this->url, $query));
        curl_setopt($handle, CURLOPT_HTTPHEADER, array(sprintf("Authorization: Bearer %s", C::$airtable->personal_access_token)));
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, 1);

        // make request and output on error
        $json = curl_exec($handle);
        if (!$json) {
            print_r(curl_error($handle));
            return null;
        }

        // decode JSON response and output on error
        $result = json_decode($json, true);
        if (isset($result["error"])) {
            return sprintf("Error: %s", $result["error"]["message"]);
        }

        // get records
        $records = $result["records"];

        // if there are more records to get, make another response using offset info,
        // and keep going recursively until all the records have been retrieved
        if (!isset($query["maxrecords"]) && isset($result["offset"])) {
            // use the offset value to get the next batch of records
            $next_data = array_merge($data, array("offset" => $result["offset"]));
            $next_records = $this->make_request($next_data);

            // if $next_records is a string that means an error has occured so return it
            if (is_string($next_records)) {
                return $next_records;
            }

            // merge arrays
            $records = array_merge($records, $next_records);
        }

        // return the complete array of records
        return $records;
    }
}
