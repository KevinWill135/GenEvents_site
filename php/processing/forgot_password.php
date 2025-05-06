<?php

    include '../db.php';

    $response = ['success' => false, 'message' => 'Erro ao carregar os dados'];
    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        if(!empty($_GET['username'])) {
            $username = trim($_GET['username']);

            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if($user) {
                $response = [
                    'success' => true,
                    'message' => 'Usuário encontrado',
                    'user_id' => $user['id']
                ];
            } else {
                $response['message'] = 'Usuário não encontrado';
            }
        } else {
            $response['message'] = 'Username Vazio';
        }
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(!empty($_POST['user_id']) AND !empty($_POST['password'])) {
            $user_id = trim($_POST['user_id']);
            $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->bind_param('si', $newPassword, $user_id);
            
            if($stmt->execute()) {
                $response = [
                    'success' => true,
                    'message' => 'Senha alterada com sucesso'
                ];
            } else {
                $response['message'] = 'Erro ao alterar a senha';
            }
        } else {
            $response['message'] = 'Falha ao carregar dados';
        }
    }

    echo json_encode($response);

?>