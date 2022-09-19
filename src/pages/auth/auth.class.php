<?php

namespace Feeds\Pages\Auth;

use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Request\Request;
use Feeds\Response\View;
use Feeds\Response\Redirect;

App::check();

class Auth
{
    /**
     * GET: /auth/login
     *
     * @return View
     */
    public function login_get(): View
    {
        // if already authorised that means an api key has been used so redirect
        if (Request::$session->is_authorised) {
            Request::$session->authorise();
            $uri = Request::$get->string("requested", default: "/");
            return new Redirect($uri);
        }

        // check login attempts to stop people trying over and over to guess the password
        Request::$session->login_attempts < C::$login->max_attempts || App::die("You're done - try again later.");

        // return login html
        return new View("auth", "login");
    }

    /**
     * POST: /auth/login
     *
     * @return Redirect
     */
    public function login_post() : Redirect
    {
        // get username and password
        $user = Request::$post->string("username");
        $pass = Request::$post->string("password");

        // check user / admin passwords
        if ($user == "user" && $pass == C::$login->pass) {
            Request::$session->authorise();
            $uri = Request::$get->string("requested", default: "/");
            return new Redirect($uri);
        } elseif ($user == "admin" && $pass == C::$login->admin) {
            Request::$session->authorise(true);
            $uri = Request::$get->string("requested", default: "/upload");
            return new Redirect($uri);
        }

        // deny access
        Request::$session->deny();

        // redirect to login GET page
        return new Redirect("/auth/login");
    }

    /**
     * GET: /auth/logout
     *
     * @return Redirect
     */
    public function logout_get() : Redirect
    {
        // clear session
        session_start();
        session_destroy();

        // redirect to home page
        return new Redirect("/");
    }
}
