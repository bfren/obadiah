<?php

namespace Feeds\Pages\Error;

use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Request\Request;
use Feeds\Response\Response;
use Feeds\Response\Html;

App::check();

class Login
{
    public function index(): Html
    {
        // handle post requests
        if (Request::$method == "POST") {
            // get username and password
            $user = Request::$post->string("username");
            $pass = Request::$post->string("password");

            // check password and redirect to home if it is correct
            // if it is not unset auth variable and increment count
            if ($user == "user" && $pass == C::$login->pass) {
                Request::$session->authorise();
                $redirect = Request::$get->string("requested", default: "/");
                Response::redirect($redirect);
            } elseif ($user == "admin" && $pass == C::$login->admin) {
                Request::$session->authorise(true);
                Response::redirect("/upload");
            } else {
                Request::$session->deny();
            }
        }

        // if already authorised that means an api key has been used so redirect
        if (Request::$session->is_authorised) {
            Request::$session->authorise();
            $redirect = Request::$get->string("requested", default: "/");
            Response::redirect($redirect);

            return;
        }

        // check login attempts to stop people trying over and over to guess the password
        Request::$session->login_attempts < C::$login->max_attempts || App::die("You're done - try again later.");

        return new Html("login");
    }
}
