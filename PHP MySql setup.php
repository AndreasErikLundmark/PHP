<?php
//Setting up a Mysql database with gathered examples. XAMPP configured
//https://www.w3schools.com/php/php_mysql_select_where.asp

ini_set('display_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "DBtest3";

// Creating a connection with the Xampp Mysql Server.
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
//echo "Connected successfully";

// CREATE DATABASE
$sql = "CREATE DATABASE IF NOT EXISTS DBtest3";
if ($conn->query($sql) === FALSE) {
    echo "Error creating database: " . $conn->error;
}

//UPDATE THE CONNECTION TO POINT TO YOUR DB
$conn->select_db($dbname);
//Alternative if conn is closed
//$conn = new mysqli($servername, $username, $password,$dbname);

// CREATE A TABLE
$sql = "CREATE TABLE IF NOT EXISTS testTable (
id INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(30),
email VARCHAR(50),
url VARCHAR(50),
comment VARCHAR(200),
reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP

)";
//Check Table creation.
if ($conn->query($sql) === TRUE) {
    echo "Database and Table created or loaded successfully" . "<br>";
} else {
    echo "Error creating table: " . $conn->error;
}
//LOAD THE TABLE WITH DATA EXAMPLE.
$sql = "INSERT INTO testTable (name, email, url, comment)
VALUES ('Brutus', 'Brutus@noemail.com', 'www.brutus.com', 'what if it works?')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully" . "<br>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

//PREPARED PARAMETERS EXAMPLE
$stmt = $conn->prepare("INSERT INTO testTable (name, email, url, comment) VALUES (?, ?, ?, ?)");
//number of s == number of variables
$stmt->bind_param("ssss", $name, $email, $url, $comment);

// set parameters and execute
$name = "Bibbi";
$email = "bibbi@example.com";
$url = "bibbi.com";
$comment = "bibbi.com";
$stmt->execute();

echo "Data loaded to " . $dbname . "<br>";
$stmt->close();


//SELECT EXAMPLE
$sql = "SELECT id, name, comment,reg_date FROM testTable";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        echo $row["reg_date"] . "/ id: " . $row["id"] . "/ Name: " . $row["name"] . " / Comment: " . $row["comment"] . "<br>";
    }
} else {
    echo "No data was fetched from " . $dbname . "<br>";
}

$conn->close();

?>




