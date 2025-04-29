<?php

    $host = 'mysql';
    $user = 'root';
    $password = 'root';
    $database = 'gen_events';

    $conn = new mysqli($host, $user, $password, $database);
    if($conn->connect_error) {
        die('Connection failed: ' . $conn->connect_error);
    }

    //conexão pdo
    $pdo = new PDO("mysql:host=$host;dbname=$database", $user, $password);

?>