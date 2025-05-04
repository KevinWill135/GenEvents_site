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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="../../CSS/style.css">
    <link rel="stylesheet" href="../../CSS/admin.css">
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
    <main class="mb-3">
        <div class="container adm_title">
            <h3>Bem-vindo ao Users Cart</h3>
        </div>
        <section id="tb_user_cart" class="container shadow p-3 mb-5 bg-body-tertiary rounded">
            <table id="table_adm_cart" class="table table-light table-hover table-borderless">
                <thead>
                    <tr>
                        <th>Imagem</th>
                        <th>User Id</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody id="userCart_tbody">
                    <!-- Tabela simples dos usuários -->
                </tbody>
            </table>
        </section>
        <section id="adm_cart" class="container">
            <div id="user_cart">
                    <!-- Informações do cart -->
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
    <script src="../../javascript/process_admin.js"></script>
    <script>
        
    </script>
</body>
</html>