<?php

    include '../db.php';
    include_once '../class/users.php';
    header('Content-Type: application/json');



    $user_id = $_POST['id_selected'];
    $user = new Usuario($pdo, $user_id);
    $data = $user->getDados();

    echo json_encode($user_id);
    exit;

?>