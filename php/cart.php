<?php

    session_start();
    include 'db.php';

    if(!isset($_SESSION['user_id'])) {
        header('Location: index.php');
        exit;
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
    <meta name ="author" content="Kevin De Paula">
    <meta name="description" content="GenEvents, site de eventos">
    <meta
      name="keywords"
      content="eventos, events, concertos, festas, sunsets, eventos Portugal, discotecas, festas de verão,  eventos Lisboa, eventos Porto"
    />
    <meta name="robots" content="nosnippet, noarchive, noimgeindex">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://kit.fontawesome.com/aa07e9ca9b.js" crossorigin="anonymous"></script>
    <title>GenEvents - Carrinho</title>
</head>
<body id="body_cart">
    <div id="div_alert"><!-- Mensagem do alert entrará aqui! --></div>
        <!-- Começo do header -->
    <header class="header_container mb-3">
        <section class="d-flex justify-content-center sec_header">
            <nav class="navbar">
                <div class="p-2 div_logo">
                    <a href="index.php">
                        <img src="../imagens/logo8.png" alt="Logo GenEvents" class="img-fluid logo">
                    </a>
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
                <div id="cart_div" class="row">
                    <?php while($row = $result->fetch_assoc()): ?>
                    <div class="card mb-3 col-sm-5 cart_item">
                        <div class="card-header">
                            <p class="d-inline-block" id="title_cart">
                                <?= $row['event_name'] ?>
                            </p>
                            <p class="d-inline-block" id="lote" style="float: right">
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
                            <li class="list-group-item mb-2 last_li">
                                <div class="div_price">
                                    <p class="ticket_price" style="display: inline-block">
                                        Preço Unitário:
                                    </p>
                                    <span class="price_spn">€<?= $row['unit_price'] ?></span>
                                </div>
                                <div class="div_qtd">
                                    <label>Quantidade:</label>
                                    <input 
                                        type="number" 
                                        name="qtd_cart" 
                                        class="qtd_cart" 
                                        data-id="<?= $row['batch_id'] ?>"
                                        data-price="<?= $row['unit_price'] ?>"
                                        data-event-id="<?= $row['event_id'] ?>"
                                        data-user="<?= $_SESSION['user_id'] ?>"
                                        value="<?= $row['quantity'] ?>" 
                                        min="0" 
                                        max="<?= $row['available_quantity'] ?>"
                                    >
                                </div>
                            </li>
                            <li>
                                <button type="button" class="btn btn-outline-danger remover_cart" data-cart-id="<?= $row['cart_id'] ?>">Remover Ticket</button>
                            </li>
                        </ul>
                    </div>
                    <?php endwhile; ?>
                    <div id="buy_div">
                        <button type="submit" class="btn btn-outline-success">Finalizar compra</button>
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
    <footer class="bg-dark text-white mt-5">
        <section class="foot_sct">
            <div class="footer_info container">
                <div class="footer_contct">
                    <h6 class="text-primary">Contactos</h6>                    
                    <ul>
                        <li>
                            <a href="https://linkedin.com">
                                <i class="fa-brands fa-linkedin-in"></i>
                                LinkedIn
                            </a>
                        </li>
                        <li>
                            <a href="https://facebook.com">
                                <i class="fa-brands fa-facebook-f"></i>
                                Facebook
                            </a>
                        </li>
                        <li>
                            <a href="https://instagram.com">
                                <i class="fa-brands fa-instagram"></i>
                                Instagram
                            </a>
                        </li>
                        <li>
                            <i class="fa-solid fa-phone"></i>
                            912667888
                        </li>
                    </ul>
                </div>
                <div class="footer_pages">
                    <h6 class="text-primary">Páginas</h6>
                    <ul>
                        <li>
                            <a href="index.php">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="../html/sobre.html">
                                Mais Sobre Nós
                            </a>
                        </li>
                        <li>
                            <a href="../html/contactos.html">
                                Contactos
                            </a>
                        </li>
                        <li>
                            <a href="../html/login.html">
                                Login
                            </a>
                        </li>
                        <li>
                            <a href="../html/register.html">
                                Registar-te
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="direitosContainer shadow-lg p-3 bg-body-dark rounded">
                <div class="direitos_">&copy;2025-2025 Todos os direitos reservados.</div>
            </div>
        </section>
    </footer>
        <!-- Fim do footer -->
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <script src="../javascript/cart.js"></script>
</body>
</html>