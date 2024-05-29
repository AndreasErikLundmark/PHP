<?php
//Simple sql example procedures upload to database
//https://www.php.net/manual/en/mysqli.quickstart.stored-procedures.php

require_once "dbConnectionPool.php";

$connectionPool = dbConnectionPool::getInstance();
$dbh = $connectionPool->getConnection();

$sql="

CREATE PROCEDURE IF NOT EXISTS friendInserter(
    IN insert_name VARCHAR(30),
    IN insert_favorite_color VARCHAR(50),
    IN insert_birthday DATE
)
BEGIN
    INSERT INTO myFriends (name,favorite_color, birthday)
    VALUES (insert_name,insert_favorite_color, insert_birthday);
END 

";

$dbh->exec($sql);

$sql="CREATE PROCEDURE IF NOT EXISTS getAllFriend()
BEGIN
    SELECT name, favorite_color, birthday FROM myFriends;
END
";

$dbh->exec($sql);


$connectionPool->returnConnection($dbh);
?>
