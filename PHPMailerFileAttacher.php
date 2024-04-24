<?php
//Using PHPMailer uploading mail fileattachment..

//https://github.com/PHPMailer/PHPMailer
//https://github.com/PHPMailer/PHPMailer/blob/master/README.md

//http :<form method="post" enctype="multipart/form-data" action="this.php">
//http : <input type="file" name="file1"/>....

ini_set('display_errors', 1);
error_reporting(E_ALL);

require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

//use PHPMailer\PHPMailer\Exception;
$mail = new PHPMailer();
//specify temp upload directory
$tempFileDir = '/Applications/XAMPP/xamppfiles/temp/';
$fileCounter = 0;
$tempFiles = array();  // to keep track of uploaded file paths

try {
    // attaching files
    foreach ($_FILES as $key => $fileArray) {
        if (empty($key)) {
        } else {
            myFileLoader($fileArray, $key, $mail, $fileCounter, $tempFiles, $tempFileDir);
        }
    }
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->Port = 465;
    $mail->SMTPAuth = true;
    $mail->Username = 'mail@gmail.com';  // SMTP username
    $mail->Password = 'xxxxxxxxx';  // SMTP password
    $mail->SMTPSecure = 'ssl';

    $From = $_POST['from'];
    $To = $_POST['to'];
    $Cc = $_POST['cc'];
    $Bcc = $_POST['bcc'];
    $Subject = $_POST['subject'];
    $Message = $_POST['message'];

    if (empty($Message)) {
        echo "forgot to write a message? please complete the form. ";
        return;
    }
    if (empty($Cc)) {
        $Cc = "Empty";
    }
    if (empty($Bcc)) {
        $Bcc = 'Empty';
    }
    if (empty($Subject)) {
        $Subject = 'Empty';
    }

    if (!filter_var($To, FILTER_VALIDATE_EMAIL) || !filter_var($From, FILTER_VALIDATE_EMAIL)) { // checks if To variable is an ok email adress
        echo "Please fill in both from and to field with an email. " . PHP_EOL;
    } else { // Constructing the email here:

//      adding an extra line to the message.
        $Message .= " Observera! Detta meddelande är sänt från ett formulär på Internet och avsändaren kan vara felaktig!";

        $mail->setFrom($From, $From);
        $mail->addAddress($To, 'User');
        $mail->addCC($Cc);
        $mail->addBCC($Bcc);
        $mail->isHTML(true);
        $mail->Subject = $Subject;
        $mail->Body = $Message;
        $mail->AltBody = strip_tags($Message);

        $mail->send();

        deleteTempFiles($tempFiles);

        echo 'Message successfully sent!';
    }
} catch
(Exception $e) {
    echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
}

function myFileLoader($value, $key, $mail, &$fileCounter, &$tempFiles, &$tempFileDir)
{
    $tempName = $_FILES[$key]['tmp_name'];
    $ext = PHPMailer::mb_pathinfo($_FILES[$key]['name'], PATHINFO_EXTENSION);

    //create unique filename for temporary upload
    $hashName = hash('sha256', $tempName) . '.' . $ext; // Hashed filename with extension
    $uploadfile = $tempFileDir . $hashName;

    if (!move_uploaded_file($_FILES[$key]['tmp_name'], $uploadfile)) {
        echo "One empty file upload.\n";
    } else {
        $mail->addAttachment($uploadfile);
        $tempFiles[$fileCounter] = $uploadfile;
        $fileCounter++;
    }
}

//clears the tempfiles in tempdirectory
function deleteTempFiles($tempFiles)
{
    foreach ($tempFiles as $key => $path) {
        unlink($path);
    }
}

?>