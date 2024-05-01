<?php
$formulaire = true;
require "header.php";
include_once "connexion.php";

$pdo = new Connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $query = "SELECT entre.*, eglise.Design FROM entre INNER JOIN eglise ON entre.ideglise = eglise.ideglise WHERE entre.ideglise = :ideglise";
    $valider = $pdo->prepare($query);
    $valider->execute(["ideglise" => $_GET["ideglise"]]);
    $row = $valider->fetch(PDO::FETCH_ASSOC);

?>
<div style="margin-top:70px; margin-bottom: 40px">
    <h1 style="margin-left: 50px;">Ajout d'une Transaction d'entrer : veuillez remplir le formulaire suivant</h1>
</div>
<form action="" class="container" method="POST">
<input type="hidden" name="ideglise" value="<?php echo $row["ideglise"]; ?>">
<div class="form-floating mb-3">
        <input type="text" class="form-control" id="floatingInput" name="motif" placeholder="Motif" value="<?php echo $row["motif"]; ?>" required>
        <label for="floatingInput">Motif</label>
    </div>
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="floatingPassword" name="montantEntre" placeholder="Montant Entrer" value="<?php echo $row["montantEntre"]; ?>" required>
        <label for="floatingPassword">Montant entrer</label>
    </div>
    <div>
        <button type="submit" class="btn btn-primary">Valider</button>
    </div>
</form>

<?php
    $valider->closeCursor();

}
   
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ideglise = isset($_POST["ideglise"]) ? $_POST["ideglise"] : '';
    $motif = isset($_POST["motif"]) ? $_POST["motif"] : '';
    $montantEntre = isset($_POST["montantEntre"]) ? $_POST["montantEntre"] : '';
    
    $pdo->beginTransaction();

    $queryCheckThreshold = "SELECT Solde FROM eglise WHERE ideglise = :ideglise";
    $stmtCheckThreshold = $pdo->prepare($queryCheckThreshold);
    $stmtCheckThreshold->execute(["ideglise" => $ideglise]);
    $soldeActuel = $stmtCheckThreshold->fetchColumn();

    if ($soldeActuel + $montantEntre < 10000) {
        $pdo->rollBack();
        echo '<div class="alert alert-danger" role="alert" style="margin-top:70px; margin-bottom: 40px">
                    Le solde ne peut pas être inférieur à 10.000 Ar. Transaction annulée.
              </div>';
    } else {
        $queryEntre = "UPDATE entre SET motif = :motif, montantEntre = :montantEntre WHERE ideglise = :ideglise";
        $stmtEntre = $pdo->prepare($queryEntre);
        $stmtEntre->execute([
            "motif" => $motif,
            "montantEntre" => $montantEntre,
            "ideglise" => $ideglise
        ]);

        $queryUpdateSolde = "UPDATE eglise SET Solde = Solde + :montantEntre WHERE ideglise = :ideglise";
        $stmtUpdateSolde = $pdo->prepare($queryUpdateSolde);
        $stmtUpdateSolde->execute([
            "montantEntre" => $montantEntre,
            "ideglise" => $ideglise
        ]);

        $pdo->commit();
        echo "Données mises à jour avec succès.";
        header("Location: AffichageSoldeEntre.php");
        exit();
    }
}

require "footer.php"; ?>