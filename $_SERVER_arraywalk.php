<?php
//going throuhg the $_SERVER array

header('Content-Type: text/plain');

$variables = array_keys($_SERVER);

// Count the number of length
$arrayLength = count($variables);

//loop to iterate over the SERVER array
for ($i = 0; $i < $arrayLength; $i++) {
    $variable = $variables[$i];

    // get the value for that variable. for example REMOTE_ADDR '
    $value = $_SERVER[$variable];
    //Print variable name + the value + new line
    echo $variable . ": " . $value . PHP_EOL;

}

?>