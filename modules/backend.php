<?php
include_once('./string.php');

if (DEV_MODE == true) {
    error_reporting(E_ALL);
    ini_set("display_errors", "1");
    http_response_code(200);
} else {
    ini_set("display_errors", "0");
}

function domain_root()
{
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $domain = $_SERVER['HTTP_HOST'];
    $current_url = $protocol . '://' . $domain;
    return $current_url;
}
function current_url()
{
    $url = domain_root() . $_SERVER['REQUEST_URI'];
    return $url;
}
function get_dir()
{
    $directory = dirname($_SERVER['PHP_SELF']);
    return $directory;
}
function current_request()
{
    return $_SERVER['REQUEST_URI'];
}
function import($lib)
{
    $lib_path = "./lib/$lib";
    if (file_exists("$lib_path/header.php")) {
        include_once("$lib_path/header.php");
    }
    if (file_exists("$lib_path/index.php")) {
        include_once("$lib_path/index.php");
    }
}

?>