<?php

    include '../db.php';

    //search events
    if(strlen($_POST['search']) <= 2) {
        exit;
    }
    if(isset($_POST['search'])) {
        $search = trim($_POST['search'] ?? '');

        $sql = $conn->prepare("SELECT * FROM events WHERE name LIKE ? OR description LIKE ? OR event_date LIKE ? OR location LIKE ? OR event_type LIKE ?");
        $likeSearch = "%$search%";
        $sql->bind_param('sssss', $likeSearch, $likeSearch, $likeSearch, $likeSearch, $likeSearch);
        $sql->execute();
        $result = $sql->get_result();

        if($result) {
            foreach($result as $event) {
                echo "
                    <div class='col-sm-2 card_search_result'>
                        <div class='card'>
                            <a href=\"event.php?id={$event['id']}\">
                                <img src=\"{$event['main_img']}\" class='card-img-top img-fluid' alt=\"Imagem {$event['name']}\">
                                <div class='card-body'>
                                    <h5 class=\"card-title\">{$event['name']}</h5>
                                    <p class=\"card-text\">{$event['event_date']}</p>
                                    <p class=\"card-text\">{$event['location']}</p>
                                </div>
                            </a>
                        </div>
                    </div>
                ";
            }
        }

    } else {
        echo "<p class=\"text-danger\">Nenhum evento encontrado.</p>";
    }

?>