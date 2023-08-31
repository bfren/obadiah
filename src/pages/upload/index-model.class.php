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
     * @param null|Result $result       Operation result.
     * @param Rota_Period $rota         The first day of the rota period.
     * @param Rota_Period $next_rota    The last day of the rota period.
     * @param array $rota_files         Array of uploaded rota files.
     * @param array $bible_files        Array of uploaded Bible reading plan files.
     * @param string $church_suite_href Church Suite home page URI.
     */
    public function __construct(
        public readonly ?Result $result,
        public readonly Rota_Period $rota,
        public readonly Rota_Period $next_rota,
        public readonly array $rota_files,
        public readonly array $bible_files,
        public readonly string $church_suite_href,
    ) {
    }
}
