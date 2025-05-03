<?php

    session_start();
    include '../db.php';
    require_once '../class/users.php';

    if(isset($_POST['id_selected'])) {
        $user_id = intval($_POST['id_selected']);
        $user = new Usuario($pdo, $user_id);
        $data = $user->getDados();

        if($user) {
            ?>
                <form id="form_adm" action="../processing/update_profile.php" method="post" class="row g-3" enctype="multipart/form-data">
                    <div class="">
                        <h4>Foto de perfil</h4>
                        <img src="../processing/<?= htmlspecialchars($data['user_img']) ?>" alt="<?= htmlspecialchars($data['username']) ?>" width="200" height="200">
                    </div>
                    <div class="col-sm-6">
                        <label for="user_img" class="form-label">Foto de perfil</label>
                        <input type="file" name="user_img" id="user_img" class="form-control">
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
                        <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder="Apartment, studio, or floor">
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
                    <div class="col-sm-5">
                        <label for="role" class="form-label">Role</label>
                        <input type="text" class="form-control" name="role" id="role" value="<?= htmlspecialchars($data['role']) ?>">
                    </div>
                    <div class="col-12">
                        <p id="message_error" class="text-danger"></p>
                        <button type="submit" class="btn btn-primary">Atualizar</button>
                    </div>
                </form>
                <script>
                    /*$(document).on('submit', '#update_user_adm', function(e) {
                        e.preventDefault()

                        const formData = new FormData(this);
                        $.ajax({
                            url: '../processing/update_profile.php',
                            method: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                alert(response)
                                window.location.href = 'users.php';
                            },
                            error: function(xhr, status, error) {
                                console.log('Error: ' + error)
                                console.log('Status: ' + status)
                                console.log('XHR: ' + xhr)
                            }
                        })
                    })*/
                </script>
            <?php
        }
    }

?>