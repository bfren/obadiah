<?php

namespace Obadiah\Baserow;

use Obadiah\App;
use Obadiah\Config\Config as C;

App::check();

class Baserow
{
    /**
     * Create Baserow object for querying the Concern table.
     *
     * @return Baserow                              Configured Baserow object.
     */
    public static function Concern(): Baserow
    {
        return new Baserow(C::$baserow->safeguarding_token, C::$baserow->concern_table_id);
    }

    /**
     * Create Baserow object for querying the Day table.
     *
     * @return Baserow                              Configured Baserow object.
     */
    public static function Day(): Baserow
    {
        return new Baserow(C::$baserow->lectionary_token, C::$baserow->day_table_id, C::$baserow->day_view_id);
    }

    /**
     * Create Baserow object for inserting into the Confidential Self-Declaration table.
     *
     * @return Baserow                              Configured Baserow object.
     */
    public static function Declaration(): Baserow
    {
        return new Baserow(C::$baserow->safeguarding_token, C::$baserow->declaration_table_id);
    }

    /**
     * Create Baserow object for inserting into the Confidential Reference table.
     *
     * @return Baserow                              Configured Baserow object.
     */
    public static function Reference(): Baserow
    {
        return new Baserow(C::$baserow->safeguarding_token, C::$baserow->reference_table_id);
    }

    /**
     * Create Baserow object for querying the Service table.
     *
     * @return Baserow                              Configured Baserow object.
     */
    public static function Service(): Baserow
    {
        return new Baserow(C::$baserow->lectionary_token, C::$baserow->service_table_id, C::$baserow->service_view_id);
    }

    /**
     * Token to access the required database.
     *
     * @var string
     */
    private readonly string $token;

    /**
     * Constructed URL to the API for a specified table (see constructor).
     *
     * @var string
     */
    private readonly string $url;

    /**
     * Build URL to connect to the specified table (and view for querying).
     *
     * @param string $token                         Database Token.
     * @param int $table_id                         Table ID.
     * @param ?int $view_id                         Optional View ID.
     * @return void
     */
    public function __construct(string $token, int $table_id, ?int $view_id = null)
    {
        $this->token = $token;

        if ($view_id == null) {
            $this->url = sprintf("%s/database/rows/table/%s/?&user_field_names=true", C::$baserow->api_uri, $table_id);
        } else {
            $this->url = sprintf("%s/database/rows/table/%s/?view_id=%s&user_field_names=true", C::$baserow->api_uri, $table_id, $view_id);
        }
    }

    /**
     * Make a GET request to the Baserow API and return array of results.
     *
     * @param mixed[] $data                         Optional request data.
     * @return mixed[]                              All results for the specified view, an error message, or null on failure.
     */
    public function get(array $data = []): array
    {
        // build HTTP query from data
        $query = http_build_query($data);

        // create curl request
        $handle = curl_init(sprintf("%s&%s", $this->url, $query));
        if ($handle === false) {
            _l("Unable to create cURL request for %s.", $this->url);
            return [];
        }

        curl_setopt($handle, CURLOPT_HTTPHEADER, array(sprintf("Authorization: Token %s", $this->token)));
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

        // make request and return empty array on error
        $json = curl_exec($handle);
        if (!is_string($json)) {
            _l(curl_error($handle));
            return [];
        }

        // decode JSON response and return empty array on error
        $result = json_decode($json, true);
        if (isset($result["error"])) {
            _l("Error: %s", $result["detail"]);
            return [];
        }

        // get records
        $results = $result["results"];

        // if there are more records to get, make another response using offset info,
        // and keep going recursively until all the records have been retrieved
        if (isset($result["next"])) {
            // get the URL query and parse into an array to use for the next query
            $next_url = parse_url($result["next"], PHP_URL_QUERY);
            $next_data = [];
            if ($next_url) {
                parse_str($next_url, $next_data);
            }

            // use the next page value to get the next batch of results
            $next_results = $this->get($next_data);

            // if $next_results is an empty array that means there are no more results or an error has occured so return
            if (count($next_results) == 0) {
                return $next_results;
            }

            // merge arrays
            $results = array_merge($results, $next_results);
        }

        // return the complete array of results
        return $results;
    }

    /**
     * Make a POST request to the Baserow API.
     *
     * @param mixed[] $data                         Request data.
     * @return Post_Result                          POST request result.
     */
    public function post(array $data): Post_Result
    {
        // build HTTP form from data
        $form = http_build_query($data);

        // create curl request
        $handle = curl_init($this->url);
        if ($handle === false) {
            _l("Unable to create cURL request for %s.", $this->url);
            return new Post_Result(500, "Error, please try again.");
        }

        curl_setopt($handle, CURLOPT_HTTPHEADER, array(
            sprintf("Authorization: Token %s", $this->token)
        ));
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $form);

        // make request and output on error
        $json = curl_exec($handle);
        if (!is_string($json)) {
            _l(curl_error($handle));
            return new Post_Result(500, "Error, please try again.");
        }

        // decode JSON response and output on error
        $result = json_decode($json, true);
        if (isset($result["error"])) {
            return new Post_Result(400, $result["detail"]);
        }

        // if we get here the POST was successful
        return new Post_Result(200, "OK");
    }
}
