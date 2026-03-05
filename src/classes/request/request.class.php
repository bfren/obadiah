<?php

namespace Obadiah\Request;

use Obadiah\App;
use Obadiah\Crypto\Crypto;
use Obadiah\Helpers\Arr;
use Obadiah\Helpers\IO;

App::check();

class Request
{
    /**
     * Encapsulates $_COOKIES.
     *
     * @var Super_Global
     */
    public static Super_Global $cookies;

    /**
     * Encapsulates $_ENV.
     *
     * @var Super_Global
     */
    public static Super_Global $env;

    /**
     * Encapsulates $_FILES.
     *
     * @var array<string, File>
     */
    public static array $files;

    /**
     * Encapsulates $_GET.
     *
     * @var Super_Global
     */
    public static Super_Global $get;

    /**
     * Encapsulates $_POST.
     *
     * @var Super_Global
     */
    public static Super_Global $post;

    /**
     * Encapsulates $_SERVER.
     *
     * @var Super_Global
     */
    public static Super_Global $server;

    /**
     * Encapsulates $_SESSION.
     *
     * @var Session
     */
    public static Session $session;

    /**
     * True if the request is marked with a debug flag.
     *
     * @var bool
     */
    public static bool $debug;

    /**
     * Current request host.
     *
     * @var string
     */
    public static string $host;

    /**
     * Returns posted JSON as an associative array.
     *
     * @var array<string, mixed>
     */
    public static array $json;

    /**
     * Request method (e.g. POST).
     *
     * @var string
     */
    public static string $method;

    /**
     * Nonce for this request.
     *
     * @var string
     */
    public static string $nonce;

    /**
     * Current request protocol ('HTTP' or 'HTTPS').
     *
     * @var string
     */
    public static string $protocol;

    /**
     * Request query string.
     *
     * @var string
     */
    public static string $query_string;

    /**
     * Request URI path.
     *
     * @var string
     */
    public static string $uri;

    /**
     * Set request values.
     *
     * @return void
     */
    public static function init(): void
    {
        self::$cookies = new Super_Global(INPUT_COOKIE);
        self::$env = new Super_Global(INPUT_ENV);
        self::$files = Arr::map($_FILES, fn($f) => File::create($f));
        self::$get = new Super_Global(INPUT_GET);
        self::$json = json_decode(IO::php_input(), true) ?: [];
        self::$post = new Super_Global(INPUT_POST);
        self::$server = new Super_Global(INPUT_SERVER);
        self::$session = new Session();

        self::$debug = self::$get->bool("debug");
        self::$method = self::$server->string("REQUEST_METHOD");
        self::$host = self::$server->string("HTTP_HOST");
        self::$nonce = Crypto::generate(16);
        self::$protocol = self::$server->bool("HTTPS", false) ? "https" : "http";
        self::$query_string = self::$server->string("QUERY_STRING");
        self::$uri = str_replace(sprintf("?%s", self::$query_string), "", self::$server->string("REQUEST_URI"));
    }
}
