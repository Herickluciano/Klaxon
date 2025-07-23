<?php
$file = __DIR__ . '/exports/trajets.json';
file_put_contents($file, $json);

// Connexion à la BDD
try {
    $pdo = new PDO('mysql:host=localhost;port=3306;dbname=klaxon;charset=utf8;', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer les trajets
$sql = "SELECT * FROM t_trajet ORDER BY id DESC";
$stmt = $pdo->query($sql);
$trajets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Vérification
if (!$trajets) {
    die("Aucun trajet trouvé.");
}

// Encodage en JSON
$json = json_encode($trajets, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

// Emplacement du fichier
$filepath = __DIR__ . '/exports/trajets.json';

// Enregistrement
file_put_contents($filepath, $json);

// Redirection ou message de succès
echo "Fichier JSON exporté avec succès : <a href='exports/trajets.json' target='_blank'>Télécharger</a>";
