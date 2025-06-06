<?php
include ("modelos.php");
include ("plantilla.php");
include ("DBx.php");

function base_url($path ="") {
    $protocol = isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === 'on' ? "https://" :"http://";

    $host = $_SERVER["HTTP_HOST"];

    $path = trim($path,"/");

    return $protocol . $host . "/" . $path;  
}
?>