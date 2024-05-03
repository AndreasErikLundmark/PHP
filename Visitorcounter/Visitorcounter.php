<?php


//header('Content-Type: text/plain');

//open file (r+ == read and write )
$counterFile = fopen("/Applications/XAMPP/xamppfiles/htdocs/Wprog2/Uppgifter/counter.txt", "r+") or die("Cannot open file!");

if (flock($counterFile, LOCK_EX)) {


//read the value at the start of the file to counter variable
    $counter = fread($counterFile, filesize("counter.txt"));

//increase counter
    $counter = $counter + 1;

//Move the pointer back to the start of file
    rewind($counterFile);

//Overwrite with the new counter at beginning of file
    fwrite($counterFile, (string)$counter);

// make sure data is saved before unlocking the file
    fflush($counterFile);
    //unlock the file againg
    flock($counterFile, LOCK_UN);
} else {
    echo "Error locking file!";
}

//close file
fclose($counterFile);

//Fetch and lead to the html destination and replace the '---$hits---' variable with counter value.
$html = file_get_contents("Visitorcounter.html");
$html = str_replace('---$hits---', $counter, $html);
echo $html;
?>