<?php
//Andreas Lundmark
//https://www.php.net/manual/en/reserved.variables.files.php
// Upload file using PHP. With error handling. JPGs, PNGs and TXTs are mirrored back
// to the client.

//Destination directory uploads located at the same place as this .php
$uploaddir = 'uploads/';
//_FILES include array items that are uploaded through the HTTP POST method.
$file = $uploaddir . basename($_FILES['file']['name']);
//$mimetype = strtolower($_FILES["file"]["type"]);
$filesize = $_FILES["file"]["size"];
$fileType = strtolower(pathinfo($file, PATHINFO_EXTENSION));

if (file_exists($file)) {
    echo "File already exists in uploads directory.";
    return;
}

if (move_uploaded_file($_FILES['file']['tmp_name'], $file)) {
    echo "File uploaded.\n";

} else {

    $errorValue = $_FILES['file']['error'];
    errorPrinter($errorValue);
    return;
}

if ($fileType == "jpg" || $fileType == "png" || $fileType == "jpeg") {
    printImg($file);
} elseif ($fileType == "txt") {
    printText($file);
} else {
    header("content-type: text/plain");
    echo "Name: " . $_FILES['file']['name'] . PHP_EOL;
    echo "Type: " . $_FILES["file"]["type"] . PHP_EOL;
    echo "Size: " . $_FILES["file"]["size"] . " kb" . PHP_EOL;
}

function printImg($file)
{
    ob_end_clean();
    header("content-type: image/jpeg");
    $handle = fopen($file, "rb");
    $contents = fread($handle, filesize($file));
    echo $contents;
    fclose($handle);
}

function printText($file)
{
    ob_end_clean();
    header("content-type: text/plain");
    $printfile = fopen($file, "r") or die("Unable to open file!");
    echo fread($printfile, filesize($file));
    fclose($printfile);
}

/**
 * ■UPLOAD_ERROR_OK, value 0, means no error occurred.
 * ■ UPLOAD_ERR_INI_SIZE, value 1, means that the size of the uploaded file exceeds the
 * maximum value specified in your php.ini file with the upload_max_filesize directive.
 * ■ UPLOAD_ERR_FORM_SIZE, value 2, means that the size of the uploaded file exceeds the
 * maximum value specified in the HTML form in the MAX_FILE_SIZE element.
 * ■ UPLOAD_ERR_PARTIAL, value 3, means that the file was only partially uploaded.
 * ■ UPLOAD_ERR_NO_FILE, value 4, means that no file was uploaded.
 * ■ UPLOAD_ERR_NO_TMP_DIR, value 6, means that no temporary directory is specified in the
 * php.ini.
 * ■ UPLOAD_ERR_CANT_WRITE, value 7, means that writing the file to disk failed.
 * ■ UPLOAD_ERR_EXTENSION, value 8, means that a PHP extension stopped the file upload
 * process.
 */
function errorPrinter($errorValue)
{
    ob_end_clean();
    header("content-type: text/plain");
    switch ($errorValue) {
        case 1:
            echo "UPLOAD_ERR_INI_SIZE, size of the uploaded file exceeds the
                 maximum value specified in php.ini ( upload_max_filesize directive.)";
            break;
        case 2:
            echo "UPLOAD_ERR_FORM_SIZE, size of the uploaded file exceeds the
                maximum value specified in the HTML form in the MAX_FILE_SIZE element.";
            break;
        case 3:
            echo "UPLOAD_ERR_PARTIAL, value 3, means that the file was only partially uploaded.";
            break;
        case 4:
            echo "UPLOAD_ERR_NO_FILE, no file was uploaded.";
            break;
        case 6:
            echo "UPLOAD_ERR_NO_TMP_DIR, no temporary directory is specified in the
                    php.ini.";
            break;
        case 7:
            echo "UPLOAD_ERR_CANT_WRITE, writing the file to disk failed.";
            break;
        case 8:
            echo "UPLOAD_ERR_EXTENSION,PHP extension stopped the file upload
        process.";
            break;
        default:
            "Unkown error";
            break;
    }
}

?>
