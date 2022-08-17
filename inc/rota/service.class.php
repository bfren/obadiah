<?php

namespace ChurchSuiteFeeds\Rota;

use DateTime;

class Service {
    /**
     * Service date and time, stored as a Unix timestap.
     *
     * @var int
     */
    public int $timestamp;

    /**
     * Service description, e.g. 'Morning Prayer'.
     *
     * @var string
     */
    public string $description;

    /**
     * The roles and people assigned to this service.
     *
     * @var string[]                    Associative array of roles, key = role, value = people assigned to that role.
     */
    public array $roles;

    /**
     * Construct a service object from an array of data.
     *
     * @param string[] $header_row      Array of rota data headings (e.g. 'Date', 'Preacher').
     * @param string[] $row             Array of data matching the headings.
     * @return void
     */
    public function __construct( $header_row, $row )
    {
        // read the data into an associative array using the header row
        $data = array();
        for ( $i = 0; $i < count($header_row); $i++ ) {
            $data[$header_row[$i]] = $row[$i];
        }

        // get the service time
        $time = match( $data["Service"] ) {
            "Sunday Morning Service 10:30am" => "10:30am",
            "Wednesday Morning Prayer 8:00am" => "8:00am",
            default => "0:00am"
        };

        // get the date as a timestamp
        $dt = DateTime::createFromFormat( "d-M-Yg:ia", $data["Date"] . $time );
        $this->timestamp = $dt->getTimestamp();

        // get the service description from the note, or use the rota service if not set
        $this->description = $data["Service Note"] ?: $data["Service"];

        // get the roles
        $this->read_roles( $data );
    }

    /**
     * Get all supported roles and the people assigned to each one, and add to $this->roles.
     *
     * @param array $data               Associative array of roles and people.
     * @return void
     */
    private function read_roles( $data )
    {
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
            "Sound Desk" => "",
            "Wednesday Morning Prayer" => "Leader",
            "Welcome" => ""
        );

        foreach ( $data as $rota_role => $people ) {
            // skip if no-one is assigned
            if ( ! $people ) {
                continue;
            }

            // add role if it is in the supported array
            foreach ( $supported_roles as $supported_role => $override ) {
                if ( str_starts_with( $rota_role, $supported_role ) ) {
                    $role = $override ?: $supported_role;
                    $sanitised = $this->sanitise_people( $people );
                    $this->roles[$role] = $sanitised;
                }
            }
        }
    }

    /**
     * Sanitise the input, removing various bits of unnecessary information provided by Church Suite.
     *
     * @param string $people            List of people assigned to this role (and other bits of information).
     * @return string[]                 Array of people's names.
     */
    private function sanitise_people( $people )
    {
        // remove any notes
        $sanitised = preg_replace( '/Notes:(.*)\n\n/s', "", $people );

        // split by new line
        $individuals = preg_split( '/\n/', trim( $sanitised ) );

        // remove clash indicators
        $without_clash = str_replace( "!! ", "", $individuals );

        // sort alphabetically
        sort( $without_clash );

        // return
        return $without_clash;
    }
}
