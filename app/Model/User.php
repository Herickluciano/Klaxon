<?php

class User {
    public function getAll() {
        // Utilise $pdo défini dans config.php
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
