<?php
//Implementing stored procedures and connection pooling examples
//https://www.php.net/manual/en/pdo.prepared-statements.php


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "dbConnectionPool.php"; // creating a connection pool
require_once "storedProcedures.php"; // creating procedures


//fetching the pool
$connectionPool = dbConnectionPool::getInstance();
//fetching a connection
$dbh = $connectionPool->getConnection();


// CREATE A TABLE
$sql = "CREATE TABLE IF NOT EXISTS myFriends (
id INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(30),
favorite_color VARCHAR(50),
birthday DATE,
reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

$dbh->exec($sql);


//Insert some friends using a stored procedure
$stmt = $dbh->prepare("CALL friendInserter(?,?,?)");
$name = "Bengt";
$color = "Yellow";
$birthday = 19850403;
$stmt->bindParam(1, $name, PDO::PARAM_STR);
$stmt->bindParam(2, $color, PDO::PARAM_STR);
$stmt->bindParam(3, $birthday, PDO::PARAM_STR);

if ($stmt->execute()) {
    echo "friend1 inserted. ";
}

$name = "Sara";
$color = "Svart";
$birthday = 19950204;
$stmt->bindParam(1, $name, PDO::PARAM_STR);
$stmt->bindParam(2, $color, PDO::PARAM_STR);
$stmt->bindParam(3, $birthday, PDO::PARAM_STR);
if ($stmt->execute()) {
    echo "friend2 inserted. ";
}

$name = "Albert";
$color = "Brun";
$birthday = 19550211;
$stmt->bindParam(1, $name, PDO::PARAM_STR);
$stmt->bindParam(2, $color, PDO::PARAM_STR);
$stmt->bindParam(3, $birthday, PDO::PARAM_STR);
if ($stmt->execute()) {
    echo "friend3 inserted. ";
}


// finally fetching all my friends
$stmt = $dbh->prepare("CALL getAllFriend");
$stmt->execute();
// bind_result separate data from datatable
$stmt->bindParam(1, $name, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
$stmt->bindParam(2, $color, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);
$stmt->bindParam(3, $birthday, PDO::PARAM_STR | PDO::PARAM_INPUT_OUTPUT);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print $row['name'] . " " . $row['favorite_color'] . " " . $row['birthday'] . "//";
}
//returning the connection to the pool
$connectionPool->returnConnection($dbh);

?>
