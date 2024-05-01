<?php
$formulaire = true;
require "header.php";
include_once "connexion.php";

$pdo = new Connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $query = "SELECT sortie.*, eglise.Design FROM sortie INNER JOIN eglise ON sortie.ideglise = eglise.ideglise WHERE sortie.ideglise = :ideglise";
    $valider = $pdo->prepare($query);
    $valider->execute(["ideglise" => $_GET["ideglise"]]);
    $row = $valider->fetch(PDO::FETCH_ASSOC);

?>
<div style="margin-top:70px; margin-bottom: 40px">
    <h1 style="margin-left: 50px;">Ajout d'une Transaction de sortie : veuillez remplir le formulaire suivant</h1>
</div>
<form action="" class="container" method="POST">
<input type="hidden" name="ideglise" value="<?php echo $row["ideglise"]; ?>">
<div class="form-floating mb-3">
        <input type="text" class="form-control" id="floatingInput" name="motif" placeholder="Motif" value="<?php echo $row["motif"]; ?>" required>
        <label for="floatingInput">Motif</label>
    </div>
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="floatingPassword" name="montantSortie" placeholder="Montant Sortie" value="<?php echo $row["montantSortie"]; ?>" required>
        <label for="floatingPassword">Montant sortie</label>
    </div>
    <div>
        <button type="submit" class="btn btn-primary">Valider</button>
    </div>
</form>

<?php
    $valider->closeCursor();

}
 
$query = $pdo->query('SELECT ideglise, Design, Solde FROM eglise');
$eglises = $query->fetchAll(PDO::FETCH_ASSOC);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $ideglise = isset($_POST["ideglise"]) ? $_POST["ideglise"] : '';
    $motif = isset($_POST["motif"]) ? $_POST["motif"] : '';
    $montantSortie = isset($_POST["montantSortie"]) ? $_POST["montantSortie"] : '';

    try {
        $pdo->beginTransaction();

        $queryCheckSolde = "SELECT Solde FROM eglise WHERE ideglise = :ideglise";
        $stmtCheckSolde = $pdo->prepare($queryCheckSolde);
        $stmtCheckSolde->execute(["ideglise" => $ideglise]);
        $soldeActuel = $stmtCheckSolde->fetchColumn();

        if ($soldeActuel - $montantSortie <= 10000) {
            $pdo->rollBack();
            echo '<div class="alert alert-danger" role="alert" style="margin-top:70px; margin-bottom: 40px">
                    Le solde ne peut pas être inférieur à 10.000 Ar. Transaction annulée.
                  </div>';
        } else {
            $querySortie = "UPDATE sortie SET motif = :motif, montantSortie = :montantSortie WHERE ideglise = :ideglise";
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
require "footer.php"; ?>