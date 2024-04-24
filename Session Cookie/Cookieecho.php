<?php
header('Content-Type: text/plain');
// search $_COOKIE for Session id

if (isset($_COOKIE['Session-id'])) {
    echo "Session-id = " . $_COOKIE['Session-id'];
} else {
    echo "Could not set a cookie for 'Session-id'";
}

?>