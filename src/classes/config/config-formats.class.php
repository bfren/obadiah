<?php

namespace Feeds\Config;

use Feeds\App;
use Feeds\Helpers\Arr;

App::check();

class Config_Formats
{
    /**
     * Church Suite CSV import date time.
     *
     * @var string
     */
    public readonly string $csv_import_datetime;

    /**
     * Display date.
     *
     * @var string
     */
    public readonly string $display_date;

    /**
     * Display day.
     *
     * @var string
     */
    public readonly string $display_day;

    /**
     * Display month.
     *
     * @var string
     */
    public readonly string $display_month;

    /**
     * Display time.
     *
     * @var string
     */
    public readonly string $display_time;

    /**
     * ICS date.
     *
     * @var string
     */
    public readonly string $ics_date;

    /**
     * ICS date time.
     *
     * @var string
     */
    public readonly string $ics_datetime;

    /**
     * Prayer Calendar month ID.
     *
     * @var string
     */
    public readonly string $prayer_month_id;

    /**
     * JSON date time.
     *
     * @var string
     */
    public readonly string $json_datetime;

    /**
     * Refresh event summary date.
     *
     * @var string
     */
    public readonly string $refresh_date;

    /**
     * Sortable date.
     *
     * @var string
     */
    public readonly string $sortable_date;

    /**
     * Sortable date time.
     *
     * @var string
     */
    public readonly string $sortable_datetime;

    /**
     * Get values from formats configuration array.
     *
     * @param array $config             Formats configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->csv_import_datetime = Arr::get($config, "csv_import_datetime");
        $this->display_date = Arr::get($config, "display_date");
        $this->display_day = Arr::get($config, "display_day");
        $this->display_month = Arr::get($config, "display_month");
        $this->display_time = Arr::get($config, "display_time");
        $this->ics_date = Arr::get($config, "ics_date");
        $this->ics_datetime = Arr::get($config, "ics_datetime");
        $this->json_datetime = Arr::get($config, "json_datetime");
        $this->refresh_date = Arr::get($config, "refresh_date");
        $this->prayer_month_id = Arr::get($config, "prayer_month_id");
        $this->sortable_date = Arr::get($config, "sortable_date");
        $this->sortable_datetime = Arr::get($config, "sortable_datetime");
    }
}
