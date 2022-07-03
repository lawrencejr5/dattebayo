<?php

$dbhost = "localhost:3325";
$dbname = "blog";
$dbuser = "root";
$dbpass = "";

try{
    $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "connection succussful";
}
catch(PDOException $e){
    echo "connection failed" . $e -> getMessage();
}
?>