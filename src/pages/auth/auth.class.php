<?php

namespace Obadiah\Pages\Auth;

use Obadiah\App;
use Obadiah\Config\Config as C;
use Obadiah\Crypto\Crypto;
use Obadiah\Request\Request;
use Obadiah\Response\Action;
use Obadiah\Response\View;
use Obadiah\Response\Redirect;
use Obadiah\Router\Endpoint;

App::check();

class Auth extends Endpoint
{
    /**
     * GET: /auth/login
     *
     * @return Action
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
     * @return Action
     */
    public function login_post() : Action
    {
        // get username and password
        $user = Request::$post->string("username");
        $pass = Request::$post->string("password");

        // check user / admin passwords
        if ($user == "user" && Crypto::verify_password(C::$login->pass, $pass)) {
            Request::$session->authorise();
            $uri = Request::$get->string("requested", default: "/");
            return new Redirect($uri);
        } elseif ($user == "admin" && Crypto::verify_password(C::$login->admin, $pass)) {
            Request::$session->authorise(true);
            $uri = Request::$get->string("requested", default: "/");
            return new Redirect($uri);
        }

        // deny access
        Request::$session->deny();

        // redirect to login page
        return new Redirect("/auth/login", true);
    }

    /**
     * GET: /auth/logout
     *
     * @return Action
     */
    public function logout_get() : Action
    {
        // log the user out
        Request::$session->logout();

        // redirect to login page
        return new Redirect("/auth/login", true);
    }
}
