<?php

    session_start();
    include '../db.php';

    if(!isset($_SESSION['user_id']) AND isset($_SESSION['role']) !== 'admin') {
        header('Location: ../index.php');
        exit;
    }

    $response = ['success' => false, 'message' => 'Erro na chegada dos dados.'];

    if(isset($_POST)) {
        $event_id = intval($_POST['event_id']);
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $visibility_date = trim($_POST['visibility_date']);
        $location = trim($_POST['location']);
        $seats = intval($_POST['seats']);
        $event_type = trim($_POST['event_type']);

        if(isset($_FILES['event_img']) && $_FILES['event_img']['error'] === UPLOAD_ERR_OK) {
            //declarando variáveis
            $img = $_FILES['event_img'];
            //extensões permitidas
            $permitidas = ['jpg', 'jpeg', 'webp', 'png'];
            $img_file = strtolower(pathinfo($img['name'], PATHINFO_EXTENSION));
            $security_name = uniqid() . '.' . $img_file;
            if(in_array($img_file, $permitidas)) {
                $destiny = dirname(__FILE__, 3) . '/img_events/' . $security_name;

                //movendo a imagem para a pasta
                if(move_uploaded_file($img['tmp_name'], $destiny)) {
                    $img_db = '../img_events/' . $security_name;
                    //atualizando o dado em database
                    $stmt = $conn->prepare("UPDATE events SET main_img = ? WHERE id = ?");
                    $stmt->bind_param('si', $img_db, $event_id);
                    $stmt->execute();

                    $response['success'] = true;
                    $response['message'] = 'Imagem editada com sucesso.';
                } else {
                    $response['message'] = 'Erro ao mover a imagem.';
                    exit;
                }
            } else {
                $response['message'] = 'Formato de imagem inválido.';
                exit;
            }
        }

        $sql = $conn->prepare("UPDATE events SET name = ?, description = ?, event_date = ?, location = ?, seats = ?, event_type = ? WHERE id = ?");
        $sql->bind_param('ssssisi', $name, $description, $visibility_date, $location, $seats, $event_type, $event_id);
        $sql->execute();

        $response['success'] = true;
        $response['message'] = 'Evento editado com sucesso.';
    } else {
        $response['message'] = 'Erro ao editar o evento.';
    }

    echo json_encode($response);

?>