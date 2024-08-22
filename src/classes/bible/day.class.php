<?php

namespace Obadiah\Bible;

use Obadiah\App;

App::check();

class Day
{
    /**
     * Create Bible Plan Day object.
     *
     * @param string $ot_psalms         The day's psalm(s).
     * @param string $ot_1              The day's first Old Testament reading (Torah and history).
     * @param string $ot_2              The day's second Old Testament reading (history, wisdom, prophets).
     * @param string $nt_gospels        The day's gospel reading.
     * @param string $nt_epistles       The day's epistle.
     * @return void
     */
    public function __construct(
        public readonly string $ot_psalms,
        public readonly string $ot_1,
        public readonly string $ot_2,
        public readonly string $nt_gospels,
        public readonly string $nt_epistles
    ) {
    }
}
