<?php

namespace Obadiah\Pages\Refresh;

use Obadiah\App;
use Obadiah\Config\Config as C;
use Obadiah\Pages\Parts\Header\Header_Model;
use Obadiah\Response\View;

App::check();

/** @var View $this */


// output header
$this->header(new Header_Model("Refresh Help"));
?>

<h2>Bible Readings</h2>
<p>The <strong>five streams</strong> are planned so if you follow all five you will read the entire Bible through once over the course of the year. It is well worth doing - but it is also a big commitment. The plan is designed so you have roughly the same amount to read each day, which should take 15-20 minutes.</p>
<p>You can use any of them in combination with the others. The simplest plan would be to pick <strong>Stream 4</strong> to learn more about Jesus - which would be a good place to start if you are new to the Bible, or new to reading it systematically.</p>
<ul>
    <li>
        <strong>Stream 1</strong> cycles through the psalms four times. You may find it helpful to read them out loud to express the emotion (from lament to praise) they contain.
    </li>
    <li>
        <strong>Stream 2</strong> begins with Genesis and works through to the end of 2 Chronicles: the Law and most of the Old Testament history books.
    </li>
    <li>
        <strong>Stream 3</strong> completes the Old Testament history with Ezra and Nehemiah, then moves through the wisdom books (e.g. Job, Proverbs) and then the prophets through to Malachi.
    </li>
    <li>
        <strong>Stream 4</strong> has a short passage from one of the gospels, about the life of Jesus; through the year you will read all four gospels.
    </li>
    <li>
        <strong>Stream 5</strong> is the rest of the New Testament, from Acts through the letters of Paul and others, all the way to Revelation.
    </li>
</ul>
<p>You can download the whole plan for the year in printable form <a href="/resources/files/bible-reading-plan-2025.pdf" target="_blank">here</a>, if you want to keep track of what you have read. (You will notice that there are no readings planned for Sundays - this is deliberate, perhaps on those days you could reflect on the previous week&rsquo;s readings, or read the passages set for the day&rsquo;s services.)</p>

<h2>People</h2>
<p>The next section contains a few people to pray for each day from our church family, so every member of our church family (that wants to) is prayed for every month. They are normally grouped by family - if you would like to be moved please contact the church office.</p>
<p>You can remove yourself from the prayer calendar in <a href="https://<?php _e(C::$churchsuite->org) ?>.churchsuite.com/my/details" target="_blank">My ChurchSuite</a> (under the &lsquo;Additional information&rsquo; heading), or by contacting the church office.</p>
<p>If you find yourself praying for someone you don&rsquo;t know, why try and find out who they are next time you&rsquo;re in church?</p>

<?php

$this->footer();
