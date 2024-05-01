<?php
$formulaire = true;
require "header.php";
include_once "connexion.php";

$pdo = new Connect();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $query = "SELECT * FROM eglise wHERE ideglise = :ideglise";
    $valider = $pdo->prepare($query);
    $valider->execute(["ideglise" => $_GET["ideglise"]]);
    while ($row = $valider->fetch(PDO::FETCH_ASSOC)) :

?>
<div style="margin-top:70px; margin-bottom: 40px">
    <h1 style="margin-left: 50px;">Ajout d'une eglise : veuillez remplir le formulaire suivant</h1>
</div>
<input type="hidden" name="ideglise" value="<?php echo $row["ideglise"]; ?>">
<form action="" class="container" method="POST">
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="floatingInput" name="ideglise" placeholder="ID église" value="<?php echo $row["ideglise"]; ?>">
        <label for="floatingInput">ID de l'église</label>
    </div>
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="floatingPassword" name="Design" placeholder="Désignation" value="<?php echo $row["Design"]; ?>" >
        <label for="floatingPassword">Désignation</label>
    </div>
    <div>
        <button type="submit" class="btn btn-primary">Valider</button>
    </div>
</form>

<?php
    endwhile;
    $valider->closeCursor();

}?>
<?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $query = "UPDATE eglise SET Design = :Design WHERE ideglise = :ideglise";
        $valider = $pdo->prepare($query);
        $valider->execute([
            "Design" => $_POST["Design"],
            "ideglise" => $_POST["ideglise"]
        ]);
        header("Location: index.php");
        exit(); 
    }
    

?>


<?php require "footer.php"; ?>