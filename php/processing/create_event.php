<?php

    session_start();
    include '../db.php';
    header('Location: ../admin/events_adm.php');

    if(!isset($_SESSION['user_id']) AND isset($_SESSION['role']) !== 'admin') {
        header('Location: ../index.php');
        exit;
    }

    if(isset($_POST)) {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $event_date = trim($_POST['event_date']);
        $location = trim($_POST['location']);
        $seats = intval($_POST['seats']);
        $event_type = trim($_POST['event_type']);
        $main_img = '';

        if(isset($_FILES['event_img']) && $_FILES['event_img']['error'] === UPLOAD_ERR_OK) {
            $img_event = $_FILES['event_img'];

            $destiny = dirname(__FILE__, 3) . '/img_events/' . basename($img_event['name']);

            if(move_uploaded_file($img_event['tmp_name'], $destiny)) {
                $img_db = '../img_events/' . basename($img_event['name']);
                $main_img = $img_db;
            }
        }

        $stmt = $conn->prepare("INSERT INTO events(name, description, event_date, location, seats, main_img, event_type) VALUES(?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssiss', $name, $description, $event_date, $location, $seats, $main_img, $event_type);
        $stmt->execute();
    }
?>