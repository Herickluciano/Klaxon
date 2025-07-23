<?php
session_start();

// Connexion à la base de données
try {
    $con = new PDO('mysql:host=localhost;port=3306;dbname=klaxon;charset=utf8;', 'root', '');
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$message = "";

// Vérification de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    // Vérifie que tous les champs sont remplis
    $requiredFields = ['depart', 'date_depart', 'heure_depart', 'destination', 'date_arrivee', 'heure_arrivee', 'place'];
    $missing = array_filter($requiredFields, fn($field) => empty(trim($_POST[$field])));

    if (empty($missing)) {
        // Nettoyage des données
        $depart = htmlspecialchars(trim($_POST['depart']));
        $date_depart = $_POST['date_depart'];
        $heure_depart = $_POST['heure_depart'];
        $destination = htmlspecialchars(trim($_POST['destination']));
        $date_arrivee = $_POST['date_arrivee'];
        $heure_arrivee = $_POST['heure_arrivee'];
        $place = (int) $_POST['place'];

        if ($place <= 0) {
            $message = "<div class='alert alert-warning'>⚠️ Le nombre de places doit être supérieur à zéro.</div>";
        } else {
            // Vérifie si le trajet existe déjà
            $check = $con->prepare('
                SELECT COUNT(*) FROM t_trajet 
                WHERE depart = ? AND date_depart = ? AND heure_depart = ? 
                  AND destination = ? AND date_arrivee = ? AND heure_arrivee = ? AND place = ?
            ');
            $check->execute([$depart, $date_depart, $heure_depart, $destination, $date_arrivee, $heure_arrivee, $place]);

            if ($check->fetchColumn() > 0) {
                $message = "<div class='alert alert-danger'>❌ Ce trajet existe déjà.</div>";
            } else {
                // Insertion dans la base
                $stmt = $con->prepare('
                    INSERT INTO t_trajet (depart, date_depart, heure_depart, destination, date_arrivee, heure_arrivee, place)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ');
                $stmt->execute([$depart, $date_depart, $heure_depart, $destination, $date_arrivee, $heure_arrivee, $place]);

                $_SESSION['id_trajet'] = $con->lastInsertId();

                // Redirection
                header('Location: liste_admin.php?success=1');
                exit();
            }
        }
    } else {
        $message = "<div class='alert alert-warning'>⚠️ Veuillez remplir tous les champs.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Trajet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Ajouter un trajet</h4>
        </div>
        <div class="card-body">
            <?= $message ?>

            <form method="post" novalidate>
                <div class="row mb-3">
                    <div class="col">
                        <label for="depart" class="form-label">Départ</label>
                        <input type="text" class="form-control" id="depart" name="depart" required>
                    </div>
                    <div class="col">
                        <label for="date_depart" class="form-label">Date de départ</label>
                        <input type="date" class="form-control" id="date_depart" name="date_depart" required>
                    </div>
                    <div class="col">
                        <label for="heure_depart" class="form-label">Heure de départ</label>
                        <input type="time" class="form-control" id="heure_depart" name="heure_depart" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="destination" class="form-label">Destination</label>
                        <input type="text" class="form-control" id="destination" name="destination" required>
                    </div>
                    <div class="col">
                        <label for="date_arrivee" class="form-label">Date d'arrivée</label>
                        <input type="date" class="form-control" id="date_arrivee" name="date_arrivee" required>
                    </div>
                    <div class="col">
                        <label for="heure_arrivee" class="form-label">Heure d'arrivée</label>
                        <input type="time" class="form-control" id="heure_arrivee" name="heure_arrivee" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="place" class="form-label">Nombre de places</label>
                    <input type="number" class="form-control" id="place" name="place" required min="1">
                </div>

                <button type="submit" name="submit" class="btn btn-success w-100">Ajouter le trajet</button>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
