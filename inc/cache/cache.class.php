<?php

namespace Feeds\Cache;

use Feeds\Lectionary\Lectionary;
use Feeds\Rota\Rota;

defined("IDX") || die("Nice try.");

class Cache
{
    /**
     * Create a new cache object.
     *
     * @param string $dir_path          Absolute path to cache data directory.
     * @param int $duration_in_seconds  Duration in seconds before cache entries expire.
     * @return void
     */
    public function __construct(
        public readonly string $dir_path,
        public readonly int $duration_in_seconds
    ) {
    }

    /**
     * Get rota from the cache (or generate a fresh copy).
     *
     * @param callable $callable        Callable function to generate a Rota if not set / expired.
     * @return Rota                     Rota value.
     */
    public function get_rota(callable $callable): Rota
    {
        return $this->get_or_set("rota", $callable);
    }

    /**
     * Get lectionary from the cache (or generate a fresh copy).
     *
     * @param callable $callable        Callable function to generate a Lectionary if not set / expired.
     * @return Lectionary               Lectionary value.
     */
    public function get_lectionary(callable $callable): Lectionary
    {
        return $this->get_or_set("lectionary", $callable);
    }

    /**
     * Get an item from the cache, or generate it if not set or expired.
     *
     * @param string $id                Cache file name.
     * @param callable $callable        Callable function to get cache value if expired or not set.
     * @return mixed                    Value (cached or generated).
     */
    private function get_or_set(string $id, callable $callable): mixed
    {
        // create path to cache file
        $file = "$this->dir_path/$id.cache";

        // if the file exists, and the cache file has not expired, read and unserialise the value
        if (file_exists($file) && time() - filemtime($file) < $this->duration_in_seconds) {
            return unserialize(file_get_contents($file));
        }

        // get a fresh value and serialise it to the cache
        $value = call_user_func($callable);
        file_put_contents($file, serialize($value));

        // return value
        return $value;
    }
}
