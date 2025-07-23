<?php
session_start();

// Connexion à la base de données
try {
    $con = new PDO('mysql:host=localhost;port=3306;dbname=klaxon;charset=utf8;', 'root', '');
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$editId = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);
$deleteId = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
$message = "";

// Suppression d'un agent
if ($deleteId) {
    $stmt = $con->prepare('DELETE FROM t_agent WHERE id = ?');
    $stmt->execute([$deleteId]);
    $_SESSION['flash'] = "✅ Agent supprimé avec succès.";
    header('Location: liste_admin.php');
    exit();
}

// Mise à jour des données
if (isset($_POST['update']) && isset($_POST['id'])) {
    $id     = (int) $_POST['id'];
    $auteur = trim($_POST['auteur']);
    $tel    = trim($_POST['tel']);
    $mail   = trim($_POST['mail']);
    $place  = (int) $_POST['place'];

    if ($auteur && $tel && $mail && $place > 0) {
        $stmt = $con->prepare('UPDATE t_agent SET auteur = ?, tel = ?, mail = ?, place = ? WHERE id = ?');
        $stmt->execute([$auteur, $tel, $mail, $place, $id]);
        $_SESSION['flash'] = "✅ Mise à jour réussie.";
        header('Location: liste_admin.php');
        exit();
    } else {
        $message = "⚠️ Tous les champs sont obligatoires et le nombre de places doit être > 0.";
    }
}

// Récupération des données de l’agent à éditer
$agentToEdit = null;
if ($editId) {
    $stmt = $con->prepare('SELECT * FROM t_agent WHERE id = ?');
    $stmt->execute([$editId]);
    $agentToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Récupération de tous les agents
$stmt = $con->query('SELECT * FROM t_agent ORDER BY id DESC');
$agents = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des agents</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Klaxon_MVC_PHP/public/css/style.css">
</head>
<body>

<h2 style="text-align:center;">Liste des agents</h2>

<!-- Message flash -->
<?php if (!empty($_SESSION['flash'])): ?>
    <p class="msg success"><?= htmlspecialchars($_SESSION['flash']) ?></p>
    <?php unset($_SESSION['flash']); ?>
<?php endif; ?>

<!-- Message d'erreur -->
<?php if ($message): ?>
    <p class="msg error"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<!-- Tableau des agents -->
<?php if (!empty($agents)): ?>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Auteur</th>
            <th>Téléphone</th>
            <th>Email</th>
            <th>Places</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($agents as $agent): ?>
        <tr>
            <td><?= htmlspecialchars($agent['id']) ?></td>
            <td><?= htmlspecialchars($agent['auteur']) ?></td>
            <td><?= htmlspecialchars($agent['tel']) ?></td>
            <td><?= htmlspecialchars($agent['mail']) ?></td>
            <td><?= htmlspecialchars($agent['place']) ?></td>
            <td>
                <a class="btn edit" href="?edit=<?= $agent['id'] ?>">✏️ Éditer</a>
                <a class="btn del" href="?delete=<?= $agent['id'] ?>" onclick="return confirm('Supprimer cet agent ?');">🗑️ Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
    <p style="text-align:center;">Aucun agent enregistré.</p>
<?php endif; ?>

<!-- Formulaire d'édition -->
<?php if ($agentToEdit): ?>
<div class="form-container">
    <form method="post">
        <h3>Édition de l’agent #<?= $agentToEdit['id'] ?></h3>
        <input type="hidden" name="id" value="<?= $agentToEdit['id'] ?>">

        <label>Auteur</label>
        <input type="text" name="auteur" value="<?= htmlspecialchars($agentToEdit['auteur']) ?>" required>

        <label>Téléphone</label>
        <input type="text" name="tel" value="<?= htmlspecialchars($agentToEdit['tel']) ?>" required>

        <label>Email</label>
        <input type="email" name="mail" value="<?= htmlspecialchars($agentToEdit['mail']) ?>" required>

        <label>Nombre de places</label>
        <input type="number" name="place" value="<?= htmlspecialchars($agentToEdit['place']) ?>" min="1" required>

        <input type="submit" name="update" value="💾 Enregistrer les modifications">
    </form>
</div>
<?php endif; ?>

</body>
</html>
