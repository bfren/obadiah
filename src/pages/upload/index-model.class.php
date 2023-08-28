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
     * @param null|Result $result                       Operation result.
     * @param string $rota_period                       The period covered by this rota - year plus term (e.g. '23-2').
     * @param DateTimeImmutable $rota_period_first_day  The first day of the rota period.
     * @param DateTimeImmutable $rota_period_last_day   The last day of the rota period.
     * @param array $rota_files                         Array of uploaded rota files.
     * @param array $bible_files                        Array of uploaded Bible reading plan files.
     * @param string $church_suite_href                 Church Suite home page URI.
     * @param string $rota_href                         Church Suite rota download URI.
     */
    public function __construct(
        public readonly ?Result $result,
        public readonly string $rota_period,
        public readonly DateTimeImmutable $rota_period_first_day,
        public readonly DateTimeImmutable $rota_period_last_day,
        public readonly array $rota_files,
        public readonly array $bible_files,
        public readonly string $church_suite_href,
        public readonly string $rota_href
    ) {
    }
}
