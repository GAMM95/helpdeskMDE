<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Inicializa Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);

// Obtén el contenido HTML del encabezado, cuerpo y pie de página
ob_start();
include 'header.php';
$header = ob_get_clean();

ob_start();
// Aquí va el HTML de la fila seleccionada
echo $_POST['html_content'];
$body = ob_get_clean();

ob_start();
include 'footer.php';
$footer = ob_get_clean();

// Combina el contenido
$html = $header . $body . $footer;

// Carga el contenido HTML
$dompdf->loadHtml($html);

// (Opcional) Configura el tamaño y orientación del papel
$dompdf->setPaper('A4', 'portrait');

// Renderiza el PDF
$dompdf->render();

// Envía el archivo PDF al navegador
$dompdf->stream("incidencia.pdf", array("Attachment" => 0));

