<?php

    session_start();
    include '../db.php';
    header('Content-Type: application/json');

    if(!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Usuário não logado']);
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $data = json_decode(file_get_contents('php://input'), true);

    if(!is_array($data)) {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
        exit;
    }

    foreach($data as $item) {
        $batch_id = intval($item['batch_id']);
        $qtd_input = intval($item['qtd_input']);

        if($qtd_input > 0) {
            $stmt = $conn->prepare("INSERT INTO cart(user_id, batch_id, quantity, date_added) VALUES(?, ?, ?, NOW()) ON DUPLICATE KEY UPDATE quantity = quantity + VALUES(quantity)");
            $stmt->bind_param('iii', $user_id, $batch_id, $qtd_input);
            $stmt->execute();
        }
    }

    echo json_encode(['success' => true]);

?>