<?php

namespace Feeds\Pages\Parts\Footer;

use DateTimeImmutable;
use Feeds\App;
use Feeds\Config\Config as C;

App::check();

$today = new DateTimeImmutable("now", C::$events->timezone);

?>

</div><!-- primary container -->
</main>

<footer class="footer footer-prayer mt-2">
    <div class="container-fluid">
        <small class="text-muted">
            <p class="d-flex justify-content-between">
                <span><a href="https://www.christchurchb29.org/privacy-policy" target="_blank">www.christchurchb29.org/privacy-policy</a></span>
                <span>Generated on <?php _e($today->format("r")); ?></span>
            </p>
        </small>
    </div>
</footer>

<script src="/resources/js/bootstrap.min.js"></script>

</body>

</html>
