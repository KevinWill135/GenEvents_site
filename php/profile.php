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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/profile.css">
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
    <main class="d-flex justify-content-center align-items-center">
        <section class="section_profile">
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
    </main>
        <!-- Fim da main -->
        <!-- Começo do footer -->
    <footer>
        Social media | links | location about others page | contacts
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