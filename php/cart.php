<?php

    session_start();
    include 'db.php';

    if(!isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit;
    }

    if($_SERVER['REQUEST_METHOD'] === 'POST') {

    }

    $user_id = $_SESSION['user_id'];
    $sql = "
        SELECT
            c.quantity,
            c.id AS cart_id,
            b.id AS batch_id,
            b.batch_name,
            c.quantity * b.price as total_price,
            b.price AS unit_price,
            b.start_date,
            b.end_date,
            b.available_quantity,
            e.id AS event_id,
            e.name AS event_name
        FROM cart c
        JOIN batches b ON c.batch_id = b.id
        JOIN events e ON b.event_id = e.id
        WHERE c.user_id = ?
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    

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
<body id="body_cart">
    <div id="div_alert"><!-- Mensagem do alert entrará aqui! --></div>
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
                            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../html/sobre.html">Sobre</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../html/contactos.html">Contactos</a>
                        </li>
                        <li class="nav-item">
                            <a 
                            class="nav-link cart_link<?php
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
    <main class="mb-3">
        <section id="cart_section" class="container">
            <?php if($result): ?>
            <form id="cart_form">
                <div id="cart_div">
                    <?php while($row = $result->fetch_assoc()): ?>
                    <div class="card mb-3">
                        <div class="card-header">
                            <p class="d-inline-block" id="title_cart">
                                <?= $row['event_name'] ?>
                            </p>
                            <p class="d-inline-block" id="lote">
                                Lote <?php 
                                        $lote = str_replace('lote_', '', $row['batch_name']);
                                        echo $lote;
                                    ?>
                            </p>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <?php 
                                    $start_date = date("d M H:i", strtotime($row['start_date']));
                                    echo $start_date;
                                ?>
                                Até
                                <?php
                                    $end_date = date("d M H:i", strtotime($row['end_date']));
                                    echo $end_date;
                                ?>
                            </li>
                            <li class="list-group-item">
                                    <input 
                                        type="number" 
                                        name="qtd_cart" 
                                        class="qtd_cart" 
                                        data-id="<?= $row['batch_id'] ?>"
                                        data-price="<?= $row['unit_price'] ?>"
                                        data-event-id="<?= $row['event_id'] ?>"
                                        value="<?= $row['quantity'] ?>" 
                                        min="0" 
                                        max="<?= $row['available_quantity'] ?>"
                                    >
                                    <p class="ticket_price" style="display: inline-block">
                                        Preço Unitário: €
                                        <span class="price_spn"><?= $row['unit_price'] ?></span>
                                    </p>
                            </li>
                            <li>
                                <button type="button" class="btn btn-outline-danger remover_cart" data-cart-id="<?= $row['cart_id'] ?>">Remover Ticket</button>
                            </li>
                        </ul>
                    </div>
                    <?php endwhile; ?>
                    <div id="buy_div">
                        <button type="submit" class="btn btn-outline-success">Comprar</button>
                        <span id="total">
                            <p class="d-inline-block">Total: €</p>
                            <p id="total_ticket" class="d-inline-block"></p>
                        </span>
                    </div>
                </div>
            </form>
            <?php endif; ?>
        </section>
    </main>
        <!-- Fim da main -->
        <!-- Começo do footer -->
    <footer>
        Social media | links | location about others page | contacts
    </footer>
        <!-- Fim do footer -->
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <script src="../javascript/cart.js"></script>
</body>
</html>