<?php

namespace Obadiah\Config;

use Obadiah\App;
use Obadiah\Helpers\Arr;

App::check();

class Config_Baserow extends Config_Section
{
    /**
     * API URI.
     *
     * @var string
     */
    public readonly string $api_uri;

    /**
     * The ID of the Safeguarding Concern table.
     *
     * @var int
     */
    public readonly int $concern_table_id;

    /**
     * The ID of the Day table.
     *
     * @var int
     */
    public readonly int $day_table_id;

    /**
     * The ID of the Day Feed view.
     *
     * @var int
     */
    public readonly int $day_view_id;

    /**
     * The ID of the Confidential Self-Declarations table.
     *
     * @var int
     */
    public readonly int $declaration_table_id;

    /**
     * The ID of the Confidential References table.
     *
     * @var int
     */
    public readonly int $reference_table_id;

    /**
     * The ID of the Service table.
     *
     * @var int
     */
    public readonly int $service_table_id;

    /**
     * The ID of the Day feed view.
     *
     * @var int
     */
    public readonly int $service_view_id;

    /**
     * Authorisation Token for Lectionary database.
     *
     * @var string
     */
    public readonly string $lectionary_token;

    /**
     * Authorisation Token for Safeguarding database.
     *
     * @var string
     */
    public readonly string $safeguarding_token;

    /**
     * Get values from Baserow configuration array.
     *
     * @param mixed[] $config           Baserow configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        # Baserow API
        $this->api_uri = Arr::get($config, "api_uri", "");

        # Rota Table and View IDs
        $this->day_table_id = Arr::get_integer($config, "day_table_id");
        $this->day_view_id = Arr::get_integer($config, "day_view_id");
        $this->service_table_id = Arr::get_integer($config, "service_table_id");
        $this->service_view_id = Arr::get_integer($config, "service_view_id");

        # Safeguarding Table IDs
        $this->concern_table_id = Arr::get_integer($config, "concern_table_id");
        $this->declaration_table_id = Arr::get_integer($config, "declaration_table_id");
        $this->reference_table_id = Arr::get_integer($config, "reference_table_id");

        # Tokens - Lectionary and Safer Recruitment information should not be stored in the same database
        $this->lectionary_token = Arr::get($config, "lectionary_token", "");
        $this->safeguarding_token = Arr::get($config, "safeguarding_token", "");
    }

    public function as_array(): array
    {
        return [
            "api_uri" => $this->api_uri,
            "concern_table_id" => $this->concern_table_id,
            "day_table_id" => $this->day_table_id,
            "day_view_id" => $this->day_view_id,
            "declaration_table_id" => $this->declaration_table_id,
            "lectionary_token" => $this->lectionary_token,
            "reference_table_id" => $this->reference_table_id,
            "safeguarding_token" => $this->safeguarding_token,
            "service_table_id" => $this->service_table_id,
            "service_view_id" => $this->service_view_id,
        ];
    }
}
