<?php
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

class HtmlPdfEntre {
    private $start_date;
    private $end_date;
    private $filteredRows;

    public function __construct($start_date, $end_date, $filteredRows) {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->filteredRows = $filteredRows;
    }

    public function GenererPdf() {
        ob_clean();
        $html = '<!DOCTYPE html>
                <html lang="fr">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Document</title>
                </head>
                <body>
                    <div>
                        <h2 style="text-align:center;">Entre ' . htmlspecialchars($this->start_date) . ' et ' . htmlspecialchars($this->end_date) . '</h2>
                        <h3 style="text-align:center;">Mouvement de sortie de caisse</h3>
                        <table style="width:100%;border-collapse:collapse;">
                            <tr>
                                <th style="border:1px solid #000;padding:8px;">Date transaction</th>
                                <th style="border:1px solid #000;padding:8px;">Motif</th>
                                <th style="border:1px solid #000;padding:8px;">Montant</th>
                            </tr>';
        
        foreach ($this->filteredRows as $row) {
            $dateSortie = htmlspecialchars($row['dateSortie']);
            $motif = htmlspecialchars($row['motif']);
            $montantSortie = htmlspecialchars($row['montantSortie']);

            $html .= '<tr>
                        <td style="border:1px solid #000;padding:8px;">' . $dateSortie . '</td>
                        <td style="border:1px solid #000;padding:8px;">' . $motif . '</td>
                        <td style="border:1px solid #000;padding:8px;">' . $montantSortie . '</td>
                      </tr>';
        }

        $html .= '<tr>
                    <td colspan="2" style="border:1px solid #000;padding:8px;text-align:center;">Total Montant sortie :</td>
                    <td style="border:1px solid #000;padding:8px;">' . array_sum(array_column($this->filteredRows, 'montantSortie')) . ' AR</td>
                  </tr>
                </table>
            </div>
        </body>
        </html>';

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream("MouvmentSortie.pdf", array("Attachment" => false));
        ob_end_flush(); 
    }
}
