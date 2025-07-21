<?php 

try {
$con = new PDO('mysql:host=localhost;port=3306;dbname=klaxon;charset=utf8;', 'root', '');
} catch (PDOException $e) {
    print "Error: " . $e->getMessage();
}


 if (isset($_POST['submit'])) {
   if (!empty($_POST['pseudo']) AND !empty($_POST['password'])  AND !empty($_POST['mail'])) {
      // code...
      $pseudo = htmlspecialchars($_POST['pseudo']);
      $password = sha1($_POST['password']);
      $mail = htmlspecialchars($_POST['mail']);
      $insertUser = $con->prepare('INSERT INTO t_admin(pseudo, password, mail) VALUES(?,?,?)');
      $insertUser->execute(array($pseudo, $password, $mail)); 

      $recupUser = $con->prepare('SELECT * FROM t_admin WHERE pseudo = ? AND password=? AND mail =?');
      $recupUser->execute(array($pseudo, $password, $mail));
      if ($recupUser->rowcount()>0) {
         // code...
          $_SESSION['pseudo'] = $pseudo;
          $_SESSION['password'] = $password;
          $_SESSION['mail'] = $mail;
          $_SESSION['id'] = $recupUser->fetch()['id'];
          header('Location:/public/login.php');
      }
      // die("Votre inscription est bien pris en compte merci... <a href>cccc</a>.");
      // echo $_SESSION['id'];

   }else {
      // code...
      // echo "<h2>Veillez remplir tous les champs svp...</h2>";
   }
    // code...
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
    <h2>Veuillez vous inscription</h2>

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

    <p class="redirect">Deja inscrire ? <a href="/Klaxon_MVC_PHP/public/login.php">Connexion</a></p>
  </div>

</body>
</html>
