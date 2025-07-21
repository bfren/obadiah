<?php

namespace Obadiah\Pages\Upload;

use DateTimeImmutable;
use Obadiah\App;
use Obadiah\Config\Config as C;

App::check();

class Rota_Period
{
    /**
     * Church Suite rota download URI.
     */
    private const ROTA_HREF = "https://%s.churchsuite.com/modules/rotas/reports/rotas_overview.php?%s";

    /**
     * Create Rota Period model.
     *
     * @param string $ref                   The period covered by this rota - year plus term (e.g. '23-2').
     * @param DateTimeImmutable $first_day  The first day of the rota period.
     * @param DateTimeImmutable $last_day   The last day of the rota period.
     * @param string $href                  Next Church Suite rota download URI.
     */
    public function __construct(
        public readonly string $ref,
        public readonly DateTimeImmutable $first_day,
        public readonly DateTimeImmutable $last_day,
        public readonly string $href
    ) {}

    public static function create(DateTimeImmutable $first_day, DateTimeImmutable $last_day): Rota_Period
    {
        $period = ceil($first_day->format("n") / 4);
        $ref = sprintf("%s-%s", $first_day->format("y"), $period);

        $query_values = array(
            "_module" => "ChurchSuite\Rotas",
            "_report_name" => "rotas_overview",
            "_report_view_module" => "rotas",
            "order_by" => "name",
            "group_by" => "time",
            "date_start" => $first_day->format("Y-m-d"),
            "date_end" => $last_day->format("Y-m-d")
        );
        $href = sprintf(self::ROTA_HREF, C::$churchsuite->org, http_build_query($query_values));

        return new Rota_Period($ref, $first_day, $last_day, $href);
    }
}
