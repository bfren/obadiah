<?php

namespace Obadiah\Baserow;

use Obadiah\App;
use Obadiah\Config\Config as C;

App::check();

class Baserow
{
    /**
     * Create Baserow object for querying the Day table.
     *
     * @return Baserow                  Configured Baserow object.
     */
    public static function Day() : Baserow
    {
        return new Baserow(C::$baserow->day_table_id, C::$baserow->day_view_id);
    }

    /**
     * Create Baserow object for querying the Service table.
     *
     * @return Baserow                  Configured Baserow object.
     */
    public static function Service() : Baserow
    {
        return new Baserow(C::$baserow->service_table_id, C::$baserow->service_view_id);
    }

    /**
     * Constructed URL to the API for a specified table (see constructor).
     *
     * @var string
     */
    private readonly string $url;

    /**
     * Build URL to connect to the specified table and view.
     *
     * @param int $table_id             Table ID.
     * @param int $view_id              View ID.
     * @return void
     */
    public function __construct(int $table_id, int $view_id)
    {
        $this->url = sprintf("%s/database/rows/table/%s/?view_id=%s&user_field_names=true", C::$baserow->api_uri, $table_id, $view_id);
    }

    /**
     * Make a request to the Baserow API and return array of results.
     *
     * @param array $data               Optional request data.
     * @return array                    All results for the specified view.
     */
    public function make_request(array $data = array()): array
    {
        // build HTTP query from data
        $query = http_build_query($data);

        // create curl request
        $handle = curl_init(sprintf("%s&%s", $this->url, $query));
        curl_setopt($handle, CURLOPT_HTTPHEADER, array(sprintf("Authorization: Token %s", C::$baserow->token)));
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
            return sprintf("Error: %s", $result["detail"]);
        }

        // get records
        $results = $result["results"];

        // if there are more records to get, make another response using offset info,
        // and keep going recursively until all the records have been retrieved
        if (isset($result["next"])) {
            // get the URL query and parse into an array to use for the next query
            $next_data = array();
            parse_str(parse_url($result["next"], PHP_URL_QUERY), $next_data);

            // use the next page value to get the next batch of results
            $next_results = $this->make_request($next_data);

            // if $next_results is a string that means an error has occured so return it
            if (is_string($next_results)) {
                return $next_results;
            }

            // merge arrays
            $results = array_merge($results, $next_results);
        }

        // return the complete array of results
        return $results;
    }
}
