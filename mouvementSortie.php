<?php 
require "header.php";
require "connexion.php";
require "pdf2.php";

$pdo = new Connect();


$queryDates = "SELECT DISTINCT dateSortie FROM sortie";
$resultDates = $pdo->query($queryDates);
    
    if ($resultDates) {
        $datesEx = $resultDates->fetchAll(PDO::FETCH_COLUMN);
    }
    if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['start_date']) && isset($_GET['end_date'])) {
        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];
    
        $query = "SELECT sortie.*, eglise.Design FROM sortie INNER JOIN eglise ON sortie.ideglise = eglise.ideglise BETWEEN :start_date AND :end_date";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['start_date' => $start_date, 'end_date' => $end_date]);
    
        $filteredRows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    }
?>


<form method="GET" action="" class="text-center my-form container" style="margin-top: 90px">
    <div class="form-group">
    <a href="genererPDFSortie.php" ><button type="button" class="container btn btn-success" style="margin-bottom: 20px">Aller générer un PDF</button></a>
        <label for="start_date" style="font-weight: bold;">Date Début:</label>
        <select name="start_date" id="start_date" class="form-control">
            <?php
            foreach ($datesEx as $datesE) {
                echo "<option value=\"$datesE\">$datesE</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group" style="margin-top: 20px">
        <label for="end_date" style="font-weight: bold;">Date Fin:</label>
        <select name="end_date" id="end_date" class="form-control">
            <?php
            foreach ($datesEx as $datesE) {
                echo "<option value=\"$datesE\">$datesE</option>";
            }
            ?>
        </select>
    </div>

    <input type="submit" value="Rechercher" class="btn btn-primary" style="margin-top: 20px">
</form>
<div data-aos="zoom-in">
<?php

if (isset($filteredRows)) {
    echo"<div class='container' style='margin-top: 5px;font-weight:bold'>Entre $start_date et $end_date</div>";
    echo"<div class='container'style='margin-top: 5px;font-weight: bold;'>Mouvement de sortie de caisse</div>";
    echo "<table id='mytable' class='table table-dark table-striped container table-bordered border-dark' style='margin-top: 50px'>";
    echo "<tr><th scope='col'>Date d'entrée</th><th scope='col'>Motif</th><th scope='col'>Montant</th></tr>";
    foreach ($filteredRows as $row) {
        echo "<tr>";
        echo "<td>{$row['dateSortie']}</td>";
        echo "<td>{$row['motif']}</td>";
        echo "<td>{$row['montantSortie']}</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<div class='container' style='margin-top: 20px;font-weight: bold;'>Total Montant Sortant : " . array_sum(array_column($filteredRows, 'montantSortie'))."AR</div>";
}
?>
</div>
<?php require "footer.php"; ?>