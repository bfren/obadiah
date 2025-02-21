<?php

namespace Obadiah\Pages\About;

use Obadiah\App;
use Obadiah\Pages\About\Index_Model;
use Obadiah\Pages\Parts\Header\Header_Model;
use Obadiah\Response\View;

App::check();

/** @var View $this */
/** @var Index_Model $model */

// output header
$this->header(new Header_Model("About", subtitle: "More information about Obadiah."));

?>

<h2>Why &lsquo;Obadiah&rsquo;?</h2>
<p class="about">
    Obadiah was the faithful palace administrator during the time of Ahab. He supported Elijah and saved 100 prophets from Jezebel.
    It seems to me like his is a good name to use for a piece of software that supports effective church administration.
</p>
<p>
    (See <a href="https://www.biblegateway.com/passage/?search=1%20Kings%2018&version=NIVUK" target="_blank">1 Kings 18</a>.)
</p>

<h2>What does Obadiah do?</h2>
<p class="about">
    <a href="https://churchsuite.com" target="_blank">ChurchSuite</a> is excellent software but it has some limitations &ndash;
    for example, there is no sensible way to add readings to a service using the Rota module. I also wanted to use the Address Book
    to generate prayer resources, without having to have a separate database containing the
    names of everyone in the church.
</p>
<p class="about">
    Therefore I used the <a href="https://github.com/ChurchSuite/churchsuite-api" target="_blank">API</a> and the Rota export feature
    to create some software to solve those two problems.  The Rota page relies on a separate database created using the excellent
    <a href="https://baserow.io" target="_blank">Baserow</a> software, which holds all the readings and sermon titles / series info,
    as well as the Church of England&rsquo;s Collects.  The data from ChurchSuite and Baserow is combined to create various useful
    feeds, calendars and prayer aids.
</p>

<?php

$this->footer();
