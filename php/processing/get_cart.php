<?php

    include '../db.php';

    $user_id = $_POST['user_id'];
    $sql = "
        SELECT
            c.quantity,
            c.id AS cart_id,
            b.id AS batch_id,
            b.batch_name,
            c.quantity * b.price AS total_price,
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

    <?php if($result && $result->num_rows > 0): ?>
        <form id="form_cart_admin">
                <div id="cart_div" class="row">
                    <?php while($row = $result->fetch_assoc()): ?>
                    <div class="card mb-3 col-sm-5 cart_item cart_adm">
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
                                        name="qtd_cart_admin" 
                                        class="qtd_cart_admin" 
                                        data-id="<?= $row['batch_id'] ?>"
                                        data-price="<?= $row['unit_price'] ?>"
                                        data-event-id="<?= $row['event_id'] ?>"
                                        data-user="<?= $_POST['user_id'] ?>"
                                        value="<?= $row['quantity'] ?>" 
                                        min="0" 
                                        max="<?= $row['available_quantity'] ?>"
                                    >
                                </div>
                                <span class="total_spn_adm">
                                    <p>Total: €</p>
                                    <p class="total_adm"><?= $row['total_price'] ?></p>
                                </span>
                            </li>
                            <li>
                                <button type="button" class="btn btn-outline-danger remover_cart_admin" data-cart-id="<?= $row['cart_id'] ?>">Remover Ticket</button>
                            </li>
                        </ul>
                    </div>
                    <?php endwhile; ?>
                </div>
            </form>
    <?php else: ?>
        <div id="empty_div">
            <h4 id="empty_cart">Carrinho está vazio! <span id="danger_icon"><i class="fa-solid fa-triangle-exclamation"></i></span></h4>
        </div>
    <?php endif; ?>

