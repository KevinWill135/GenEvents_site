<?php

    session_start();
    include '../db.php';


    if(!isset($_SESSION['user_id']) AND $_SESSION['role'] !== 'admin') {
        header('Location: ../index.php');
        exit;
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = $conn->prepare("SELECT * FROM users");
        $stmt->execute();
        $result = $stmt->get_result();
        $users = [];
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }

        echo json_encode($users);
    }

    //view total_sales_user
    $view_sales = $conn->prepare("SELECT * FROM total_sales_user");
    $view_sales->execute();
    $result_ = $view_sales->get_result();
    $view_sales = [];
    while($total_sales = $result_->fetch_assoc()) {
        $view_sales[] = $total_sales;
    }

    //view order_history
    $view_order = $conn->prepare("SELECT * FROM order_history");
    $view_order->execute();
    $result = $view_order->get_result();
    $order_history = [];
    while($row = $result->fetch_assoc()) {
        $order_history[] = $row;
    }

    $response = [
        'sales' => $view_sales,
        'orders' => $order_history
    ];

    echo json_encode($response);

?>