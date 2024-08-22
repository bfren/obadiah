<?php

namespace Obadiah\Pages\Safeguarding;

use DateTimeImmutable;
use Obadiah\App;
use Obadiah\Baserow\Baserow;
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
            "Name" => $form["name-1"],
            "Role Applied For" => $form["text-1"],
            "1." => $form["select-1"],
            "1. Details" => $form["textarea-1"],
            "2." => $form["select-2"],
            "2. Details" => $form["textarea-2"],
            "3." => $form["select-3"],
            "3. Details" => $form["textarea-3"],
            "4." => $form["select-4"],
            "4. Details" => $form["textarea-4"],
            "5." => $form["select-5"],
            "6." => $form["select-6"],
            "6. Details" => $form["textarea-5"],
            "7." => $form["select-7"],
            "7. Details" => $form["textarea-6"],
            "8." => $form["select-8"],
            "8. Details" => $form["textarea-7"],
            "9." => $form["select-9"],
            "10." => $form["select-10"],
            "11." => $form["select-11"],
            "12." => $form["select-12"],
            "13." => $form["select-13"],
            "13. Details" => $form["textarea-8"],
            "14." => $form["select-14"],
            "14. Details" => $form["textarea-9"],
            "15." => $form["select-15"],
            "15. Details" => $form["textarea-10"],
            "16." => $form["select-16"],
            "16. Details" => $form["textarea-11"],
            "Declaration" => $form["name-2"],
            "Overseas Consent" => $form["name-3"]
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
        $get_select = fn (int $num) => str_replace("-", " ", $form["select-$num"]);

        // map JSON to Baserow table fields
        $row = array(
            "Applicant Full Name" => $form["name-1"],
            "Relationship" => $form["text-1"],
            "Known For" => $form["text-2"],
            "Suitability" => $get_select(1),
            "Experience" => $get_select(2),
            "Care" => $get_select(3),
            "Equality" => $get_select(4),
            "Honesty etc" => $get_select(5),
            "Comments" => $form["textarea-1"],
            "Health" => $form["select-6"],
            "Health Details" => $form["textarea-2"],
            "Unsuitability" => $form["select-7"],
            "Unsuitability Details" => $form["textarea-3"],
            "Referee Full Name" => $form["name-2"],
            "Confirm" => $form["checkbox-1"] == "true",
            "Referee Email" => $form["email-1"],
            "Referee Phone" => $form["phone-1"],
        );

        // create Baserow connection and execute request
        $reference_table = Baserow::Reference();
        return self::execute($reference_table, $row);
    }
}
