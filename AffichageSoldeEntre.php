<?php 
    require "header.php";
    require  "connexion.php";

    $counter = 0;
    
    $pdo = new Connect();
    $query = "SELECT * FROM entre";
    $données = $pdo->prepare($query);
    $données->execute(); 

    $query = "SELECT entre.*, eglise.Design FROM entre INNER JOIN eglise ON entre.ideglise = eglise.ideglise";
    $données = $pdo->prepare($query);
    $données->execute();
 
    

?>
<?php
    $queryMotifs = "SELECT DISTINCT motif FROM entre";
    $resultMotifs = $pdo->query($queryMotifs);
    
    if ($resultMotifs) {
        $motifsExistants = $resultMotifs->fetchAll(PDO::FETCH_COLUMN);

    }
?>
<div data-aos="zoom-in">
<div class="container">
<h1 style="margin-left: 50px;margin-top: 70px;margin-bottom: 40px">Affichage des solde entre :</h1>
<a href="formulaireAjoutEntrer.php" class="btn btn-primary" style="float:right;margin-bottom:20px;">
Ajouter  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-plus-fill" viewBox="0 0 16 16">
        <path d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2M8.5 6v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 1 0"/>
    </svg>
</a>


<table id="mytable" class="display" >
    <thead>
        <tr>
            <th>ID EGLISE</th>
            <th>DESIGNATION</th>
            <th>MOTIF</th>
            <th>MONTANT ENTRER</th>
            <th>DATE ENTRER</th>
            <th>ACTION</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $données->fetch(PDO::FETCH_ASSOC)) :
        $counter++;
        ?>
            <tr>
                <td><?php echo $row['ideglise']; ?></td>
                <td><?php echo $row['Design']; ?></td>
                <td><?php echo $row['motif']; ?></td>
                <td><?php echo $row['montantEntre']; ?></td>
                <td><?php echo $row['dateEntre']; ?></td>
                <td>
                    <a href="modifierEntrer.php?ideglise=<?php echo $row['ideglise']; ?>" class="btn btn-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                            <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                        </svg>
                    </a>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $counter ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                        </svg>
                    </button>    
                </td>
            </tr>
<div class="modal fade" id="deleteModal<?= $counter ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Suppression</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Voulez vous vraiment supprimer cette ligne ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <a href="deletEntre.php?identre=<?php echo $row['identre']; ?>" class="btn btn-danger">Confirmer</a>
      </div>
    </div>
  </div>
</div>
        <?php endwhile; ?> 
        
    </tbody>
</table>
</div>
<form action="rechercheEntre.php" class="container" method="GET">
        <div class="form-floating mb-3">
            <select name="motif" id="motif" >
                <?php
                // Affichez chaque motif dans la liste déroulante
                foreach ($motifsExistants as $motifExist) {
                    echo "<option value=\"$motifExist\">$motifExist</option>";
                }
                ?>
            </select>
            <label for="motif" style="margin-top: 10px;">Sélectionnez le motif</label>
        </div>
        <!-- Autres champs du formulaire -->
        <button type="submit" class="btn btn-primary"  style="margin-top: 20px;">Rechercher</button>
</form>
</div>
<?php require "footer.php"; ?>