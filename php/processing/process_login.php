<?php

    session_start();
    include '../db.php';

    json_encode(['success' => false,'message' => 'Email e senha são obrigatórios']);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        if(empty($email) || empty($password)) {
            echo json_encode(['success' => false,'message' => 'Email e senha são obrigatórios']);
            exit;
        }

        //verificar se utilizador existe
        $stmt = $pdo->prepare('SELECT id, password, role, event_type FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['event_type'] = $user['event_type'];
    
            $redirect = $_SESSION['role'] === 'admin' ? '../admin/admin.php' : '../index.php';
            echo json_encode(['success' => true, 'redirect' => $redirect]);
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Email ou senha inválidos.']);
            exit;
        }
    }

?>