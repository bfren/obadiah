<?php

namespace Feeds\Pages;

use Feeds\App;
use Feeds\Pages\Parts\Header\Header;
use Feeds\Request\Request;
use Feeds\Response\Html;

App::check();

/** @var Html $this */

// output header
$this->header(new Header("Security"));

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

<?php $this->footer();
