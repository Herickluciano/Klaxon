<?php require __DIR__ . '/../layouts/header_utilisateur.php'; ?>

<?php
/***** Connexion *****/


try {
    $con = new PDO('mysql:host=localhost;port=3306;dbname=klaxon;charset=utf8;', 'root', '');
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}


/***** Gestion des actions *****/
$editId   = filter_input(INPUT_GET, 'edit',   FILTER_VALIDATE_INT);
$deleteId = filter_input(INPUT_GET, 'delete', FILTER_VALIDATE_INT);
$msg      = "";

/* --- Suppression --- */
if ($deleteId) {
    $stmt = $con->prepare('DELETE FROM t_trajet WHERE id = ?');
    $stmt->execute([$deleteId]);
    header('Location: liste_admin.php');
    exit();
}

/* --- Mise à jour (formulaire POST) --- */
if (isset($_POST['update']) && isset($_POST['id'])) {
    $id     = (int)$_POST['id'];
    $depart = trim($_POST['depart']);
    $date_depart   = trim($_POST['date_depart']);
     $heure_depart    = (int)$_POST['heure_depart'];
    $destination = trim($_POST['destination']);
    $date_arrivee   = trim($_POST['date_arrivee']);
     $heure_arrivee     = (int)$_POST['heure_arrivee'];
    $place = trim($_POST['place']);
    

    if ($depart !== '' && $date_depart !== '' && $heure_depart !== '' && $destination !== '' && $date_arrivee !== '' && $heure_arrivee !== '' && $place !== '') {
        $stmt = $con->prepare('UPDATE t_trajet SET depart = ?, date_depart = ? heure_depart = ?, destination = ? date_arrivee = ?, heure_arrivee = ? place = ? WHERE id = ?');
        $stmt->execute([$depart, $date_depart, $heure_depart, $destination, $date_arrivee, $heure_arrivee, $place, $id]);
        header('Location: liste_admin.php');
        exit();
    } else {
        $msg = "Tous les champs sont obligatoires.";
    }
}

/***** Récupération de la liste *****/
$sql   = "SELECT id, depart, date_depart, heure_depart, destination, date_arrivee, heure_arrivee, place, created_at FROM t_trajet ORDER BY id";
$stmt  = $con->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

/***** édite, récupère l’utilisateur concerné *****/
$toEdit = null;
if ($editId) {
    $stmt   = $con->prepare('SELECT id, depart, date_depart, heure_depart, destination, date_arrivee, heure_arrivee, place  FROM t_trajet WHERE id = ?');
    $stmt->execute([$editId]);
    $toEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Creer un trajet</title>
    <link rel="stylesheet" href="/Klaxon_MVC_PHP/public/css/style.css">
</head>
<body>

<h2 style="text-align:center;">Trajets proposes</h2>

<?php if ($msg): ?>
<p class="msg"><?= htmlspecialchars($msg) ?></p>
<?php endif; ?>

<?php if (count($users) > 0): ?>
<table>
    <tr>
        <th>ID</th>
        <th>Depart</th>
        <th>Date</th>
        <th>Heure</th>
        <th>Destination</th>
        <th>Date</th>
        <th>Heure</th>
        <th>Place</th>
        <th>Date d'inscription</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= htmlspecialchars($user['id']) ?></td>
        <td><?= htmlspecialchars($user['depart']) ?></td>
        <td><?= htmlspecialchars($user['date_depart']) ?></td>
        <td><?= htmlspecialchars($user['heure_depart']) ?></td>
        <td><?= htmlspecialchars($user['destination']) ?></td>
        <td><?= htmlspecialchars($user['date_arrivee']) ?></td>
        <td><?= htmlspecialchars($user['heure_arrivee']) ?></td>
        <td><?= htmlspecialchars($user['place']) ?></td>
        <td><?= htmlspecialchars($user['created_at']) ?></td>
        <td>
            <a class="btn edit" href="/Klaxon_MVC_PHP/public/login_visiteur.php">Éditer</a>
            <a class="btn edit" id="add" onclick="show()">vue</a>
            <a class="btn del"  href="?delete=<?= $user['id'] ?>" 
               onclick="return confirm('Supprimer définitivement ?');">Supprimer</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
<p style="text-align:center;">Aucun utilisateur inscrit.</p>
<?php endif; ?>


<?php if ($toEdit): ?>
<form class="form-edit" method="post">
    <h3>Édition de l’utilisateur #<?= $toEdit['id'] ?></h3>
    <input type="hidden" name="id" value="<?= $toEdit['id'] ?>">
    <label>Pseudo :</label>
    <input type="text" name="depart" value="<?= htmlspecialchars($toEdit['depart']) ?>" required>
    <label>Email :</label>
    <input type="text" name="date_depart" value="<?= htmlspecialchars($toEdit['date_depart']) ?>" required>
    <input type="hidden" name="heure_depart" value="<?= $toEdit['heure_depart'] ?>">
    <label>Pseudo :</label>
    <input type="text" name="destination" value="<?= htmlspecialchars($toEdit['destination']) ?>" required>
    <label>Email :</label>
    <input type="text" name="date_arrivee" value="<?= htmlspecialchars($toEdit['date_arrivee']) ?>" required>
    <input type="text" name="destination" value="<?= htmlspecialchars($toEdit['heure_arrivee']) ?>" required>
    <label>Email :</label>
    <input type="text" name="date_arrivee" value="<?= htmlspecialchars($toEdit['place']) ?>" required>
    <input type="submit" name="update" value="Mettre à jour">
</form>
<?php endif; ?>


<div id="popup">
  <form id="form">
    <button type="button" id="close" onclick="hide()">X</button>
    <br>
    <p1>Auteur : Xxxx Xxxx</p1>
    <br>

    <p1>Telephone : Xxxx xxx</p1>
    <br>
   
    <p1>E_mail : Xxxx@xxx</p1>
    <br>
   
    <p1>Nombre de place : Xxxx Xxxx</p1>
   </form>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

<script>
    function show(){
  document.getElementById("popup").style.display = "block";
}
function hide() {
  document.getElementById("popup").style.display = "none";
  
  document.getElementById("name").value = "";
  document.getElementById("age").value = "";
  document.getElementById("country").value = "";
}

function add() {
  var name = document.getElementById("name").value;
  var age = document.getElementById("age").value;
  var country = document.getElementById("country").value;
  
  if (name == "" || age == "" || country == "") {
    alert("Please fill all fields.")
  } else {
    document.getElementById("popup").style.display = "none";
    var newdiv = document.createElement("div");
    newdiv.className += "cont";
    newdiv.innerHTML = "Name: "+ name + "<br>Age: " + age + "<br>Country: " + country;
    document.getElementById("results").appendChild(newdiv);
    
    document.getElementById("name").value = "";
    document.getElementById("age").value = "";
    document.getElementById("country").value = "";
  }
}
</script>