<?php

namespace Feeds\Cache;

use Feeds\App;
use Feeds\Lectionary\Lectionary;
use Feeds\Prayer\Prayer_Calendar;
use Feeds\Rota\Rota;

App::check();

class Cache
{
    /**
     * Lectionary cache name.
     */
    public const LECTIONARY = "lectionary";

    /**
     * Prayer Calendar cache name.
     */
    public const PRAYER = "prayer";

    /**
     * Rota cache name.
     */
    public const ROTA = "rota";

    /**
     * Absolute path to cache data directory.
     *
     * @var string
     */
    private static string $dir_path;

    /**
     * Duration in seconds before cache entries expire.
     *
     * @var int
     */
    private static int $duration_in_seconds;

    /**
     * Store cache values.
     *
     * @param string $dir_path          Absolute path to cache data directory.
     * @param int $duration_in_seconds  Duration in seconds before cache entries expire.
     * @return void
     */
    public static function init(string $dir_path, int $duration_in_seconds)
    {
        self::$dir_path = $dir_path;
        self::$duration_in_seconds = $duration_in_seconds;
    }

    /**
     * Clear the lectionary cache.
     *
     * @return void
     */
    public static function clear_lectionary(): void
    {
        self::clear(self::LECTIONARY);
    }

    /**
     * Clear the prayer calendar cache.
     *
     * @return void
     */
    public static function clear_prayer_calendar(): void
    {
        self::clear(self::PRAYER);
    }

    /**
     * Clear the rota cache.
     *
     * @return void
     */
    public static function clear_rota(): void
    {
        self::clear(self::ROTA);
    }

    /**
     * Get lectionary from the cache (or generate a fresh copy).
     *
     * @param callable $callable        Callable function to generate a Lectionary if not set / expired.
     * @return Lectionary               Lectionary value.
     */
    public static function get_lectionary(callable $callable): Lectionary
    {
        return self::get_or_set(self::LECTIONARY, $callable);
    }

    /**
     * Get prayer calendar from the cache (or generate a fresh copy).
     *
     * @param callable $callable        Callable function to generate a Prayer Calendar if not set / expired.
     * @return Prayer_Calendar          Prayer Calendar value.
     */
    public static function get_prayer_calendar(callable $callable): Prayer_Calendar
    {
        return self::get_or_set(self::PRAYER, $callable);
    }

    /**
     * Get rota from the cache (or generate a fresh copy).
     *
     * @param callable $callable        Callable function to generate a Rota if not set / expired.
     * @return Rota                     Rota value.
     */
    public static function get_rota(callable $callable): Rota
    {
        return self::get_or_set(self::ROTA, $callable);
    }

    /**
     * Get absolute path to cache file.
     *
     * @param string $id                Cache ID.
     * @return string                   Absolute path to cache file.
     */
    public static function get_cache_file_path(string $id): string
    {
        return sprintf("%s/%s.cache", self::$dir_path, $id);
    }

    /**
     * Clear a cache.
     *
     * @param string $id                Cache file name.
     * @return void
     */
    private static function clear(string $id): void
    {
        // get path to cache file
        $file = self::get_cache_file_path($id);

        // delete the file if it exists
        file_exists($file) && unlink($file);
    }

    /**
     * Get an item from the cache, or generate it if not set or expired.
     *
     * @param string $id                Cache file name.
     * @param callable $callable        Callable function to get cache value if expired or not set.
     * @return mixed                    Value (cached or generated).
     */
    private static function get_or_set(string $id, callable $callable): mixed
    {
        // get path to cache file
        $file = self::get_cache_file_path($id);

        // if the file exists, and the cache file has not expired, read and unserialise the value
        if (file_exists($file) && time() - filemtime($file) < self::$duration_in_seconds) {
            return unserialize(file_get_contents($file));
        }

        // get a fresh value and serialise it to the cache
        $value = call_user_func($callable);
        file_put_contents($file, serialize($value));

        // return value
        return $value;
    }
}
