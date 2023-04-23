<?php
include('./modules/frontend.php');
// HOME PAGE IS BUILT IN helloworld.php in ./src DIRECTORY and then included here.
include_once('./src/helloworld.php');
include_once('./src/home.php');
include_once('./src/404.php');
include_once('./src/about-us.php');
include_once('./src/contact.php');
end_doc();
?>