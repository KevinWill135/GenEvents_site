<?php

    session_start();
    include '../db.php';

    if(!isset($_SESSION['user_id']) AND isset($_SESSION['role']) !== 'admin') {
        header('Location: ../index.php');
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM users");
    $stmt->execute();
    $result = $stmt->get_result();
    $users = [];
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
        //echo '<pre>';
        //print_r($row['password']);
        //echo '</pre>';
        //exit;
    }

    echo json_encode($users);


?>