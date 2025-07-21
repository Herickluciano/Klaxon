<?php
session_start();

try {
    $con = new PDO('mysql:host=localhost;port=3306;dbname=klaxon;charset=utf8;', 'root', '');
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$editId = filter_input(INPUT_GET, 'edit', FILTER_VALIDATE_INT);
$deleteId = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
$msg = "";

/* Suppression */
if ($deleteId) {
    $stmt = $con->prepare('DELETE FROM t_agent WHERE id = ?');
    $stmt->execute([$deleteId]);
    header('Location: liste_admin.php');
    exit();
}

/* Mise à jour */
if (isset($_POST['update']) && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    $auteur = trim($_POST['auteur']);
    $tel = trim($_POST['tel']);
    $mail = trim($_POST['mail']);
    $place = (int)$_POST['place'];

    if ($auteur !== '' && $tel !== '' && $mail !== '' && $place > 0) {
        $stmt = $con->prepare('UPDATE t_trajet SET auteur = ?, tel = ?, mail = ?, place = ? WHERE id = ?');
        $stmt->execute([$auteur, $tel, $mail, $place, $id]);
        header('Location: liste_admin.php');
        exit();
    } else {
        $msg = "Tous les champs sont obligatoires et le nombre de place doit être supérieur à zéro.";
    }
}

/* Récupération de la liste */
$stmt = $con->prepare('SELECT id, auteur, tel, mail, place FROM t_agent WHERE id = ?');
$stmt->execute([$editId]);
$toEdit = $stmt->fetch(PDO::FETCH_ASSOC);


/*  édite */
$toEdit = null;
if ($editId) {
    $stmt = $con->prepare('SELECT id, auteur, tel, mail, place FROM t_agent WHERE id = ?');
    $stmt->execute([$editId]);
    $toEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}
$stmt = $con->prepare('SELECT * FROM t_agent ORDER BY id DESC');
$stmt->execute();
$trajets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des agent</title>
    <link rel="stylesheet" href="/Klaxon_MVC_PHP/public/css/style.css">
</head>
<body>

<h2 style="text-align:center;">Liste des trajets</h2>

<?php if ($msg): ?>
    <p class="msg"><?= htmlspecialchars($msg) ?></p>
<?php endif; ?>

<?php if (!empty($trajets) && is_array($trajets)): ?>
<table>
    <tr>
        <th>ID</th>
        <th>Auteur</th>
        <th>Téléphone</th>
        <th>Email</th>
        <th>Nombre de place</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($trajets as $trajet): ?>
    <tr>
        <td><?= htmlspecialchars($trajet['id']) ?></td>
        <td><?= htmlspecialchars($trajet['auteur']) ?></td>
        <td><?= htmlspecialchars($trajet['tel']) ?></td>
        <td><?= htmlspecialchars($trajet['mail']) ?></td>
        <td><?= htmlspecialchars($trajet['place']) ?></td>
        <td>
            <a class="btn edit" href="?edit=<?= $trajet['id'] ?>">Éditer</a>
            <a class="btn del" href="?delete=<?= $trajet['id'] ?>" onclick="return confirm('Supprimer définitivement ?');">Supprimer</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
    <p style="text-align:center;">Aucun agent trouvé.</p>
<?php endif; ?>

<?php if ($toEdit): ?>
    <form class="form-edit" method="post" action="">
        <h3>Édition du trajet #<?= $toEdit['id'] ?></h3>
        <input type="hidden" name="id" value="<?= $toEdit['id'] ?>">
        <label>Auteur :</label>
        <input type="text" name="auteur" value="<?= htmlspecialchars($toEdit['auteur']) ?>" required>
        <label>Téléphone :</label>
        <input type="text" name="tel" value="<?= htmlspecialchars($toEdit['tel']) ?>" required>
        <label>Email :</label>
        <input type="email" name="mail" value="<?= htmlspecialchars($toEdit['mail']) ?>" required>
        <label>Nombre de place :</label>
        <input type="number" name="place" min="1" value="<?= htmlspecialchars($toEdit['place']) ?>" required>
        <input type="submit" name="update" value="Mettre à jour">
    </form>
<?php endif; ?>

</body>
</html>
