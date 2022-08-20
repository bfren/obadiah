<?php

namespace Feeds\Rota;

use DateTime;
use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;
use Feeds\Lectionary\Lectionary;
use Feeds\Rota\Service;

class Builder
{
    /**
     * Array of days of the week, starting with Sunday, numbered to match DateTime format 'N'
     *
     * @var array
     */
    public static array $days_of_the_week = array(
        7 => "Sunday",
        1 => "Monday",
        2 => "Tuesday",
        3 => "Wednesday",
        4 => "Thursday",
        5 => "Friday",
        6 => "Saturday",
    );

    /**
     * Build a rota of matching services, combining rota and lectionary information.
     *
     * @param Lectionary $lectionary    Lectionary object.
     * @param Service[] $services       Services matching the current filters.
     * @return Combined_Day[]           Array of objects combining rota and lectionary service info.
     */
    public static function build_combined_rota(Lectionary $lectionary, array $services): array
    {
        // create an empty array to hold the combined rota
        $rota = array();

        foreach ($lectionary->days as $day) {
            // look for any services on this day
            $rota_services = array_filter($services, function ($service) use ($day) {
                return $service->dt->format(C::$formats->sortable_date) == $day->date;
            });

            // if there are no services, continue
            if (!$rota_services) {
                continue;
            }

            // add the day to the rota
            $c_day = new Combined_Day();
            $c_day->dt = DateTime::createFromFormat(C::$formats->sortable_date, $day->date, C::$events->timezone)->setTime(0, 0);
            $c_day->name = $day->name;
            $c_day->services = array();

            // add all the services
            foreach ($rota_services as $rota_service) {
                // add rota information
                $c_service = new Combined_Service();
                $c_service->dt = $rota_service->dt;
                $c_service->time = $rota_service->dt->format(C::$formats->display_time);
                $c_service->name = $rota_service->description;
                $c_service->roles = $rota_service->roles;

                // get lectionary information
                $lectionary_service = $day->get_service($rota_service->dt);
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

    /**
     * Get the name of the specified day of the week.
     *
     * @param int $num                  Day number.
     * @return string
     */
    public static function get_day(int $num): string
    {
        return Arr::get(self::$days_of_the_week, $num);
    }

    /**
     * Generate a unique ID for a service.
     *
     * @param Combined_Service $service     Service object.
     * @return string                       Unique hashed ID.
     */
    public static function get_uuid(Combined_Service $service): string
    {
        return md5($service->dt->format("c") . $service->name);
    }

    /**
     * Generate an event summary for a service, including role indicators for the specified person.
     *
     * @param Combined_Service $service     Service object.
     * @param string $person                Selected person.
     * @return string                       Service name with role indicators.
     */
    public static function get_summary(Combined_Service $service, string $person): string
    {
        // use the name as the basic summary
        $summary = $service->name;

        // if no person is set, return the summary
        if (!$person) {
            return $summary;
        }

        // look for certain roles
        $roles = array();
        foreach ($service->roles as $role => $people) {
            foreach ($people as $p) {
                if (str_starts_with($p, $person)) {
                    $roles[] = match ($role) {
                        "Duty Warden" => "W",
                        "Intercessions" => "Py",
                        "Lead Musician" => "M",
                        "Leader" => "L",
                        "Preacher" => "Pr",
                        "President" => "Ps",
                        default => null
                    };
                }
            }
        }

        // if there are no roles, return the summary
        $roles = array_filter($roles);
        if (!$roles) {
            return $summary;
        }

        // sort roles and add to summary
        sort($roles);
        return sprintf("%s (%s)", $summary, join(", ", $roles));
    }

    /**
     * Generate an event description for a service, including lectionary / teaching info and roles.
     *
     * @param Combined_Day $day             Lectionary day information.
     * @param Combined_Service $service     Service object.
     * @return string                       Event description.
     */
    public static function get_description(Combined_Day $day, Combined_Service $service): string
    {
        // create empty array for description lines
        $description = array();

        // add lectionary info
        if ($day->name) {
            $description[] = "= Liturgical Day =";
            $description[] = $day->name;
            $description[] = "";
        }

        // add teaching
        if ($service->series_title || $service->sermon_title || $service->main_reading) {
            $description[] = "= Teaching =";

            // series title
            if ($service->series_title) {
                $title = $service->series_title;
                if ($service->sermon_num) {
                    $title = sprintf("%s (%s)", $title, $service->sermon_num);
                }
                $description[] = $title;
            }

            // sermon title
            if ($service->sermon_title) {
                $description[] = sprintf("Title: %s", $service->sermon_title);
            }

            // main reading
            if ($service->main_reading) {
                $description[] = sprintf("Main Reading: %s", $service->main_reading);
            }

            // additional reading
            if ($service->additional_reading) {
                $description[] = sprintf("Additional Reading: %s", $service->additional_reading);
            }

            $description[] = "";
        }

        // add roles
        if ($service->roles) {
            $description[] = "= Roles =";
            foreach ($service->roles as $role => $people) {
                $description[] = sprintf("%s: %s", $role, join(", ", $people));
            }
        }

        // return description
        return join("\\n", $description);
    }
}
