<?php

    session_start();
    include '../db.php';
    header('Content-Type: application/json');

    if(!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Usuário não logado']);
        header('Location: ../index.php');
        exit;
    }

    if(!isset($_POST['new_qtd'])) {
        echo json_encode(['success' => false, 'message' => 'Dados não recebidos corretamente']);
        exit;
    }

    //atualizando coluna quantity
    $user_id = intval($_POST['user_id']);
    $new_qtd = intval($_POST['new_qtd']);
    $batch_id = intval($_POST['batch_id']);

    $stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE user_id = ? AND batch_id = ?");
    $stmt->bind_param('iii', $new_qtd, $user_id, $batch_id);
    
    if($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Dados atualizado com sucesso']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao atulizar dados no servidor']);
    }

?>