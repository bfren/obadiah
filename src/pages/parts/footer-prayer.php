<?php

namespace Feeds\Pages;

use DateTimeImmutable;
use Feeds\App;
use Feeds\Config\Config as C;

App::check();

$today = new DateTimeImmutable("now", C::$general->timezone);

?>

</div><!-- primary container -->
</main>

<footer class="footer footer-prayer mt-2 py-1 bg-light">
    <div class="container-fluid">
        <small class="text-muted">
            <p>These names come from our church address book. If you don&rsquo;t wish to be included, you can remove yourself in Church Suite or by contacting the office.</p>
            <p class="d-flex justify-content-between">
                <span><a href="https://www.christchurchb29.org/privacy-policy" target="_blank">www.christchurchb29.org/privacy-policy</a></span>
                <span>Generated on <?php echo $today->format("r"); ?></span>
            </p>
        </small>
    </div>
</footer>

<script src="/resources/js/bootstrap.min.js"></script>

</body>

</html>
