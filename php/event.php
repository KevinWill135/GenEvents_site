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
    <title>GenEvents - Evento <?= $event['name'] ?></title>
</head>
<body id="event_body">
        <!-- Começo do header -->
    <header id="event_header" class="header_container mb-3">
        <section class="d-flex justify-content-center sec_header">
            <nav class="navbar">
                <div class="p-2 div_logo">
                    <a href="index.php">
                        <img src="../imagens/logo_event.png" alt="Logo GenEvents" class="img-fluid logo">    
                    </a>
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
            <div id="event_container" class="mt-3">
                <div id="img_div" class="mb-3">
                    <div id="event_img" class="mb-2">
                        <img src="<?= $event['main_img'] ?>" alt="<?= $event['name'] ?>" class="card-img-top img_card">
                    </div>
                    <div id="title_div">
                        <h3><?= $event['name'] ?></h3>
                    </div>
                </div>
                <div id="event_description">
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
                        <?php if($batch['available_quantity'] == 0) {
                            $quantity = 'disabled';
                        } else {
                            $quantity = '';
                        } ?>
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
                            <?= $quantity ?>
                        >
                    </div>
                    <?php endforeach; ?>
                    <div>
                        <p>Total: €<span id="total">0.00</span></p>
                        <button type="button" id="add_cart" class="btn btn-outline-light">Comprar</button>
                    </div>
                </form>
            </div>
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
    <script src="../javascript/process_event.js"></script>
</body>
</html>