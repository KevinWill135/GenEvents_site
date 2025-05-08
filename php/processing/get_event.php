<?php

    session_start();
    include '../db.php';

    if(!isset($_SESSION['user_id']) AND isset($_SESSION['role']) !== 'admin') {
        header('Location: ../index.php');
        exit;
    }

    $event_id = $_POST['event_id'];
    $stmt = $conn->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->bind_param('i', $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $tb_event = $result->fetch_assoc();

?>
            <form id="form_edit_event" method="post" class="row g-3" enctype="multipart/form-data">
                <div class="col-12">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" name="name" id="name" class="form-control" aria-describedby="text_name" value="<?= $tb_event['name']; ?>">
                    <input type="hidden" name="event_id" id="event_id" value="<?= $event_id ?>">
                    <div id="text_name" class="form-text">
                        Nome do Evento.
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="description" class="form-label">Descrição</label>
                    <textarea name="description" id="description" class="form-control" aria-describedby="text_description"><?= $tb_event['description']; ?></textarea>
                    <div id="text_description" class="form-text">
                        Descrição do evento.
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="event_date" class="form-label">date</label>
                    <input type="date" name="event_date" id="event_date" class="form-control" aria-describedby="text_date" value="<?= $tb_event['event_date']; ?>">
                    <input type="hidden" name="visibility_date" id="visibility_date" value="<?= $tb_event['event_date']; ?>">
                    <div id="text_date" class="form-text">
                        Data do evento.
                    </div>
                </div>
                <div class="col-12">
                    <label for="location" class="form-label">Localização</label>
                    <input type="text" name="location" class="form-control" id="location" value="<?= $tb_event['location']; ?>" aria-describedby="text_location">
                    <div id="text_location" class="form-text">
                        Localização do evento.
                    </div>
                </div>
                <div class="col-12">
                    <label for="seats" class="form-label">Lotação do evento</label>
                    <input type="number" class="form-control" name="seats" id="seats" value="<?= $tb_event['seats']; ?>" aria-describedby="text-seats">
                    <div id="text-seats" class="form-text">
                        Lotação disponivel para o evento.
                    </div>
                </div>
                <div class="col-sm-4">
                    <label class="form-label" for="event_type">Tipo do evento</label>
                    <select class="form-select" id="event_type" name="event_type" aria-describedby="text_slcEvent">
                        <option selected disabled>Escolha...</option>
                        <option value="concertos" <?= $tb_event['event_type'] === 'concertos' ? 'selected' : '' ?>>Concertos</option>
                        <option value="discoteca" <?= $tb_event['event_type'] === 'discoteca' ? 'selected' : '' ?>>Discoteca</option>
                        <option value="sunset" <?= $tb_event['event_type'] === 'sunset' ? 'selected' : '' ?>>Sunset</option>
                    </select>
                    <div id="text_slcEvent" class="form-text">
                        O tipo do evento.
                    </div>
                </div>
                <div class="col-sm-12">
                    <label for="event_img" class="form-label">Cartaz do evento</label>
                    <input type="file" name="event_img" id="event_img" class="form-control" aria-describedby="text_img">
                    <div id="text_img" class="form-text">
                        Escolha o cataz do evento.
                    </div>
                </div>
                <div class="col-12">
                    <p id="message_error" class="text-danger"></p>
                    <button type="submit" class="btn btn-primary">Editar</button>
                </div>
            </form>