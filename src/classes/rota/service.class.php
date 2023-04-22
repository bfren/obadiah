<?php

namespace Feeds\Rota;

use DateInterval;
use DateTimeImmutable;
use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;

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
     * The roles and people assigned to this service.
     *
     * @var Service_Role[]              Array key is role name / description.
     */
    public readonly array $roles;

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

        // get the roles
        $this->roles = $this->get_roles($data);

        // get all the people involved in this service
        $people = array();
        foreach ($this->roles as $service_roles) {
            // remove extra information and merge arrays
            $people = array_merge(preg_replace("/ \(.*\)/", "", $service_roles->people), $people);
        }

        // sort alphabetically and remove duplicates
        asort($people);
        $this->people = array_unique($people);
    }

    /**
     * Get all supported roles and the people assigned to each one, and add to $this->roles.
     *
     * @param array $data               Associative array of service data.
     * @return Service_Role[]           Associative array of roles.
     */
    private function get_roles(array $data): array
    {
        // create empty roles array
        $roles = array();
        foreach ($data as $rota_role => $people) {
            // skip if no-one is assigned
            if (!$people) {
                continue;
            }

            // add role if it is in the supported array
            foreach (C::$rota->roles as $supported_role) {
                if (str_starts_with($rota_role, $supported_role->name)) {
                    $name = $supported_role->description ?: $supported_role->name;
                    $roles[$name] = new Service_Role($supported_role->abbreviation, $this->sanitise_people($people));
                }
            }
        }

        // sort and return roles
        ksort($roles);
        return $roles;
    }

    /**
     * Sanitise the input, removing various bits of unnecessary information provided by Church Suite.
     *
     * @param string $people            List of people assigned to this role (and other bits of information).
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
