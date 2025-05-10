<?php

    session_start();
    include '../db.php';

    if(!isset($_SESSION['user_id']) AND isset($_SESSION['role']) !== 'admin') {
        header('Location: ../index.php');
        exit;
    }
    $orders = [];
    if(isset($_GET['search'])) {
        $search = $_GET['search'];
        
        $stmt = $conn->prepare("SELECT * FROM events WHERE name LIKE ? OR location LIKE ? OR event_type LIKE ? OR event_date LIKE ?");
        $likeSearch = "%$search%";
        $stmt->bind_param('ssss', $likeSearch, $likeSearch, $likeSearch, $likeSearch);
        $stmt->execute();
        $result = $stmt->get_result();

        while($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        $stmt->close();
    } else {
        $sql = "SELECT * FROM events";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
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
    <link rel="stylesheet" href="../../CSS/style.css">
    <link rel="stylesheet" href="../../CSS/admin.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://kit.fontawesome.com/aa07e9ca9b.js" crossorigin="anonymous"></script>
    <title>GenEvents - Bem Vindo Admin</title>
</head>
<body class="bg-body-tertiary body_admin">
        <!-- Começo do header -->
    <header id="events_adm_header" class="header_container mb-3 header_admin">
        <section class="d-flex justify-content-center sec_header">
            <nav class="navbar">
                <div class="p-2 div_logo">
                    <a href="../index.php">
                        <img src="../../imagens/logo8.jpg" alt="Logo GenEvents" class="img-fluid logo">
                    </a>
                </div>
                <div id="links_search_events" class="p-2 d-flex link_bar">
                    <div id="events_adm_links" class="div_links">
                        <ul id="events_adm_ul" class="nav justify-content-center">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="../index.php">Home</a>
                            </li>
                            <li class="nav-item dropdown">
                                <button type="button" class="btn btn-outline-dark btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                    Área de Administração
                                </button>
                                <ul class="dropdown-menu dropdown-menu-dark">
                                    <li>
                                        <a class="dropdown-item text-light" href="admin.php">Admin</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-light" href="events_adm.php">Events</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-light" href="users.php">Users</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-light" href="users_cart.php">Users Cart</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <form action="events_adm.php" id="search_events_adm" method="get" class="d-flex form_search">
                        <div class="d-flex input-group border border-1 border-light rounded-pill">
                            <button id="search-button" type="submit" class="input-group-text bg-white text-black border-0 py-0" aria-label="search submit">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                            <input type="search" name="search" id="search" autocomplete="off" class="form-control text-black border-0" placeholder="Search events">
                        </div>
                    </form>
                </div>
                <div class="p-2 icons">
                    <a href="../profile.php">
                        <i class="fa-regular fa-circle-user icon_user"></i>
                    </a>
                    <?php
                        if(isset($_SESSION['user_id'])) {
                            echo '<a href="../logout.php" class="btn"><i class="fa-solid fa-arrow-right-from-bracket icon_user"></i></a>';
                        }
                    ?>
                </div>
            </nav>
        </section>
    </header>
        <!-- Fim do header -->
        <!-- Começo da main -->
    <main class="container mb-3 main_admin">
        <section id="events_adm" class="shadow p-3 mb-5 bg-body-tertiary rounded">
            <div id="tb_events_div">
                <h2 class="events_adm_Hs">Eventos Disponíveis</h2>
                <?php if($orders): ?>
                <table id="events_adm_table" class="table table-light table-hover table-borderless">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Image Event</th>
                            <th>Name</th>
                            <th>Event Date</th>
                            <th>Location</th>
                            <th>Seats</th>
                            <th>Event Type</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="tbody_event_adm">
                        <?php foreach($orders as $order): ?>
                            <tr>
                                <td>
                                    <?= $order['id']; ?>
                                </td>
                                <td>
                                    <img src="../<?= $order['main_img']; ?>" alt="<?= $order['name']; ?>" width="100">
                                </td>
                                <td>
                                    <?= $order['name']; ?>
                                </td>
                                <td>
                                    <?= $order['event_date']; ?>
                                </td>
                                <td>
                                    <?= $order['location']; ?>
                                </td>
                                <td>
                                    <?= $order['seats']; ?>
                                </td>
                                <td>
                                    <?= $order['event_type']; ?>
                                </td>
                                <td>
                                    <button type="button" id="delete_event_adm" data-id="<?= $order['id']; ?>" class="btn btn-danger">Deletar</button>
                                    <button type="button" id="slc_event_adm" data-id="<?= $order['id']; ?>" class="btn btn-outline-dark">Editar</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <div class="alert alert-danger" role="alert">
                        Nenhum evento encontrado.
                    </div>
                <?php endif; ?>
            </div>
        </section>
        <div class="container mb-5">            
            <section id="edit_event" class="shadow-lg p-3 mb-5 bg-body-tertiary rounded" style="display: none;">
                <h3 id="title_edit_event" class="events_adm_Hs">Editar Eventos</h3>
                <div id="div_edit_event">
                    <!-- Evento para editar -->
                </div>
            </section>
            <section id="create_event" class="shadow-lg p-3 mb-5 bg-body-tertiary rounded">
                <h3 class="events_adm_Hs">Criar Evento</h3>
                <div id="div_create">
                    <form action="../processing/create_event.php" method="post" class="row g-3" enctype="multipart/form-data">
                        <div class="col-12">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" name="name" id="name" class="form-control" aria-describedby="text_name" required>
                            <div id="text_name" class="form-text">
                                Nome do Evento.
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea name="description" id="description" class="form-control" aria-describedby="text_description" required></textarea>
                            <div id="text_description" class="form-text">
                                Descrição do evento.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="event_date" class="form-label">date</label>
                            <input type="date" name="event_date" id="event_date" class="form-control" aria-describedby="text_date" required>
                            <div id="text_date" class="form-text">
                                Data do evento.
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="location" class="form-label">Localização</label>
                            <input type="text" name="location" class="form-control" id="location" aria-describedby="text_location" required>
                            <div id="text_location" class="form-text">
                                Localização do evento.
                            </div>
                        </div>
                        <div class="col-5">
                            <label for="seats" class="form-label">Lotação do evento</label>
                            <input type="number" class="form-control" name="seats" id="seats" aria-describedby="text-seats" required>
                            <div id="text-seats" class="form-text">
                                Lotação disponivel para o evento.
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <label class="form-label" for="event_type">Tipo do evento</label>
                            <select class="form-select" id="event_type" name="event_type" aria-describedby="text_slcEvent" required>
                                <option selected disabled>Escolha...</option>
                                <option value="concertos">Concertos</option>
                                <option value="discoteca">Discoteca</option>
                                <option value="sunset">Sunset</option>
                            </select>
                            <div id="text_slcEvent" class="form-text">
                                O tipo do evento.
                            </div>
                        </div>
                        <div class="col-12">
                            <label for="event_img" class="form-label">Cartaz do evento</label>
                            <input type="file" name="event_img" id="event_img" class="form-control" aria-describedby="text_img" required>
                            <div id="text_img" class="form-text">
                                Escolha o cataz do evento.
                            </div>
                        </div>
                        <div class="col-12">
                            <p id="message_error" class="text-danger"></p>
                            <button type="submit" class="btn btn-primary">Editar</button>
                        </div>
                    </form>
                </div>
            </section>
        </div>
    </main>
        <!-- Fim da main -->
        <!-- Começo do footer -->
    <footer class="bg-dark text-white mt-5 admin_footer">
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
                            <a href="../index.php">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="../../html/sobre.html">
                                Mais Sobre Nós
                            </a>
                        </li>
                        <li>
                            <a href="../../html/contactos.html">
                                Contactos
                            </a>
                        </li>
                        <li>
                            <a href="../../html/login.html">
                                Login
                            </a>
                        </li>
                        <li>
                            <a href="../../html/register.html">
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
    <script src="../../javascript/events_adm.js"></script>
</body>
</html>