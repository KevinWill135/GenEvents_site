<?php

    session_start();
    include 'db.php';

    if(isset($_GET['id'])) {
        $id = $_GET['id'];
        $id = $conn->real_escape_string($id);

        $sql = "SELECT * FROM events WHERE id = '$id'";
        $result = $conn->query($sql);

        if($result->num_rows > 0) {
            $event = $result->fetch_assoc();
            $date = $event['event_date'];
            $date_replace = date("d-m-Y", strtotime($date));
        } else {
            die ('Evento não encontrado');
            exit;
        }

        $stmt = $conn->prepare("SELECT * FROM batches WHERE event_id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $batch_result = $stmt->get_result();
        $batches = $batch_result->fetch_all(MYSQLI_ASSOC);


    } else {
        header('Location: index.php');
        echo 'Erro ao carregar evento';
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://kit.fontawesome.com/aa07e9ca9b.js" crossorigin="anonymous"></script>
    <title>GenEvents</title>
</head>
<body>
        <!-- Começo do header -->
    <header class="header_container mb-3">
        <section class="d-flex justify-content-center sec_header">
            <nav class="navbar">
                <div class="p-2 div_logo">
                    <img src="../imagens/logo8.jpg" alt="Logo GenEvents" class="img-fluid logo">
                </div>
                <div class="p-2 d-flex link_bar">
                    <ul class="nav justify-content-center">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../html/sobre.html">Sobre</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../html/contactos.html">Contactos</a>
                        </li>
                        <li class="nav-item">
                            <a 
                            class="nav-link <?php
                                    if(!isset($_SESSION['user_id'])) {
                                        echo 'disabled';
                                    }
                                ?>" 
                            href="cart.php">
                                Carrinho
                            </a>
                        </li>
                    </ul>
                    <form id="search_index" action="" method="get" class="d-flex form_search">
                        <div class="d-flex input-group border border-1 border-light rounded-pill">
                            <button id="search-button" type="submit" class="input-group-text bg-white text-black border-0 py-0" aria-label="search submit">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                            <input type="search" name="search" id="search" autocomplete="off" class="form-control text-black border-0" placeholder="Search events">
                        </div>
                    </form>
                </div>
                <div class="p-2 icons">
                    <a href="profile.php">
                        <i class="fa-regular fa-circle-user icon_user"></i>
                    </a>
                    <?php
                        if(isset($_SESSION['user_id'])) {
                            echo '<a href="logout.php" class="btn"><i class="fa-solid fa-arrow-right-from-bracket icon_user"></i></a>';
                        }
                    ?>
                </div>
            </nav>
        </section>
    </header>
        <!-- Fim do header -->
        <!-- Começo da main -->
    <main id="event_main">
        <section id="event" class="container-fluid">
            <div id="event_container">
                <div id="img_div" class="mb-3">
                    <div id="event_img" class="mb-2">
                        <img src="<?= $event['main_img'] ?>" alt="<?= $event['name'] ?>" class="img-fluid">
                    </div>
                    <div id="title_div">
                        <h3><?= $event['name'] ?></h3>
                    </div>
                </div>
                <div id="description">
                    <div id="desc_div">
                        <h4>Descrição do evento:</h4>
                        <p><?= $event['description'] ?></p>
                    </div>
                </div>
                <div id="details_event">
                    <div id="details_div">
                        <p><i class="fa-solid fa-calendar-days"></i> <?= $date_replace ?></p>
                        <p><i class="fa-solid fa-location-dot"></i> <?= $event['location'] ?></p>
                        <p><i class="fa-solid fa-ticket"></i> <?= $event['seats'] ?></p>
                    </div>
                </div>
                <form method="post" id="tickets_form">
                    <?php foreach($batches as $batch): ?>
                    <div class="div_tckts form-label">
                        <label for="batch_<?= $batch['id'] ?>">
                            Lote 
                            <?php 
                                $batch_num = $batch['batch_name'];
                                $batch_replace = str_replace('lote_', '', $batch_num);
                                echo $batch_replace;
                            ?>
                            €<?= $batch['price'] ?>
                        </label>
                        <input 
                            type="number" 
                            name="batch_<?= $batch['id'] ?>" 
                            id="batch_<?= $batch['id'] ?>" 
                            class="qtd_batch form-control mb-3"
                            value="0"
                            min="0" 
                            max="<?= $batch['available_quantity'] ?>" 
                            data-batch-id="<?= $batch['id'] ?>" 
                            data-price="<?= $batch['price'] ?>"
                        >
                    </div>
                    <?php endforeach; ?>
                    <div>
                        <p>Total: €<span id="total">0.00</span></p>
                        <button type="button" id="add_cart" class="btn btn-outline-primary">Comprar</button>
                    </div>
                </form>
            </div>
        </section>
    </main>
        <!-- Fim da main -->
        <!-- Começo do footer -->
    <footer>
        Social media | links | location about others page | contacts
    </footer>
        <!-- Fim do footer -->
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <script src="../javascript/process_event.js"></script>
</body>
</html>