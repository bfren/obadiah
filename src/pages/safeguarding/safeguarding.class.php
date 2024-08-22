<?php

namespace Obadiah\Pages\Safeguarding;

use DateTimeImmutable;
use Obadiah\App;
use Obadiah\Baserow\Baserow;
use Obadiah\Config\Config as C;
use Obadiah\Helpers\Arr;
use Obadiah\Request\Request;
use Obadiah\Response\Json;

App::check();

class Safeguarding
{
    /**
     * Execute a POST request on the provided table.
     *
     * @param Baserow $table            Baserow table object.
     * @param array $row                Array of row data to POST.
     * @return Json                     JSON response to return to the client.
     */
    private static function execute(Baserow $table, array $row): Json
    {
        // execute the POST request
        $result = $table->post($row);

        // return Json result
        if ($result->status == 200) {
            return new Json($result->content);
        } else {
            _l("Unable to execute Baserow insert: %s.", json_encode($result->content));
            return new Json(array("error" => $result->content, "data" => $row), $result->status);
        }
    }

    /**
     * Retrieve JSON request object and post it to the Safeguarding Concern table in Baserow.
     *
     * @return Json                     JSON response to return to the client.
     */
    public function concern_post(): Json
    {
        // receive JSON data from website
        $form = Request::$json;

        // parse date/time
        $dt_string = trim(sprintf("%s %s", Arr::get($form, "date_1"), Arr::get($form, "time_1")));
        $dt = DateTimeImmutable::createFromFormat("d/m/Y H:i", $dt_string, C::$events->timezone);
        if ($dt === false) {
            _l("Unable to parse date '%s'.", $dt_string);
            return new Json(array("error" => sprintf("Unable to parse date: %s.", $dt_string)), 400);
        }

        // map JSON to Baserow table fields
        $row = array(
            "Name" => Arr::get($form, "text_1"),
            "Contact Details" => Arr::get($form, "text_5"),
            "Activity" => Arr::get($form, "text_2"),
            "Who" => Arr::get($form, "text_3"),
            "Date of Activity / Concern" => $dt->format("c"),
            "Details" => Arr::get($form, "textarea_1"),
            "Reported" => Arr::get($form, "radio_1"),
            "Reported To" => Arr::get($form, "text_4"),
            "Action" => Arr::get($form, "textarea_2"),
        );

        // create Baserow connection and execute request
        $concern_table = Baserow::Concern();
        return self::execute($concern_table, $row);
    }

    /**
     * Retrieve JSON request object and post it to the Confidential Self_Declaration table in Baserow.
     *
     * @return Json                     JSON response to return to the client.
     */
    public function declaration_post(): Json
    {
        // receive JSON data from website
        $form = Request::$json;

        // map JSON to Baserow table fields
        $row = array(
            "Name" => Arr::get($form, "name_1"),
            "Role Applied For" => Arr::get($form, "text_1"),
            "1." => Arr::get($form, "select_1"),
            "1. Details" => Arr::get($form, "textarea_1"),
            "2." => Arr::get($form, "select_2"),
            "2. Details" => Arr::get($form, "textarea_2"),
            "3." => Arr::get($form, "select_3"),
            "3. Details" => Arr::get($form, "textarea_3"),
            "4." => Arr::get($form, "select_4"),
            "4. Details" => Arr::get($form, "textarea_4"),
            "5." => Arr::get($form, "select_5"),
            "6." => Arr::get($form, "select_6"),
            "6. Details" => Arr::get($form, "textarea_5"),
            "7." => Arr::get($form, "select_7"),
            "7. Details" => Arr::get($form, "textarea_6"),
            "8." => Arr::get($form, "select_8"),
            "8. Details" => Arr::get($form, "textarea_7"),
            "9." => Arr::get($form, "select_9"),
            "10." => Arr::get($form, "select_10"),
            "11." => Arr::get($form, "select_11"),
            "12." => Arr::get($form, "select_12"),
            "13." => Arr::get($form, "select_13"),
            "13. Details" => Arr::get($form, "textarea_8"),
            "14." => Arr::get($form, "select_14"),
            "14. Details" => Arr::get($form, "textarea_9"),
            "15." => Arr::get($form, "select_15"),
            "15. Details" => Arr::get($form, "textarea_10"),
            "16." => Arr::get($form, "select_16"),
            "16. Details" => Arr::get($form, "textarea_11"),
            "Declaration" => Arr::get($form, "name_2"),
            "Overseas Consent" => Arr::get($form, "name_3"),
        );

        // create Baserow connection and execute request
        $declaration_table = Baserow::Declaration();
        return self::execute($declaration_table, $row);
    }

    /**
     * Retrieve JSON request object and post it to the Confidential Reference table in Baserow.
     *
     * @return Json                     JSON response to return to the client.
     */
    public function reference_post(): Json
    {
        // receive JSON data from website
        $form = Request::$json;

        // remove hyphens from select values
        $get_select = fn(int $num) => str_replace("-", " ", Arr::get($form, "select_$num"));

        // map JSON to Baserow table fields
        $row = array(
            "Applicant Full Name" => Arr::get($form, "name_1"),
            "Relationship" => Arr::get($form, "text_1"),
            "Known For" => Arr::get($form, "text_2"),
            "Suitability" => $get_select(1),
            "Experience" => $get_select(2),
            "Care" => $get_select(3),
            "Equality" => $get_select(4),
            "Honesty etc" => $get_select(5),
            "Comments" => Arr::get($form, "textarea_1"),
            "Health" => Arr::get($form, "select_6"),
            "Health Details" => Arr::get($form, "textarea_2"),
            "Unsuitability" => Arr::get($form, "select_7"),
            "Unsuitability Details" => Arr::get($form, "textarea_3"),
            "Referee Full Name" => Arr::get($form, "name_2"),
            "Confirm" => Arr::get($form, "checkbox_1") == "true",
            "Referee Email" => Arr::get($form, "email_1"),
            "Referee Phone" => Arr::get($form, "phone_1"),
        );

        // create Baserow connection and execute request
        $reference_table = Baserow::Reference();
        return self::execute($reference_table, $row);
    }
}
