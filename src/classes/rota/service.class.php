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
     * Service length.
     *
     * @var DateInterval
     */
    public readonly DateInterval $length;

    /**
     * Service description, e.g. 'Morning Prayer'.
     *
     * @var string
     */
    public readonly string $description;

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

        // get the service name, time and length
        $pattern = "/([^\d]+)(\d{1,2}:\d{2}[a|p]m)/";
        $matches = array();
        if (preg_match_all($pattern, $data["Service"], $matches) && count($matches) == 3) {
            $name = trim($matches[1][0]);
            $time = trim($matches[2][0]);
        } else {
            $name = "Unrecognised Service";
            $time = "0:00am";
        }

        $length = Arr::get(C::$rota->services, $name, C::$rota->default_length);

        // get the date as a timestamp
        $this->start = DateTimeImmutable::createFromFormat(C::$formats->csv_import_datetime, sprintf("%s%s", $data["Date"], $time), C::$events->timezone);
        $this->length = $length;

        // get the service description
        $this->description = $this->get_description($this->get_note($data), $name);

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
     * Get the service note, or the last role note if any roles have one.
     *
     * @param array $data               Service data.
     * @return ?string                  Service or role note, if any can be found.
     */
    private function get_note($data): ?string
    {
        // Service Note takes precedence
        $service_note = $data["Service Note"];
        if ($service_note) {
            return $service_note;
        }

        // Look for any role notes
        $role_notes = Arr::match($data, fn ($v) => preg_match("/Notes:(.+)/s", $v));
        $last_note = array_pop($role_notes);
        if ($last_note) {
            return trim(preg_replace("/Notes:\n([\w ]+)\n.*/s", "$1",  $last_note));
        }

        return null;
    }

    /**
     * Get the service description from the note, or use the rota service name if not set.
     *
     * @param string|null $note         A note can be added to a service to provide more information.
     * @param string $name              The rota service name (e.g. 'Sunday Morning Service').
     * @return string                   Service description.
     */
    private function get_description(?string $note, string $name): string
    {
        // use note as description, or the rota service name if it's not set
        $description = $note ?: $name;

        // sanitise names to remove unnecessary information
        return match ($description) {
            "HC" => "Holy Communion",
            "Wednesday Morning Prayer" => "Morning Prayer",
            default => $description
        };
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
