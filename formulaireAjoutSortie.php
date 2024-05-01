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
    $montantSortie = isset($_POST["montantSortie"]) ? $_POST["montantSortie"] : '';

    try {
        $pdo->beginTransaction();

        $queryCheckSolde = "SELECT Solde FROM eglise WHERE ideglise = :ideglise";
        $stmtCheckSolde = $pdo->prepare($queryCheckSolde);
        $stmtCheckSolde->execute(["ideglise" => $ideglise]);
        $soldeActuel = $stmtCheckSolde->fetchColumn();

        if ($soldeActuel <= 10000 && $soldeActuel == $montantSortie) {
            $pdo->rollBack(); 
            echo '<div class="alert alert-danger" role="alert" style="margin-top:70px; margin-bottom: 40px">
                    Le solde ne peut pas être inférieur à 10.000 Ar. Transaction annulée.
                  </div>';
        } else {
            $querySortie = "INSERT INTO sortie (ideglise, motif, montantSortie) VALUES (:ideglise, :motif, :montantSortie)";
            $stmtSortie = $pdo->prepare($querySortie);
            $stmtSortie->execute([
                "ideglise" => $ideglise,
                "motif" => $motif,
                "montantSortie" => $montantSortie
            ]);
    
            $queryUpdateSolde = "UPDATE eglise SET Solde = Solde - :montantSortie WHERE ideglise = :ideglise";
            $stmtUpdateSolde = $pdo->prepare($queryUpdateSolde);
            $stmtUpdateSolde->execute([
                "montantSortie" => $montantSortie,
                "ideglise" => $ideglise
            ]);

            $pdo->commit();
            echo 'Données insérées avec succès.';
            header("Location: AffichageSoldeSorti.php");
            exit();
        }
    } catch (PDOException $e) {
            $pdo->rollBack();
            echo '<div class="alert alert-danger" role="alert">
                    Erreur lors de l\'insertion : ' . $e->getMessage() . '
                  </div>';
    }

}
?>



<div style="margin-top:70px; margin-bottom: 40px">
    <h1 style="margin-left: 50px;">Ajout d'une transaction de sortie : veuillez remplir le formulaire suivant</h1>
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
        <input type="number" class="form-control" id="floatingSolde" name="montantSortie" placeholder="Solde" required>
        <label for="floatingSolde">Montant sortie</label>
    </div>
    <div>
        <button type="submit" class="btn btn-primary">Valider</button>
    </div>
</form>



<?php require "footer.php"; ?>