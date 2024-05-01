<?php
include_once "connexion.php";

$pdo = new Connect();

if (!empty($_GET["identre"])) {
    $query = "DELETE FROM entre WHERE identre = :identre";
    $valider = $pdo->prepare($query);
    $valider->execute(["identre" => $_GET["identre"]]);
    $valider->closeCursor();
    header("Location: AffichageSoldeEntre.php");

}


?>