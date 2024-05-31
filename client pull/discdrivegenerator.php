<?php

// Refresh every second
header('Refresh: 1');
header('Content-Type: text/plain');

//array with string content
$floppy = array("
     .---------------------------------.",
    "|  .---------------------------.  |",
    "|[]|                           |[]|",
    "|  |                           |  |",
    "|  |                           |  |",
    "|  |                           |  |",
    "|  |                           |  |",
    "|  |                           |  |",
    "|  |                           |  |",
    "|  |                           |  |",
    "|  |           Hello!          |  |",
    "|  `---------------------------'  |",
    "|      __________________ _____   |",
    "|     |   ___            |     |  |",
    "|     |  |   |           |     |  |",
    "|     |  |   |           |     |  |",
    "|     |  |   |           |     |  |",
    "|     |  |___|           |     |  |",
    "\_____|__________________|_____|__|"
);

$counterFile = fopen("saveFile.txt", "r+") or die("Cannot open file!");

if (flock($counterFile, LOCK_EX)) {
    //read the value at the start of the file to counter variable
    $counter = (int)fread($counterFile, filesize("saveFile.txt"));

    if ($counter == 19) {
        $counter = 0;
    } else {
        $counter++;
    }
    //Move the pointer back to the start of file
    rewind($counterFile);
    //clear the file
    ftruncate($counterFile, 0);
    //Overwrite with the new counter at beginning of file
    fwrite($counterFile, (string)$counter);
    // make sure data is saved before unlocking the file
    fflush($counterFile);
    //unlock the file againg
    flock($counterFile, LOCK_UN);
} else {
    echo "Error locking file!";
}
fclose($counterFile);
$result = "";
//print the floppyarray
for ($i = 0; $i < $counter; $i++) {
    $result .= $floppy[$i] . "\n";
}
echo $result;

?>
