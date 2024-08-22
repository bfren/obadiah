<?php

namespace Obadiah\Rota;

use DateTimeImmutable;
use Obadiah\App;
use Obadiah\Config\Config as C;

App::check();

class Service
{
    /**
     * Service start date and time.
     *
     * @var DateTimeImmutable
     */
    public readonly DateTimeImmutable $start;

    /**
     * The ministries and people assigned to this service.
     *
     * @var Service_Ministry[]              Array key is ministry name / description.
     */
    public readonly array $ministries;

    /**
     * All the people in the current service.
     *
     * @var string[]
     */
    public readonly array $people;

    /**
     * Construct a service object from an array of data.
     *
     * @param string[] $header_row      Array of rota data headings (e.g. 'Date', 'Preacher').
     * @param string[] $row             Array of data matching the headings.
     * @return void
     */
    public function __construct(array $header_row, array $row)
    {
        // read the data into an associative array using the header row
        $data = array();
        for ($i = 0; $i < count($header_row); $i++) {
            $data[$header_row[$i]] = $row[$i];
        }
        // get the start date and time as a timestamp
        $this->start = DateTimeImmutable::createFromFormat(C::$formats->csv_import_datetime, sprintf("%s%s", $data["Date"], $data["Time"]), C::$events->timezone);

        // get the ministries
        $this->ministries = $this->get_ministries($data);

        // get all the people involved in this service
        $people = array();
        foreach ($this->ministries as $service_ministries) {
            // remove extra information and merge arrays
            $people = array_merge(preg_replace("/ \(.*\)/", "", $service_ministries->people), $people);
        }

        // sort alphabetically and remove duplicates
        asort($people);
        $this->people = array_unique($people);
    }

    /**
     * Get all supported ministries and the people assigned to each one, and add to $this->ministries.
     *
     * @param array $data               Associative array of service data.
     * @return Service_Ministry[]       Associative array of ministries.
     */
    private function get_ministries(array $data): array
    {
        // create empty ministries array
        $ministries = array();
        foreach ($data as $rota_ministry => $people) {
            // skip if no-one is assigned
            if (!$people) {
                continue;
            }

            // add ministry if it is in the supported array
            foreach (C::$rota->ministries as $supported_ministry) {
                if (str_starts_with($rota_ministry, $supported_ministry->name)) {
                    $name = $supported_ministry->description ?: $supported_ministry->name;
                    $ministries[$name] = new Service_Ministry($supported_ministry->abbreviation, $this->sanitise_people($people));
                }
            }
        }

        // sort and return ministries
        ksort($ministries);
        return $ministries;
    }

    /**
     * Sanitise the input, removing various bits of unnecessary information provided by Church Suite.
     *
     * @param string $people            List of people assigned to this ministry (and other bits of information).
     * @return string[]                 Array of people's names.
     */
    private function sanitise_people(string $people): array
    {
        // remove any notes
        $sanitised = preg_replace("/Notes:(.*)\n\n/s", "", $people);

        // split by new line
        $individuals = preg_split("/\n/", trim($sanitised));

        // remove clash indicators
        $without_clash = str_replace(array("!! ", "** "), "", $individuals);

        // sort alphabetically
        sort($without_clash);

        // return
        return $without_clash;
    }
}
