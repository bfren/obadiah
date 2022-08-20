<?php

namespace Feeds\Rota;

use DateInterval;
use DateTimeImmutable;
use Feeds\Config\Config as C;

defined("IDX") || die("Nice try.");

class Service
{
    /**
     * Service date and time.
     *
     * @var DateTimeImmutable
     */
    public DateTimeImmutable $dt;

    /**
     * Service length.
     *
     * @var DateInterval
     */
    public DateInterval $length;

    /**
     * Service description, e.g. 'Morning Prayer'.
     *
     * @var string
     */
    public string $description;

    /**
     * The roles and people assigned to this service.
     *
     * @var array                       Associative array of roles, key = role, value = people assigned to that role.
     *      $roles = array(
     *          string role_name => string[] people
     *      )
     */
    public array $roles = array();

    /**
     * All the people in the current service.
     *
     * @var string[]
     */
    public array $people = array();

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

        // get the service time and length (as a DateInterval string)
        $time = match ($data["Service"]) {
            "Socially Distanced Service 9:00am" => array("9:00am", "PT30M"),
            "Sunday Morning Service 10:30am" => array("10:30am", "PT90M"),
            "Wednesday Morning Prayer 8:00am" => array("8:00am", "PT30M"),
            default => "0:00am"
        };

        // get the date as a timestamp
        $this->dt = DateTimeImmutable::createFromFormat(C::$formats->csv_import_datetime, $data["Date"] . $time[0], C::$events->timezone);
        $this->length = new DateInterval($time[1]);

        // get the service description
        $this->description = $this->get_description($data);

        // get the roles
        $this->roles = $this->get_roles($data);
    }

    /**
     * Get the service description from the note, or use the rota service name if not set.
     *
     * @param array $data               Associative array of service data.
     * @return string                   Service description.
     */
    private function get_description(array $data): string
    {
        // use Service Note as description, or the rota service name if it's not set
        $description = $data["Service Note"] ?: $data["Service"];

        // sanitise names to remove unnecessary information
        return match ($description) {
            "HC" => "Holy Communion",
            "Wednesday Morning Prayer 8:00am" => "Morning Prayer",
            default => $description
        };
    }

    /**
     * Get all supported roles and the people assigned to each one, and add to $this->roles.
     *
     * @param array $data               Associative array of service data.
     * @return array                    Associative array of roles
     */
    private function get_roles(array $data): array
    {
        // create empty roles array
        $roles = array();

        // any roles not listed here will not be added to the service
        $supported_roles = array(
            "Communion Assistants" => "",
            "Duty Warden" => "",
            "Intercessions" => "",
            "Prayer Ministry" => "",
            "Preacher" => "",
            "President" => "",
            "Readings" => "Reader",
            "Refreshments" => "",
            "Service Leader" => "Leader",
            "Socially Distanced Service Leader 22-2" => "Leader & Preacher",
            "Sound Desk" => "",
            "Wednesday Morning Prayer" => "Leader",
            "Welcome" => ""
        );

        foreach ($data as $rota_role => $people) {
            // skip if no-one is assigned
            if (!$people) {
                continue;
            }

            // add role if it is in the supported array
            foreach ($supported_roles as $supported_role => $override) {
                if (str_starts_with($rota_role, $supported_role)) {
                    $role = $override ?: $supported_role;
                    $sanitised = $this->sanitise_people($people);
                    $roles[$role] = $sanitised;
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

        // remove roles
        $without_roles = preg_replace("/ \(.*\)/", "", $without_clash);
        $this->people = array_unique(array_merge($this->people, $without_roles));
        asort($this->people);

        // return
        return $without_clash;
    }
}
