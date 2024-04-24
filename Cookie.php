<?php

//create a random id. uniqid based on microtime
function idGenerator()
{
    $number = uniqid();
    return $number;
}

$sessionId = idGenerator();

//creating a cookie
$cookie_name = "Session-id";
$cookie_value = $sessionId;
$cookie_time_valid = 3600 * 3; // 3600s = 1h * 3 = 3 hours
setcookie($cookie_name, $cookie_value, time() + $cookie_time_valid, "/"); // "/" = valid on whole website

$html = file_get_contents("CookieExample.html");
$html = str_replace('---session-id---', $cookie_value, $html);
echo $html;
?>