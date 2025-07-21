<?php 
session_start();

try {
    $con = new PDO('mysql:host=localhost;port=3306;dbname=klaxon;charset=utf8;', 'root', '');
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$message = "";

if (isset($_POST['submit'])) {
    if (!empty($_POST['pseudo']) && !empty($_POST['password']) && !empty($_POST['mail'])) {
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // plus sécurisé
        $mail = htmlspecialchars($_POST['mail']);

        // Vérifie si le pseudo ou l'email existe déjà
        $checkUser = $con->prepare('SELECT * FROM t_admin WHERE pseudo = ? OR mail = ?');
        $checkUser->execute([$pseudo, $mail]);

        if ($checkUser->rowCount() > 0) {
            $message = "❌ Ce pseudo ou e-mail est déjà utilisé.";
        } else {
            $insertUser = $con->prepare('INSERT INTO t_admin(pseudo, password, mail) VALUES (?, ?, ?)');
            $insertUser->execute([$pseudo, $password, $mail]);

            $lastId = $con->lastInsertId();
            $_SESSION['pseudo'] = $pseudo;
            $_SESSION['mail'] = $mail;
            $_SESSION['id'] = $lastId;

            header('Location: /Klaxon_MVC_PHP/app/View/home/index_edit.php');
            exit();
        }
    } else {
        $message = "⚠️ Veuillez remplir tous les champs.";
    }
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Inscription</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="/Klaxon_MVC_PHP/public/css/index.css">
</head>

<body>

  <div class="form-container">
    <h2>Entrer vos references</h2>

    <form action="" method="post" novalidate>
      <div class="mb-3">
        <label for="username" class="form-label">Nom d'utilisateur</label>
        <input type="text" class="form-control" id="username" name="pseudo" placeholder="Entrez votre nom" required autocomplete="off">
      </div>

      <div class="mb-3">
        <label for="usermail" class="form-label">Adresse e-mail</label>
        <input type="email" class="form-control" id="usermail" name="mail" placeholder="Entrez votre e-mail" required autocomplete="off">
      </div>

      <div class="mb-3">
        <label for="userpassword" class="form-label">Mot de passe</label>
        <input type="password" class="form-control" id="userpassword" name="password" placeholder="Entrez votre mot de passe" required autocomplete="off">
      </div>

      <button type="submit" name="submit" class="btn btn-primary w-100">Inscription</button>
    </form>

    <p class="redirect">Déjà inscrit ? <a href="/Klaxon_MVC_PHP/public/login.php">Connexion</a></p>
  </div>

</body>
</html>
