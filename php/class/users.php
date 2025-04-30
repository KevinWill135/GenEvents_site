<?php

    class Usuario {
        private $pdo;
        private $id;

        public function __construct($pdo, $id) {
            $this->pdo = $pdo;
            $this->id = $id;
        }

        public function updateImg($photo) {
            if($photo['error'] === 0) {
                $ext = pathinfo($photo['name'], PATHINFO_EXTENSION);
                $photoName = basename($photo['name']);
                $destiny = __DIR__ . '/../processing/uploads/' . $photoName;

                if(move_uploaded_file($photo['tmp_name'], $destiny)) {
                    $stmt = $this->pdo->prepare("UPDATE users SET user_img = ? WHERE id = ?");
                    return $stmt->execute(['uploads/'.$photoName, $this->id]);
                }
            }
            return false;
        }

        public function atualizar($data) {
            $campos = [];
            $valores = [];

            foreach($data as $campo => $valor) {
                if(!empty($valor)) {
                    if($campo === 'confirmPassword') {
                        $valor = password_hash($valor, PASSWORD_DEFAULT);
                        $campos[] = "password = ?";
                        $valores[] = $valor;
                        continue;
                    }

                    $campos[] = "$campo = ?";
                    $valores[] = $valor;
                }
            }

            if(count($campos) > 0) {
                $valores[] = $this->id;
                $sql = "UPDATE users SET " . implode(', ', $campos) . " WHERE id = ?";
                $stmt = $this->pdo->prepare($sql);
                return $stmt->execute($valores);
            }

            return false;
        }

        public function getDados() {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$this->id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }

?>