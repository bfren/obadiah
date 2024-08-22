<?php

namespace Obadiah\Pages\Auth;

use Obadiah\App;
use Obadiah\Config\Config as C;
use Obadiah\Request\Request;
use Obadiah\Response\Action;
use Obadiah\Response\View;
use Obadiah\Response\Redirect;

App::check();

class Auth
{
    /**
     * GET: /auth/login
     *
     * @return View
     */
    public function login_get(): Action
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
            $uri = Request::$get->string("requested", default: "/");
            return new Redirect($uri);
        }

        // deny access
        Request::$session->deny();

        // redirect to login page
        return new Redirect(sprintf("/auth/login", true));
    }

    /**
     * GET: /auth/logout
     *
     * @return Redirect
     */
    public function logout_get() : Redirect
    {
        // log the user out
        Request::$session->logout();

        // redirect to login page
        return new Redirect(sprintf("/auth/login", true));
    }
}
