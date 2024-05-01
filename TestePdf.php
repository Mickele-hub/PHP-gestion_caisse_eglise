<?php

    require('fpdf/fpdf.php');

    $pdf = new FPDF();

    $pdf->AddPage();

    $pdf->SetFont('Arial', 'B', 16);

    // Add header
    $pdf->Cell(40, 10, 'Bonjour Teste PDF PHP !');
    $pdf->Output('Facture.pdf', 'D');



?>