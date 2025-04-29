<?php

    session_start();
    include '../db.php';


    if(!isset($_SESSION['user_id']) AND $_SESSION['role'] !== 'admin') {
        header('Location: ../index.php');
        exit;
    }

    print_r($_POST);

?>