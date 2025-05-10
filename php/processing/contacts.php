<?php

    session_start();
    include '../db.php';

    if($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode(['success' => false, 'message' => 'Erro na chegada dos dados']);
        exit;
    }

    $response = ['success' => false, 'message' => 'First Problem line 11' ];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email']);
        $name = trim($_POST['name']);
        $phone = trim($_POST['phone']);
        $description = trim($_POST['description']);

        if(!$email) {
            $response['message'] = 'Email ainda vazio!';
            echo json_encode($response);
            exit;
        }

        if(strlen($name) < 5) {
            $response['message'] = 'Nome precisa estar completo';
            echo json_encode($response);
            exit;
        }

        $phoneRegex = '/^(\+|00)?\d{1,3}[\s\-]?(\(?\d{2,4}\)?[\s\-]?)?\d{3,5}[\s\-]?\d{3,5}$/';
        if(!preg_match($phoneRegex, $phone)) {
            $response['message'] = 'Precisa de um número de telefone válido';
            echo json_encode($response);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO contacts(name, email, phone, description) VALUES(?, ?, ?, ?)");
        $stmt->bind_param('ssss', $name, $email, $phone, $description);
        $stmt->execute();

        $response['success'] = true;
        $response['message'] = 'Dados enviados com sucesso!';
        echo json_encode($response);
    }



?>