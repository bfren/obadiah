<?php

namespace Feeds\Pages\Upload;

use DateTimeImmutable;
use Feeds\Admin\Bible_File;
use Feeds\Admin\Prayer_File;
use Feeds\Admin\Result;
use Feeds\Admin\Rota_File;
use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Request\Request;
use Feeds\Response\View;

App::check();

class Upload
{
    /**
     * Church Suite home page URI.
     */
    private const CHURCH_SUITE_HREF = "https://%s.churchsuite.com";

    /**
     * Church Suite rota download URI.
     */
    private const ROTA_HREF = "https://%s.churchsuite.com/modules/rotas/reports/rotas_overview.php?%s";

    /**
     * Church Suite address book download URI.
     */
    private const ADULTS_HREF = "https://%s.churchsuite.com/modules/addressbook/reports/contact_table_generator.php?%s";

    /**
     * Church Suite children download URI.
     */
    private const CHILDREN_HREF = "https://%s.churchsuite.com/modules/children/reports/child_table_generator.php?%s";

    /**
     * Operation result.
     *
     * @var null|Result
     */
    private ?Result $result = null;

    /**
     * GET: /upload
     *
     * @return View
     */
    public function index_get(): View
    {
        // get uploaded files and sort by name
        $rota_files = array_slice(scandir(C::$dir->rota), 2);
        sort($rota_files);

        $prayer_files = array_map("basename", glob(sprintf("%s/*.csv", C::$dir->prayer), 2));
        sort($prayer_files);

        $bible_files = array_slice(scandir(C::$dir->bible), 2);
        sort($bible_files);

        // calculate the current four-month period of the year
        $month = date("n"); // month number without leading zeroes
        $rota_period = ceil($month / 4);
        $last_month = $rota_period * 4;
        $first_month = $last_month - 3;

        $rota_period_first_day = DateTimeImmutable::createFromFormat("Y-m-d", sprintf("%s-%s-%s", date("Y"), $first_month, 1));
        $rota_period_last_day = DateTimeImmutable::createFromFormat("Y-m-d", sprintf("%s-%s-%s", date("Y"), $last_month, 1))->modify("last day of this month");

        // build Church Suite queries
        $rota_query = http_build_query(array(
            "_module" => "ChurchSuite\Rotas",
            "_report_name" => "rotas_overview",
            "_report_view_module" => "rotas",
            "_report_view_file" => "rotas_overview",
            "order_by" => "name",
            "group_by" => "time",
            "date_start" => $rota_period_first_day->format("Y-m-d"),
            "date_end" => $rota_period_last_day->format("Y-m-d")
        ));

        $prayer_adults_query = http_build_query(array(
            "_module" => "ChurchSuite\AddressBook",
            "_report_name" => "contact_table_generator",
            "_report_view_module" => "addressbook",
            "_report_view_file" => "contact_table_generator",
            "columns" => array("first_name", "last_name"),
            "status" => "active",
            "tags" => array(30)
        ));

        $prayer_children_query = http_build_query(array(
            "_module" => "ChurchSuite\Children",
            "_report_name" => "child_table_generator",
            "_report_view_module" => "children",
            "_report_view_file" => "child_table_generator",
            "columns" => array("first_name", "last_name"),
            "status" => "active",
            "tags" => array(31)
        ));

        // return View
        return new View("upload", model: new Index_Model(
            result: $this->result,
            rota_period: sprintf("%s-%s", date("y"), $rota_period),
            rota_period_first_day: $rota_period_first_day,
            rota_period_last_day: $rota_period_last_day,
            rota_files: $rota_files,
            prayer_files: $prayer_files,
            bible_files: $bible_files,
            church_suite_href: sprintf(self::CHURCH_SUITE_HREF, C::$churchsuite->org),
            rota_href: sprintf(self::ROTA_HREF, C::$churchsuite->org, $rota_query),
            prayer_adults_href: sprintf(self::ADULTS_HREF, C::$churchsuite->org, $prayer_adults_query),
            prayer_children_href: sprintf(self::CHILDREN_HREF, C::$churchsuite->org, $prayer_children_query)
        ));
    }

    /**
     * POST: /upload
     *
     * @return View
     */
    public function index_post(): View
    {
        // save files
        $this->result = match (Request::$post->string("submit")) {
            "bible" => Bible_File::upload(),
            "prayer-adults" => Prayer_File::upload_adults(),
            "prayer-children" => Prayer_File::upload_children(),
            "rota" => Rota_File::upload(),
            default => Result::failure("Unknown action.")
        };

        // show upload page
        return $this->index_get();
    }

    /**
     * GET: /upload/delete_rota
     *
     * @return View
     */
    public function delete_rota_get(): View
    {
        // get file and delete
        if ($file = Request::$get->string("file")) {
            $this->result = Rota_File::delete($file);
        }

        // return index page
        return $this->index_get();
    }

    /**
     * GET: /upload/delete_prayer
     *
     * @return View
     */
    public function delete_prayer_get(): View
    {
        // get file and delete
        if ($file = Request::$get->string("file")) {
            $this->result = Prayer_File::delete($file);
        }

        // return index page
        return $this->index_get();
    }

    /**
     * GET: /upload/delete_bible
     *
     * @return View
     */
    public function delete_bible_get(): View
    {
        // delete file
        $this->result = Bible_File::delete();

        // return index page
        return $this->index_get();
    }
}
