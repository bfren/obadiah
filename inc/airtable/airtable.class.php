<?php

namespace Feeds\Airtable;

use Feeds\Base;

class Airtable
{
    /**
     * Airtable Base ref.
     */
    private const BASE = "appTweAghmB40WEbS";

    /**
     * Airtable API key.
     *
     * @var string
     */
    private string $key;

    /**
     * Constructed URL to the API for a specified table (see constructor).
     *
     * @var string
     */
    private string $url;

    /**
     * Connect to the Airtable API for the specified table.
     *
     * @param Base $base                Base config.
     * @param string $table             Table name.
     * @return void
     */
    public function __construct(Base $base, string $table)
    {
        $this->key = $base->airtable_api_key;
        $this->url = sprintf("https://api.airtable.com/v0/%s/%s", self::BASE, $table);
    }

    /**
     * Make a request to the Airtable API and return array of records.
     *
     * @param array $data               Request data.
     * @return array                    All records for the specified view.
     */
    public function make_request(array $data)
    {
        // build HTTP query from data
        $query = http_build_query($data);

        // create curl request
        $ch = curl_init("$this->url?$query");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer $this->key"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // make request and output on error
        $json = curl_exec($ch);
        if (!$json) {
            echo curl_error($ch);
            return null;
        }

        // decode JSON response and output on error
        $result = json_decode($json, true);
        if (isset($result["error"])) {
            return "Error: " . $result["error"]["message"];
        }

        // get records
        $records = $result["records"];

        // if there are more records to get, make another response using offset info,
        // and keep going recursively until all the records have been retrieved
        if (!isset($query["maxrecords"]) && isset($result["offset"])) {
            // use the offset value to get the next batch of records
            $query["offset"] = $result["offset"];
            $next_records = $this->make_request($query);

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
