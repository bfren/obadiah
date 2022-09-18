<?php

namespace Feeds\Cache;

use Feeds\App;
use Feeds\Bible\Bible_Plan;
use Feeds\Calendar\JEvent;
use Feeds\Lectionary\Lectionary;
use Feeds\Prayer\Prayer_Calendar;
use Feeds\Refresh\Refresh;
use Feeds\Rota\Rota;
use SplFileInfo;

App::check();

class Cache
{
    /**
     * Bible Plan cache name.
     */
    public const BIBLE_PLAN = "bible";

    /**
     * Church Suite events cache.
     */
    public const EVENTS = "events";

    /**
     * Lectionary cache name.
     */
    public const LECTIONARY = "lectionary";

    /**
     * Prayer Calendar cache name.
     */
    public const PRAYER = "prayer";

    /**
     * Refresh cache name.
     */
    public const REFRESH = "refresh";

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
     * Clear the Bible Plan cache.
     *
     * @return void
     */
    public static function clear_bible_plan(): void
    {
        self::clear(self::BIBLE_PLAN);
    }

    /**
     * Clear the Events cache.
     *
     * @return void
     */
    public static function clear_events(): void
    {
        self::clear(self::EVENTS);
    }

    /**
     * Clear the Lectionary cache.
     *
     * @return void
     */
    public static function clear_lectionary(): void
    {
        self::clear(self::LECTIONARY);
    }

    /**
     * Clear the Prayer Calendar cache.
     *
     * @return void
     */
    public static function clear_prayer_calendar(): void
    {
        self::clear(self::PRAYER);
    }

    /**
     * Clear the Refresh cache.
     *
     * @return void
     */
    public static function clear_refresh(): void
    {
        self::clear(self::REFRESH);
    }

    /**
     * Clear the Rota cache.
     *
     * @return void
     */
    public static function clear_rota(): void
    {
        self::clear(self::ROTA);
    }

    /**
     * Get Bible Plan from the cache (or generate a fresh copy).
     *
     * @param bool $force               If true, $callable will be used whether or not the cache entry has expired.
     * @return Bible_Plan               Bible Plan object.
     */
    public static function get_bible_plan(bool $force = false): Bible_Plan
    {
        return self::get_or_set(self::BIBLE_PLAN, fn () => new Bible_Plan(), $force);
    }

    /**
     * Get Events from the cache (or generate a fresh copy).
     *
     * @param callable $callable        Callable function to generate an array of events if not set / expired.
     * @param bool $force               If true, $callable will be used whether or not the cache entry has expired.
     * @return JEvent[]                 Event objects.
     */
    public static function get_events(callable $callable, bool $force = false): array
    {
        return self::get_or_set(self::EVENTS, $callable, $force);
    }

    /**
     * Get Lectionary from the cache (or generate a fresh copy).
     *
     * @param bool $force               If true, $callable will be used whether or not the cache entry has expired.
     * @return Lectionary               Lectionary object.
     */
    public static function get_lectionary(bool $force = false): Lectionary
    {
        return self::get_or_set(self::LECTIONARY, fn () => new Lectionary(), $force);
    }

    /**
     * Get Prayer calendar from the cache (or generate a fresh copy).
     *
     * @param bool $force               If true, $callable will be used whether or not the cache entry has expired.
     * @return Prayer_Calendar          Prayer Calendar object.
     */
    public static function get_prayer_calendar(bool $force = false): Prayer_Calendar
    {
        return self::get_or_set(self::PRAYER, fn () => new Prayer_Calendar(), $force);
    }

    /**
     * Get Refresh calendar from the cache (or generate a fresh copy).
     *
     * @param bool $force               If true, $callable will be used whether or not the cache entry has expired.
     * @return Refresh                  Refresh object.
     */
    public static function get_refresh(bool $force = false): Refresh
    {
        return self::get_or_set(self::REFRESH, fn () => new Refresh(), $force);
    }

    /**
     * Get Rota from the cache (or generate a fresh copy).
     *
     * @param bool $force               If true, $callable will be used whether or not the cache entry has expired.
     * @return Rota                     Rota object.
     */
    public static function get_rota(bool $force = false): Rota
    {
        return self::get_or_set(self::ROTA, fn () => new Rota(), $force);
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
        $path = self::get_cache_file_path($id);

        // delete the file if it exists
        $file = new SplFileInfo($path);
        $file->isFile() && unlink($file->getRealPath());
    }

    /**
     * Get an item from the cache, or generate it if not set or expired.
     *
     * @param string $id                Cache file name.
     * @param callable $callable        Callable function to get cache value if expired or not set.
     * @param bool $force               If true, $callable will be used whether or not the cache entry has expired.
     * @return mixed                    Value (cached or generated).
     */
    private static function get_or_set(string $id, callable $callable, bool $force = false): mixed
    {
        // clear cache if $force is set
        if ($force) {
            self::clear($id);
        }

        // get path to cache file
        $path = self::get_cache_file_path($id);

        // if the file exists, and the cache file has not expired, read and unserialise the value
        $file = new SplFileInfo($path);
        if ($file->isFile() && time() - $file->getMTime() < self::$duration_in_seconds) {
            return unserialize(file_get_contents($file));
        }

        // get a fresh value and serialise it to the cache
        $value = call_user_func($callable);
        file_put_contents($file, serialize($value));

        // return value
        return $value;
    }
}
