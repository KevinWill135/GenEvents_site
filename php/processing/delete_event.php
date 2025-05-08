<?php

    session_start();
    include '../db.php';

    if(!isset($_SESSION['user_id']) AND isset($_SESSION['role']) !== 'admin') {
        header('Location: ../index.php');
        exit;
    }

    $event_id = $_POST['event_id'];
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param('i', $event_id);
    $stmt->execute();
    $stmt->close();

    echo json_encode(['success' => true, 'message' => 'Evento deletado com sucesso!']);

?>