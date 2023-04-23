<?php
include_once("./src/comp/nav.php");
def_page([
    "render" => <<<HTML
        {$navigation}
        <h1>About Us</h1>
        <p>
        I have chosen the WP-PageNavi plugin (the most popular option) to demonstrate the installation and setup in this blog. Almost all WordPress pagination plugins are installed and configured the same way, so you can adopt this method to install your preferred plugin.
        If you still don’t understand the importance of pagination, then look at Google, and notice how it treats its audience with the search results. Does it include all the results on a single page or separate them with various pages? Take notes. Shall you require more assistance? Feel free to drop your queries in the comment section below.
        </p>
    HTML,
    "uri" => "/about-us",
    "header" => <<<CSS
        <title>About Us &bull; Yugal</title>
    CSS
]);
?>