<?php

namespace Obadiah\Pages\Auth;

use Obadiah\Admin\Result;
use Obadiah\App;
use Obadiah\Pages\Parts\Header\Header_Model;
use Obadiah\Request\Request;
use Obadiah\Response\View;

App::check();

/** @var View $this */

// output header
$this->header(new Header_Model("Security"));

// output denied alert
if (Request::$session->is_denied()) {
    $this->alert(Result::failure("Access denied, please try again."));
}

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

<?php

$this->footer();
