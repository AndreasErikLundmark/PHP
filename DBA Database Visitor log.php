<?php
//Creates a small database visitor log with DBA. Check server and handler compability
//https://www.php.net/manual/en/dba.installation.php
//https://www.php.net/manual/en/function.dba-fetch.php
//https://www.php.net/manual/en/function.dba-insert.php
//methods used: dba_open, dba_insert, dba_fetch, dba_close

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: text/plain');
// file name + path. File extension optional
$db_path = "myVisitorLog.db.cdb";
//"c" creates a db-file if it does't exist. "r", "w" are other options
$db = dba_open($db_path, "c", "gdbm");

if (!$db) {
    echo "Could not open/create the DB at $db_path";
    exit;
} else {
    //Getting the data to be stored
    $date = date("Y-m-d");
    $time = date('H:i:s');
    $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
    $HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];
    //Divider token separates values
    $DIV = "\xFE";

    $dataLogEntry = "$date" . $DIV . "$time" . $DIV . "$REMOTE_ADDR" . $DIV . "$HTTP_USER_AGENT";
    //Write entry to database
    dba_insert($key = uniqid(), $dataLogEntry, $db);

    //Fetching the data
    $key = dba_firstkey($db);
    //List to store dB entries
    $LIST = array();
    $ii = 0;
    //Going through the database
    while ($key != NULL) {
        //Get the data for every key
        $data = dba_fetch($key, $db);
        //List in List
        $LIST[$ii] = explode($DIV, $data);    // fetch array

        $key = dba_nextkey($db);

        $ii++;

    }
}

// Close the database
dba_close($db);

//size of exported data list
$total = $ii;

//sort($LIST,SORT_NUMERIC);
//$counter = 0;

//Print list
for ($i = 0; $i < $total; $i++) {

    $entry = $LIST[$i];
    $date = $entry[0];
    $time = $entry[1];
    $REMOTE_ADDR = $entry[2];
    $HTTP_USER_AGENT = $entry[3];


    echo "Tid: $date $time" . PHP_EOL
        . "REMOTE_ADDR: $REMOTE_ADDR" . PHP_EOL
        . "HTTP_USER_AGENT: $HTTP_USER_AGENT" . PHP_EOL . PHP_EOL;
}

?>
