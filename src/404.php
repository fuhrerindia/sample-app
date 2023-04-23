<?php
include_once("./src/comp/nav.php");
def_error404([
    "render"=><<<HTML
    $navigation
    <center>
        <h1>ERROR 404</h1>
        <p>Page Not Found</p>
    </center>
    HTML,
    "header"=><<<HTML
        <title>
            Page Not Found &bull; Yugal
        </title>
    HTML
]);
?>