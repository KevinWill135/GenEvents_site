<?php

    function hashPassword($senha) {
        return password_hash($senha, PASSWORD_DEFAULT);
    }

?>