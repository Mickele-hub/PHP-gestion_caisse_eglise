<?php
$historique = true;
require "header.php";
require "connexion.php";

$pdo = new Connect();
$query = "SELECT Design,Solde FROM eglise";
$données = $pdo->prepare($query);
$données->execute();

foreach ($données as $donné){
    $Design[] = $donné['Design'];
    $Solde[] = $donné['Solde'];
}

?>
<style>
    body {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100vh;
    }
    #myChart {
        height: 400px !important;
        width: 100% !important;
    }
</style>
<div>
  <canvas id="myChart"></canvas>
</div>

<script>
  const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($Design); ?>,
      datasets: [{
        label: 'Solde',
        data: <?php echo json_encode($Solde); ?>,
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>

