<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');

use horstoeko\zugferd\ZugferdDocumentReader;
use horstoeko\zugferdvisualizer\ZugferdVisualizer;
use horstoeko\zugferdvisualizer\renderer\ZugferdVisualizerDefaultRenderer;

require dirname(__FILE__) . '/vendor/autoload.php';

$xml = file_get_contents('php://input');

$document = ZugferdDocumentReader::readAndGuessFromContent($xml);

$visualizer = new ZugferdVisualizer($document);
$visualizer->setDefaultTemplate();
// $visualizer->setRenderer(new ZugferdVisualizerDefaultRenderer());
// $visualizer->setTemplate(dirname(__FILE__) . '/template/custom.tmpl');
$visualizer->setPdfFontDefault('courier');
// $visualizer->renderPdfFile($filePath);
$pdfString = $visualizer->renderPdf();

header('Content-Type: application/pdf');
header('Content-Disposition: inline; filename="e-invoice.pdf"');
header('Content-Length: ' . strlen($pdfString));
echo $pdfString;