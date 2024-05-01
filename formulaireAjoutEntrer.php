<?php
require "header.php";
require "connexion.php";

$pdo = new Connect();
$query = $pdo->query('SELECT ideglise, Design, Solde FROM eglise');
$eglises = $query->fetchAll(PDO::FETCH_ASSOC);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ideglise = isset($_POST["eglise"]) ? $_POST["eglise"] : '';
    $motif = isset($_POST["motif"]) ? $_POST["motif"] : '';
    $montantEntre = isset($_POST["montantEntre"]) ? $_POST["montantEntre"] : '';

    try {
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
            $queryEntre = "INSERT INTO entre (ideglise, motif, montantEntre) VALUES (:ideglise, :motif, :montantEntre)";
            $stmtEntre = $pdo->prepare($queryEntre);
            $stmtEntre->execute([
                "ideglise" => $ideglise,
                "motif" => $motif,
                "montantEntre" => $montantEntre
            ]);

            $queryUpdateSolde = "UPDATE eglise SET Solde = Solde + :montantEntre WHERE ideglise = :ideglise";
            $stmtUpdateSolde = $pdo->prepare($queryUpdateSolde);
            $stmtUpdateSolde->execute([
                "montantEntre" => $montantEntre,
                "ideglise" => $ideglise
            ]);

            $pdo->commit();
            echo "Données insérées avec succès.";
            header("Location: AffichageSoldeEntre.php");
            exit();
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Erreur lors de l'insertion : " . $e->getMessage();
    }
}

?>


<div style="margin-top:70px; margin-bottom: 40px">
    <h1 style="margin-left: 50px;">Ajout d'une transaction d'entrer : veuillez remplir le formulaire suivant</h1>
</div>
<form action="" class="container" method="POST">
    <div class="form-floating mb-3">
        <select name="eglise" id="eglise">
            <?php foreach ($eglises as $eglise) : ?>
                <option value="<?= $eglise['ideglise'] ?>"><?= $eglise['Design'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="floatingPassword" name="motif" placeholder="Désignation" required>
        <label for="floatingPassword">Motif</label>
    </div>
    <div class="form-floating mb-3">
        <input type="number" class="form-control" id="floatingSolde" name="montantEntre" placeholder="Solde" required>
        <label for="floatingSolde">Montant entrer</label>
    </div>
    <div>
        <button type="submit" class="btn btn-primary">Valider</button>
    </div>
</form>



<?php require "footer.php"; ?>