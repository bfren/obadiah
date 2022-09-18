<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Request\Request;
use Feeds\Response\Response;

App::check();

// handle post requests
if (Request::$method == "POST") {
    $user = Request::$post->string("username");
    $pass = Request::$post->string("password");
    // check password and redirect to home if it is correct
    // if it is not unset auth variable and increment count
    if ($user == "user" && $pass == C::$login->pass) {
        Request::$session->authorise();
        $redirect = Request::$get->string("requested", default:"/");
        Response::redirect($redirect);
    } elseif ($user == "admin" && $pass == C::$login->admin) {
        Request::$session->authorise(true);
        Response::redirect("/upload");
    } else {
        Request::$session->deny();
    }
// if already authorised that means an api key has been used so redirect
} else if(Request::$session->is_authorised) {
    Request::$session->authorise();
    $redirect = Request::$get->string("requested", default:"/");
    Response::redirect($redirect);
}

// check login attempts to stop people trying over and over to guess the password
Request::$session->login_attempts < C::$login->max_attempts || App::die("You're done - try again later.");

// output header
$title = "Security";
require_once "parts/header.php";

?>

<p class="mt-2">Please log in to access feeds.</p>

<form class="row row-cols-lg-auto g-3 align-items-center needs-validation" method="POST" novalidate>
    <div class="col-12 position-relative">
        <label class="visually-hidden" for="username">Username</label>
        <input type="text" class="form-control" name="username" id="username" placeholder="Username" required />
        <div class="invalid-tooltip">Please enter the username.</div>
    </div>
    <div class="col-12 position-relative">
        <label class="visually-hidden" for="password">Password</label>
        <input type="password" class="form-control" name="password" id="password" placeholder="Password" required />
        <div class="invalid-tooltip">Please enter the password.</div>
    </div>
    <input type="hidden" name="attempts" value="<?php _e(Request::$session->login_attempts); ?>" />
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>

<script type="text/javascript">
    document.getElementById("username").focus();
</script>

<?php require_once "parts/footer.php"; ?>
