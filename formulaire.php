<?php
$formulaire = true;
require "header.php";
include_once "connexion.php";

$pdo = new Connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $query = "INSERT INTO eglise (ideglise, Design) VALUES (:ideglise, :Design)";

    $ideglise = isset($_POST["ideglise"]) ? $_POST["ideglise"] : '';
    $design = isset($_POST["Design"]) ? $_POST["Design"] : '';

    try {
        $valider = $pdo->prepare($query);
        $valider->execute([
            "ideglise" => $ideglise,
            "Design" => $design
        ]);
        echo "Données insérées avec succès.";
        $valider->closeCursor();
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        echo "Erreur lors de l'insertion : " . $e->getMessage();
    }
}



?>
<div style="margin-top:70px; margin-bottom: 40px">
    <h1 style="margin-left: 50px;">Ajout d'une eglise : veuillez remplir le formulaire suivant</h1>
</div>
<form action="" class="container" method="POST">
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="floatingInput" name="ideglise" placeholder="ID église" required>
        <label for="floatingInput">ID de l'église</label>
    </div>
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="floatingPassword" name="Design" placeholder="Désignation" required>
        <label for="floatingPassword">Désignation</label>
    </div>
    <div>
        <button type="submit" class="btn btn-primary">Valider</button>
    </div>
</form>



<?php require "footer.php"; ?>