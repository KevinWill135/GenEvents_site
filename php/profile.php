<?php

    session_start();
    include 'db.php';
    include_once 'class/users.php';

    if(!isset($_SESSION['user_id'])) {
        header('Location: ../html/login.html');
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $user = new Usuario($pdo, $user_id);
    $data = $user->getDados();

    $sql = $conn->prepare("SELECT * FROM order_history WHERE user_id = ?");
    $sql->bind_param('i', $user_id);
    $sql->execute();
    $result = $sql->get_result();

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
    <link rel="stylesheet" href="../CSS/profile.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://kit.fontawesome.com/aa07e9ca9b.js" crossorigin="anonymous"></script>
    <title>GenEvents - Perfil</title>
</head>
<body>
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
                        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin/admin.php">Administração</a>
                            </li>
                        <?php endif; ?>
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
    <main class="d-flex flex-column justify-content-center align-items-center">
        <section class="section_profile mb-5">
            <form action="processing/update_profile.php" method="post" class="row g-3" enctype="multipart/form-data">
                <div class="div_fotoPF">
                    <h4>Foto de perfil</h4>
                    <img src="processing/<?= htmlspecialchars($data['user_img']) ?>" alt="<?= htmlspecialchars($data['username']) ?>" width="200" height="200">
                </div>
                <div id="nova_foto" class="col-sm-12">
                    <label for="user_img" class="form-label img_profile_update">Alterar foto</label>
                    <input type="file" name="user_img" id="user_img" class="form-control" onchange="showFile(this)">
                    <span id="file-name">Nenhum arquivo selecionado</span>
                </div>
                <div class="col-sm-9">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($data['name']) ?>">
                </div>
                <div class="col-sm-9">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($data['username']) ?>">
                </div>
                <div class="col-sm-9">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($data['email']) ?>">
                </div>
                <div class="col-sm-9">
                    <label for="newPassword" class="form-label">Alterar Senha</label>
                    <input type="password" name="newPassword" class="form-control" id="newPassword" placeholder="Alterar Senha">
                </div>
                <div class="col-sm-9">
                    <label for="confirmPassword" class="form-label">Confirmar Senha</label>
                    <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Confirme sua nova senha">
                </div>
                <div class="col-sm-5">
                    <label for="city" class="form-label">Cidade</label>
                    <input type="text" class="form-control" name="city" id="city" value="<?= htmlspecialchars($data['city']) ?>">
                </div>
                <div class="col-sm-5">
                    <label for="country" class="form-label">País</label>
                    <input type="text" class="form-control" name="country" id="country" value="<?= htmlspecialchars($data['country']) ?>">
                </div>
                <div class="col-sm-5">
                    <label for="phone" class="form-label">Telefone de contacto</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="<?= htmlspecialchars($data['phone']) ?>">
                </div>
                <div class="col-sm-5">
                    <label class="form-label" for="event_type">Evento preferido</label>
                    <select class="form-select" id="event_type" name="event_type">
                        <option selected disabled>Escolha...</option>
                        <option value="concertos" <?= $data['event_type'] === 'concertos' ? 'selected' : '' ?>>Concertos</option>
                        <option value="discoteca" <?= $data['event_type'] === 'discoteca' ? 'selected' : '' ?>>Discoteca</option>
                        <option value="sunset" <?= $data['event_type'] === 'sunset' ? 'selected' : '' ?>>Sunset</option>
                    </select>
                </div>
                <div class="col-12">
                    <p id="message_error" class="text-danger"></p>
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </div>
            </form>
        </section>
        <section id="history">
            <div id="history_div">
                <h3 class="mb-4">Histórico de Compras</h3>
                <div class="shadow p-3 mb-5 bg-body-tertiary rounded">
                    <table id="table_hist" class="table table-borderless table-light">
                        <thead>
                            <tr>
                                <th>Evento</th>
                                <th>Lote</th>
                                <th>Quantidade</th>
                                <th>Valor Total</th>
                                <th>Data de Compra</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td data-label="Evento:">
                                    <?= $row['event_name']; ?>
                                </td>
                                <td data-label="Lote:">
                                    <?php
                                        $lote = str_replace('_', ' ', $row['batch_name']);
                                        echo $lote;
                                    ?>
                                </td>
                                <td data-label="Quantidade:">
                                    <?= $row['quantity']; ?>
                                </td>
                                <td data-label="Valor Total:">
                                    <p>
                                        €<?= $row['total_price']; ?>
                                    </p>
                                </td>
                                <td data-label="Data de Compra:">
                                    <?php 
                                        $event_date = date('d/m/Y H:i', strtotime($row['sale_date']));
                                        echo $event_date;
                                    ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>   
                </div>
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
    <script>
        function showFile(input) {
            const fileName = input.files[0]?.name || 'Nenhum arquivo selecionado'
            document.getElementById("file-name").textContent = fileName;
        }
    </script>
</body>
</html>