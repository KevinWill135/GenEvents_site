<?php

    session_start();
    include '../db.php';
    header('Content-Type: application/json');

    if(!isset($_POST['cart_id'])) {
        echo json_encode(['success' => false, 'message' => 'Dados enviados incorretamente']);
        exit;
    }
    
    if($_SERVER['REQUEST_METHOD'] === 'POST' AND isset($_POST['cart_id'])) {
        //removendo ticket do carrinho
       $cart_id = intval($_POST['cart_id']);
       $sql = $conn->prepare("DELETE FROM cart WHERE id = ?");
       $sql->bind_param('i', $cart_id);
       if($sql->execute()) {
           echo json_encode(['success' => true, 'message' => 'Removido com sucesso']);
       } else {
           echo json_encode(['success' => false, 'message' => 'Erro ao remover do carrinho']);
       }
   }

?>