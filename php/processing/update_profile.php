<?php

    session_start();
    include '../db.php';
    include_once '../class/users.php';

    $user_id = $_SESSION['user_id'];
    $user = new Usuario($pdo, $user_id);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $update = [
            'name' => trim($_POST['name']),
            'username' => trim($_POST['username']),
            'email' => trim($_POST['email']),
            'city' => trim($_POST['city']),
            'country' => trim($_POST['country']),
            'phone' => trim($_POST['phone']),
            'event_type' => trim($_POST['event_type']),
            'role' => trim($_POST['role'])
        ];
        
        $newPassword = trim($_POST['newPassword']);
        $confirmPassword = trim($_POST['confirmPassword']);
        
        if (!empty($newPassword)) {
            if ($newPassword !== $confirmPassword) {
                echo 'As senhas não coincidem';
                exit;
            }
        
            $update['confirmPassword'] = $confirmPassword;
        } else {
            unset($update['confirmPassword']);
        }

        $user->atualizar($update);
        $user_img = $_FILES['user_img'];
        if (!empty($user_img) && $user_img['error'] === 0) {
            $user->updateImg($user_img);
        }

        echo 'Usuário atulizado com sucesso!';
    }

?>