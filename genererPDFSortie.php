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

        $htmlPdfSortie = new HtmlPdfEntre($start_date, $end_date, $filteredRows);
        $htmlPdfSortie->GenererPdf();
    }
?>


<form method="GET" action="" class="text-center my-form container" style="margin-top: 90px">
    <div class="form-group">
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

    <input type="submit" value="Générer" class="btn btn-primary" style="margin-top: 20px">
</form>



<?php require "footer.php"; ?>