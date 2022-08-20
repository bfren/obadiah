<?php

namespace Feeds\Pages;

use DateTimeImmutable;
use Feeds\Config\Config as C;

defined("IDX") || die("Nice try.");

$today = new DateTimeImmutable("now", C::$events->timezone);

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

<script src="/js/bootstrap.min.js"></script>

</body>

</html>
