<?php

namespace Feeds\Rota;

use DateTime;
use DateTimeZone;
use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;
use Feeds\Lectionary\Lectionary;
use Feeds\Rota\Service;

class Builder
{
    /**
     * Build a rota of matching services, combining rota and lectionary information.
     *
     * @param Lectionary $lectionary    Lectionary object.
     * @param Service[] $services       Services matching the current filters.
     * @return Combined_Day[]           Array of objects combining rota and lectionary service info.
     */
    public function build_combined_rota(Lectionary $lectionary, array $services): array
    {
        // create an empty array to hold the combined rota
        $rota = array();

        foreach ($lectionary->days as $day) {
            // look for any services on this day
            $rota_services = array_filter($services, function ($service) use ($day) {
                return date(C::$formats->sortable_date, $service->timestamp) == $day->date;
            });

            // if there are no services, continue
            if (!$rota_services) {
                continue;
            }

            // add the day to the rota
            $c_day = new Combined_Day();
            $c_day->dt = DateTime::createFromFormat(C::$formats->sortable_date, $day->date, new DateTimeZone("Europe/London"))->setTime(0, 0);
            $c_day->name = $day->name;
            $c_day->services = array();

            // add all the services
            foreach ($rota_services as $rota_service) {
                // add rota information
                $c_service = new Combined_Service();
                $c_service->timestamp = $rota_service->timestamp;
                $c_service->time = date(C::$formats->display_time, $rota_service->timestamp);
                $c_service->name = $rota_service->description;
                $c_service->roles = $rota_service->roles;

                // get lectionary information
                $lectionary_service = $day->get_service($rota_service->timestamp);
                if ($lectionary_service) {
                    $c_service->series_title = $lectionary_service->series;
                    $c_service->sermon_num = $lectionary_service->num;
                    $c_service->sermon_title = $lectionary_service->title;
                    $c_service->main_reading = $lectionary_service->main_reading;
                    $c_service->additional_reading = $lectionary_service->additional_reading;
                }

                // add service to the rota
                $c_day->services[] = $c_service;
            }

            // add the day to the rota
            $rota[$day->date] = $c_day;
        }

        // return built rota
        return $rota;
    }
}
