<?php

namespace Obadiah\Pages\Upload;

use DateInterval;
use DateTimeImmutable;
use Obadiah\Admin\Bible_File;
use Obadiah\Admin\Prayer_File;
use Obadiah\Admin\Result;
use Obadiah\Admin\Rota_File;
use Obadiah\App;
use Obadiah\Config\Config as C;
use Obadiah\Request\Request;
use Obadiah\Response\View;

App::check();

class Upload
{
    /**
     * Church Suite home page URI.
     */
    private const CHURCH_SUITE_HREF = "https://%s.churchsuite.com";

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

        $bible_files = array_slice(scandir(C::$dir->bible), 2);
        sort($bible_files);

        // calculate the current four-month period of the year
        $month = date("n"); // month number without leading zeroes
        $rota_period = ceil($month / 4);
        $last_month = $rota_period * 4;
        $first_month = $last_month - 3;
        $rota_period_first_day = DateTimeImmutable::createFromFormat("Y-m-d", sprintf("%s-%s-%s", date("Y"), $first_month, 1));
        $rota_period_last_day = DateTimeImmutable::createFromFormat("Y-m-d", sprintf("%s-%s-%s", date("Y"), $last_month, 1))->modify("last day of this month");

        // calculate the next four-month period
        $next_period_first_day = $rota_period_last_day->add(new DateInterval("P1D"));
        $next_period_last_day = $next_period_first_day->add(new DateInterval("P3M"))->modify("last day of this month");

        // return View
        return new View("upload", model: new Index_Model(
            result: $this->result,
            rota: Rota_Period::create($rota_period_first_day, $rota_period_last_day),
            next_rota: Rota_Period::create($next_period_first_day, $next_period_last_day),
            rota_files: $rota_files,
            bible_files: $bible_files,
            church_suite_href: sprintf(self::CHURCH_SUITE_HREF, C::$churchsuite->org)
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
