<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Config\Config as C;
use Feeds\Helpers\Arr;
use Feeds\Request\Request;

App::check();

// handle post requests
if (Request::$method == "POST") {
    $pass = Arr::get($_POST, "password");
    // check password and redirect to home if it is correct
    // if it is not unset auth variable and increment count
    if ($pass == C::$login->pass) {
        Request::authorise();
        Request::redirect("/");
    } elseif ($pass == C::$login->admin) {
        Request::authorise(true);
        Request::redirect("/admin");
    } else {
        Request::deny();
    }
}

// check login attempts to stop people trying over and over to guess the password
Request::get_login_attempts() < C::$login->max_attempts || die("You're done - try again later.");

// output header
$title = "Login";
require_once("parts/header.php");

?>

<p>Please enter the password to access.</p>

<form class="row row-cols-lg-auto g-3 align-items-center needs-validation" method="POST" novalidate>
    <div class="col-12 position-relative">
        <label class="visually-hidden" for="password">Password</label>
        <input type="password" class="form-control" name="password" id="password" placeholder="Password" required />
        <div class="invalid-tooltip">Please enter the password.</div>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>

<script type="text/javascript">
    document.getElementById("password").focus();
</script>

<?php require_once("parts/footer.php"); ?>
