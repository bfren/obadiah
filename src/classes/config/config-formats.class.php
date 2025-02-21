<?php

namespace Obadiah\Config;

use Obadiah\App;
use Obadiah\Helpers\Arr;

App::check();

class Config_Formats extends Config_Section
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
     * Display day and month.
     *
     * @var string
     */
    public readonly string $display_day_and_month;

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
     * @param mixed[] $config           Formats configuration array.
     * @return void
     */
    public function __construct(array $config)
    {
        $this->csv_import_datetime = Arr::get($config, "csv_import_datetime", "d-M-Yg:ia");
        $this->display_date = Arr::get($config, "display_date", "D j M Y");
        $this->display_day = Arr::get($config, "display_day", "D jS");
        $this->display_day_and_month = Arr::get($config, "display_day_and_month", "j F");
        $this->display_month = Arr::get($config, "display_month", "F Y");
        $this->display_time = Arr::get($config, "display_time", "H:i");
        $this->ics_date = Arr::get($config, "ics_date", "Ymd");
        $this->ics_datetime = Arr::get($config, "ics_datetime", "Ymd\THis");
        $this->json_datetime = Arr::get($config, "json_datetime", "Y-m-d\TH:i:sO");
        $this->prayer_month_id = Arr::get($config, "prayer_month_id", "Y-m");
        $this->refresh_date = Arr::get($config, "refresh_date", "d/n");
        $this->sortable_date = Arr::get($config, "sortable_date", "Y-m-d");
        $this->sortable_datetime = Arr::get($config, "sortable_datetime", "Y-m-d H:i");
    }

    public function as_array(): array
    {
        return [
            "csv_import_datetime" => $this->csv_import_datetime,
            "display_date" => $this->display_date,
            "display_day_and_month" => $this->display_day_and_month,
            "display_day" => $this->display_day,
            "display_month" => $this->display_month,
            "display_time" => $this->display_time,
            "ics_date" => $this->ics_date,
            "ics_datetime" => $this->ics_datetime,
            "json_datetime" => $this->json_datetime,
            "prayer_month_id" => $this->prayer_month_id,
            "refresh_date" => $this->refresh_date,
            "sortable_date" => $this->sortable_date,
            "sortable_datetime" => $this->sortable_datetime,
        ];
    }
}
