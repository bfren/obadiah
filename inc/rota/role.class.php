<?php

namespace ChurchSuiteFeeds\Rota;

class Role {

    /**
     * Role name.
     *
     * @var string
     */
    public string $name;

    /**
     * People assigned to this role.
     *
     * @var string[]
     */
    public array $people;

    /**
     * Create a new role from the provided name and people.
     *
     * @param string $name              The name of the role.
     * @param string $people            The people assigned to this role.
     * @return void
     */
    public function __construct( $name, $people )
    {
        $this->name = $name;
        $this->people = $this->sanitise( $people );
    }

    /**
     * Sanitise the input, removing various bits of unnecessary information provided by Church Suite.
     *
     * @param string $people            List of people assigned to this role (and other bits of information).
     * @return string[]                 Sanitised array of people.
     */
    private function sanitise( $people )
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
