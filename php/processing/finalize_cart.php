<?php

    session_start();
    include '../db.php';
    header('Content-Type: application/json');

    //verificação de usuário
    if(!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Usuário indefinido']);
        exit;
    }

    //verificação de method enviado atraves do ajax
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método inválido']);
        exit;
    }

    //verificando tipo de dados enviados atraves do ajax
    $items = json_decode(file_get_contents('php://input'), true);
    if(!is_array($items)) {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
        exit;
    }

    //lógica para finalizar compra dos tickets
    $user_id = $_SESSION['user_id'];
    $total_price = floatval($items['total_price']);
    //preparando query para sales
    $stmt = $conn->prepare("INSERT INTO sales(user_id, total_price) VALUES(?, ?)");
    $stmt->bind_param('id', $user_id, $total_price);
    //verificação do execute para enviar ao order_items
    if($stmt->execute()) {
        $sale_id = $stmt->insert_id;

        $insert_success = true;
        foreach($items['items'] as $item) {
            $batch_id = intval($item['batch_id']);
            $quantity = intval($item['quantity']);
            $unit_price = floatval($item['unit_price']);
                
            $sql = $conn->prepare("INSERT INTO order_items(user_id, batch_id, sale_id, quantity, unit_price) VALUES(?, ?, ?, ?, ?)");
            $sql->bind_param('iiiid', $user_id, $batch_id, $sale_id, $quantity, $unit_price);
            if(!$sql->execute()) {
                $insert_success = false;
                break;
            }

            //subtraindo tickets de quantity dentro de batches
            $sub_btc = $conn->prepare("UPDATE batches SET available_quantity = available_quantity - ? WHERE id = ?");
            $sub_btc->bind_param('ii', $quantity, $batch_id);
            $sub_btc->execute();
            //subtraindo tickets de total de seats na tabela events
            $slc = $conn->prepare("SELECT event_id FROM batches WHERE id = ?");
            $slc->bind_param('i', $batch_id);
            $slc->execute();
            $result = $slc->get_result();
            $tb_btc = $result->fetch_assoc();
            $event_id = $tb_btc['event_id'];
            $sub_evnt = $conn->prepare("UPDATE events SET seats = seats - ? WHERE id = ?");
            $sub_evnt->bind_param('ii', $quantity, $event_id);
            $sub_evnt->execute();
        }

        if($insert_success) {
            //esvaziando carrinho
            $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            $stmt->bind_param('i', $user_id);
            $stmt->execute();

            echo json_encode(['success' => true, 'message' => 'Compra finalizada(servidor)']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao gravar os itens no banco de dados']);
        }   
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao criar venda']);
        exit;
    }


?>