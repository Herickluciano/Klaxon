<?php
try {
    $con = new PDO('mysql:host=localhost;dbname=klaxon;charset=utf8', 'root', '');
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}