<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "jazykova_skola";
    $port = 3306;

    $conn = new mysqli($servername, $username, $password, $db, $port);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>