<?php

namespace Obadiah\Pages\Parts\Footer;

use DateTimeImmutable;
use Obadiah\App;
use Obadiah\Config\Config as C;

App::check();

$today = new DateTimeImmutable("now", C::$events->timezone);

?>

</div><!-- primary container -->
</main>

<footer class="footer mt-auto py-1 bg-light">
    <div class="container-fluid">
        <small class="text-muted">
            Generated on <?php _e($today->format("r")); ?>
        </small>
    </div>
</footer>

<script src="/resources/js/bootstrap.min.js"></script>

</body>

</html>