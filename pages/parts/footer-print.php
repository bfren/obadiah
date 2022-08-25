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

<footer class="footer mt-auto py-3 bg-light">
    <div class="container-fluid">
        <small class="text-muted">
            Generated on <?php echo $today->format("r"); ?>
        </small>
    </div>
</footer>

<script src="/resources/js/bootstrap.min.js"></script>

</body>

</html>
