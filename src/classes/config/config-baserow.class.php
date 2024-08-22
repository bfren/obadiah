<?php

namespace Obadiah\Config;

use Obadiah\App;
use Obadiah\Helpers\Arr;

App::check();

class Config_Baserow
{
    /**
     * API URI.
     *
     * @var string
     */
    public readonly string $api_uri;

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
     * Authorisation Token.
     *
     * @var string
     */
    public readonly string $token;

    /**
     * Get values from Baserow configuration array.
     *
     * @param array $config             Baserow configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->api_uri = Arr::get($config, "api_uri", "");
        $this->day_table_id = Arr::get_integer($config, "day_table_id");
        $this->day_view_id = Arr::get_integer($config, "day_view_id");
        $this->service_table_id = Arr::get_integer($config, "service_table_id");
        $this->service_view_id = Arr::get_integer($config, "service_view_id");
        $this->token = Arr::get($config, "token", "");
    }
}
