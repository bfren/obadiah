<?php

namespace Feeds\Pages\Upload;

use DateTimeImmutable;
use Feeds\Admin\Result;
use Feeds\App;

App::check();

class Index_Model
{
    /**
     * Create Index model.
     *
     * @param null|Result $result           Operation result.
     * @param array $rota_files             Array of uploaded rota files.
     * @param array $prayer_files           Array of uploaded prayer calendar files.
     * @param array $bible_files            Array of uploaded Bible reading plan files.
     * @param string $church_suite_href     Church Suite home page URI.
     * @param string $rota_href             Church Suite rota download URI.
     * @param string $prayer_adults_href    Church Suite address book download URI.
     * @param string $prayer_children_href  Church Suite children download URI.
     */
    public function __construct(
        public readonly ?Result $result,
        public readonly string $rota_period,
        public readonly DateTimeImmutable $rota_period_first_day,
        public readonly DateTimeImmutable $rota_period_last_day,
        public readonly array $rota_files,
        public readonly array $prayer_files,
        public readonly array $bible_files,
        public readonly string $church_suite_href,
        public readonly string $rota_href,
        public readonly string $prayer_adults_href,
        public readonly string $prayer_children_href
    ) {
    }
}
