<?php

    session_start();
    include_once 'db.php';
    
    $events = $conn->query("SELECT * FROM events")


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
    <header class="header_container mb-3 shadow p-3 mb-5 bg-body-tertiary rounded">
        <section class="d-flex justify-content-center sec_header">
            <nav class="navbar navbar-expand-lg">
                <div class="p-2 div_logo">
                    <a href="index.php" class="link_logo">
                        <img src="../imagens/logo8.png" alt="Logo GenEvents" class="img-fluid logo">
                    </a>
                </div>
                <div class="p-2 d-flex link_bar">
                    <div class="div_links">
                        <button class="navbar-toggler btn_collpase" type="button" data-bs-toggle="collapse" data-bs-target="#link_collapse" aria-controls="link_collapse" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <ul class="nav justify-content-center collapse navbar-collapse" id="link_collapse">
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
                                class="nav-link<?php
                                        if(!isset($_SESSION['user_id'])) {
                                            echo ' disabled';
                                        }
                                    ?>" 
                                href="cart.php">
                                    Carrinho
                                </a>
                            </li>
                        </ul>
                    </div>                   
                    <form id="search_index" method="get" class="d-flex form_search">
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
        <section id="first_section" class="container-fluid mb-2">
            <div id="wallpaper_div" class="shadow-lg p-3 mb-5 bg-body-tertiary rounded">
                <h3 style="color: white">Seja Bem-Vindo</h3>
            </div>
        </section>
        <section id="search_section" class="container">
            <div id="text_search">
                <h3 id="h3_search"><!-- Título colocado dinamicamente com JS --></h3>
            </div>
            <div id="search_div" class="row">
                    <!-- Resultado do search -->
            </div>
        </section>
                    <!-- Eventos -->
        <section id="section_events" class="container">
            <div id="events_div" class="row">
                <?php foreach($events as $event): ?>
                <div id="card_div_container" class="col-sm-3 mb-3">
                    <div class="card">
                        <a href="event.php?id=<?= $event['id'] ?>">
                            <div 
                                style=
                                "
                                    position: relative;
                                    background-image: url('<?= $event['main_img'] ?>');
                                    background-repeat: no-repeat;
                                    background-size: cover;
                                    background-position: center;
                                "
                            >
                                <div class="div_img_index">
                                    <img src="<?= $event['main_img'] ?>" class="card-img-top img_card" alt="Imagem <?= $event['name'] ?>">
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <h5 class="card-title"><?= $event['name'] ?></h5>
                                <p class="card-text">
                                    <i class="fa-solid fa-calendar-days"></i>
                                    <?php 
                                        $date = date('d/m/Y', strtotime($event['event_date']));
                                        echo $date; 
                                    ?>
                                </p>
                                <p class="card-text">
                                    <i class="fa-solid fa-location-dot"></i> 
                                    <?= $event['location'] ?>
                                </p>
                            </div>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
                    <!-- FIM Eventos -->
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
                <div class="direitosContainer shadow-lg p-3 bg-body-dark rounded">
                    <div class="direitos_">&copy;2025-2025 Todos os direitos reservados.</div>
                </div>
            </div>
        </section>
    </footer>
        <!-- Fim do footer -->
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <script src="../javascript/process_index.js"></script>
</body>
</html>