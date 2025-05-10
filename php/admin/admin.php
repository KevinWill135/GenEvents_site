<?php

    session_start();
    include '../db.php';


    if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
        header('Location: ../index.php');
        exit;
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
    <header class="header_container mb-3 header_admin">
        <section class="d-flex justify-content-center sec_header">
            <nav class="navbar">
                <div class="p-2 div_logo">
                    <a href="../index.php">
                        <img src="../../imagens/logo8.jpg" alt="Logo GenEvents" class="img-fluid logo">
                    </a>
                </div>
                <div class="p-2 d-flex link_bar">
                    <ul class="nav justify-content-center">
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
    <main class="mb-3 main_admin">
        <section id="view_sales" class="container shadow p-3 mb-5 bg-body-tertiary rounded">
            <div>
                <table class="table table-light table-hover table-borderless">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Total Sales</th>
                        </tr>
                    </thead>
                    <tbody id="total_sales">
                            <!-- View está AQUI!! -->
                    </tbody>
                </table>
            </div>
        </section>
        <section id="view_order" class="container shadow p-3 mb-5 bg-body-tertiary rounded">
            <div>
                <table class="table table-light table-hover table-borderless">
                    <thead>
                        <tr>
                            <th>Sale ID</th>
                            <th>User ID</th>
                            <th>Username</th>
                            <th>Event Name</th>
                            <th>Batch Name</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                            <th>Sale Date</th>
                        </tr>
                    </thead>
                    <tbody id="order_history">
                            <!-- View está AQUI!! -->
                    </tbody>
                </table>
            </div>
        </section>
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
    <script src="../../javascript/process_admin.js"></script>
</body>
</html>