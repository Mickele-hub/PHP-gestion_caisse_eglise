<?php
include_once "connexion.php";

$pdo = new Connect();

if (!empty($_GET["ideglise"])) {
    $query = "DELETE FROM eglise WHERE ideglise = :ideglise";
    $valider = $pdo->prepare($query);
    $valider->execute(["ideglise" => $_GET["ideglise"]]);
    $valider->closeCursor();
    header("Location: index.php");

}

?>