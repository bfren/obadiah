<?php

namespace Obadiah\Pages\Safeguarding;

use DateTimeImmutable;
use Obadiah\App;
use Obadiah\Baserow\Baserow;
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
            return new Json(array("error" => $result->content, "data" => $row), $result->status);
        }
    }

    /**
     * Retrieve JSON request object and post it to the Confidential Self-Declaration table in Baserow.
     *
     * @return Json                     JSON response to return to the client.
     */
    public function declaration_post(): Json
    {
        // receive JSON data from website
        $form = Request::$json;

        // map JSON to Baserow table fields
        $row = array(
            "Name" => Arr::get($form, "name-1"),
            "Role Applied For" => Arr::get($form, "text-1"),
            "1." => Arr::get($form, "select-1"),
            "1. Details" => Arr::get($form, "textarea-1"),
            "2." => Arr::get($form, "select-2"),
            "2. Details" => Arr::get($form, "textarea-2"),
            "3." => Arr::get($form, "select-3"),
            "3. Details" => Arr::get($form, "textarea-3"),
            "4." => Arr::get($form, "select-4"),
            "4. Details" => Arr::get($form, "textarea-4"),
            "5." => Arr::get($form, "select-5"),
            "6." => Arr::get($form, "select-6"),
            "6. Details" => Arr::get($form, "textarea-5"),
            "7." => Arr::get($form, "select-7"),
            "7. Details" => Arr::get($form, "textarea-6"),
            "8." => Arr::get($form, "select-8"),
            "8. Details" => Arr::get($form, "textarea-7"),
            "9." => Arr::get($form, "select-9"),
            "10." => Arr::get($form, "select-10"),
            "11." => Arr::get($form, "select-11"),
            "12." => Arr::get($form, "select-12"),
            "13." => Arr::get($form, "select-13"),
            "13. Details" => Arr::get($form, "textarea-8"),
            "14." => Arr::get($form, "select-14"),
            "14. Details" => Arr::get($form, "textarea-9"),
            "15." => Arr::get($form, "select-15"),
            "15. Details" => Arr::get($form, "textarea-10"),
            "16." => Arr::get($form, "select-16"),
            "16. Details" => Arr::get($form, "textarea-11"),
            "Declaration" => Arr::get($form, "name-2"),
            "Overseas Consent" => Arr::get($form, "name-3")
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
        $get_select = fn (int $num) => str_replace("-", " ", Arr::get($form, "select-$num"));

        // map JSON to Baserow table fields
        $row = array(
            "Applicant Full Name" => Arr::get($form, "name-1"),
            "Relationship" => Arr::get($form, "text-1"),
            "Known For" => Arr::get($form, "text-2"),
            "Suitability" => $get_select(1),
            "Experience" => $get_select(2),
            "Care" => $get_select(3),
            "Equality" => $get_select(4),
            "Honesty etc" => $get_select(5),
            "Comments" => Arr::get($form, "textarea-1"),
            "Health" => Arr::get($form, "select-6"),
            "Health Details" => Arr::get($form, "textarea-2"),
            "Unsuitability" => Arr::get($form, "select-7"),
            "Unsuitability Details" => Arr::get($form, "textarea-3"),
            "Referee Full Name" => Arr::get($form, "name-2"),
            "Confirm" => Arr::get($form, "checkbox-1") == "true",
            "Referee Email" => Arr::get($form, "email-1"),
            "Referee Phone" => Arr::get($form, "phone-1"),
        );

        // create Baserow connection and execute request
        $reference_table = Baserow::Reference();
        return self::execute($reference_table, $row);
    }
}
