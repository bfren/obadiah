<?php

namespace Feeds\Pages;

use Feeds\App;

App::check();

?>

</div><!-- primary container -->
</main>

<footer class="footer mt-auto py-3 bg-light">
    <div class="container-fluid">
        <small class="text-muted">
            Copyright &copy; <?php _e(date("Y")); ?> <a href="https://christchurchb29.org">Christ Church Selly Park</a>.
            <span class="d-none d-sm-inline">Source on <a href="https://github.com/bfren/churchsuite-feeds" target="_blank">GitHub</a>.</span>
        </small>
    </div>
</footer>

<!-- Bootstrap -->
<script src="/resources/js/popper.min.js"></script>
<script src="/resources/js/bootstrap.min.js"></script>
<script src="/resources/js/validate.js"></script>

<!-- Prayer Calendar -->
<script src="/resources/js/axios.min.js"></script>
<script src="/resources/js/dragula.min.js"></script>
<script src="/resources/js/prayer.js"></script>

</body>

</html>
