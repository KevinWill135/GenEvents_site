<?php

    session_start();
    include '../db.php';
    include_once '../admin/functions.php';
    $response = ['success' => false, 'message' => 'Primeiro problema aqui process_register.php line 5'];

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        //recebendo dados do formulário
        $name = trim($_POST['name']);
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirmPassword = trim($_POST['confirmPassword']);
        $city = trim($_POST['city']);
        $country = trim($_POST['country']);
        $phone = trim($_POST['phone']);
        $event_type = trim($_POST['event_type']);
        
        //validando dados do formulário
        if(strlen($name) < 4) {
            $response['message'] = 'Nome deve ter pelo menos 4 letras';
            echo json_encode($response);
            exit;
        }

        if(strlen($username) < 3) {
            $response['message'] = 'Username deve ter pelo menos 3 letras';
            echo json_encode($response);
            exit;
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Email inválido';
            echo json_encode($response);
            exit;
        }

        $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z0-9]).{8,}$/';
        if(!preg_match($passwordRegex, $password)) {
            $response['message'] = 'A senha deve ter pelo menos 8 letras, uma letra maiúscula, um número e um caractere especial';
            echo json_encode($response);
            exit;
        }

        if($password !== $confirmPassword) {
            $response['message'] = 'As senhas não coincidem';
            echo json_encode($response);
            exit;
        }

        if(strlen($city) < 3) {
            $response['message'] = 'Cidade deve ter pelo menos 3 letras';
            echo json_encode($response);
            exit;
        }

        if(strlen($country) < 2) {
            $response['message'] = 'País deve ter pelo menos 2 letras';
            echo json_encode($response);
            exit;
        }

        $phoneRegex = '/^(\+|00)?\d{1,3}[\s\-]?(\(?\d{2,4}\)?[\s\-]?)?\d{3,5}[\s\-]?\d{3,5}$/';
        if(!preg_match($phoneRegex, $phone)) {
            $response['message'] = 'Telefone inválido';
            echo json_encode($response);
            exit;
        }

        if(empty($event_type)) {
            $response['message'] = 'Selecione um tipo de evento';
            echo json_encode($response);
            exit;
        }

        //fazer o upload da foto de perfil
        $user_img = null;
        if(!empty($_FILES['user_img']['name'])) {
            $file = $_FILES['user_img'];
            $uploadDir = 'uploads/';
            $uploadFile = $uploadDir. basename($file['name']);

            //verificando se a pasta uploads para as imagens existe
            if(!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            //verificando se a imagem foi movido para o diretório correto
            if(move_uploaded_file($file['tmp_name'], $uploadFile)) {
                $user_img = $uploadFile;
            } else {
                $response['message'] = 'Falha ao fazer o upload da imagem';
                echo json_decode($response);
                exit;
            }
        }

        //criptografia da senha
        $hashedPassword = hashPassword($password);

        //inserir dados em DB gen_events
        $stmt = $pdo->prepare("INSERT INTO users (name, username, phone, city, country, email, password, user_img, event_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $success = $stmt->execute([$name, $username, $phone, $city, $country, $email, $hashedPassword, $user_img, $event_type]);
        if($success) {
            $response['success'] = true;
            $response['message'] = 'Usuário registrado com sucesso';
        } else {
            $response['message'] = 'Erro ao registrar usuário';
        }

        echo json_encode($response);

    }

?>