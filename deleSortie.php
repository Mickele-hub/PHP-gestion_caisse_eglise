<?php
include_once "connexion.php";

$pdo = new Connect();

if (!empty($_GET["idsortie"])) {
    $query = "DELETE FROM sortie WHERE idsortie = :idsortie";
    $valider = $pdo->prepare($query);
    $valider->execute(["idsortie" => $_GET["idsortie"]]);
    $valider->closeCursor();
    header("Location: AffichageSoldeSorti.php");

}


?>