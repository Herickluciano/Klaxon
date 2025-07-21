<?php

// Affichage des erreurs (à désactiver en production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Configuration de la base de données
define('DB_HOST', 'localhost');
define('DB_NAME', 'klaxon');         // ← Le nom réel de ta base de données
define('DB_USER', 'root');           // ← Utilisateur par défaut
define('DB_PASS', '');               // ← Aucun mot de passe


// Chemin de base du projet (utile pour les URLs, redirections, etc.)
define('BASE_URL', '/Klaxon_MVC_PHP/');

// Connexion PDO partagée (optionnel)
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8',
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}

// Inclure automatiquement les classes (auto-chargement)
spl_autoload_register(function ($class) {
    $paths = ['../app/controllers/', '../app/models/', '../app/core/'];
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
return [
    'dsn'      => 'mysql:host=localhost;dbname=klaxon;charset=utf8mb4',
    'user'     => 'root',
    'password' => '',
];
