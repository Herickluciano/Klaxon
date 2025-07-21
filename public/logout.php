<?php
session_start();
session_destroy();
header('Location: inscription.php'); // ou connexion.php
exit();
?>
