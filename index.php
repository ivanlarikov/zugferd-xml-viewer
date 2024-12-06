<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: text/plain");

use horstoeko\zugferd\ZugferdDocumentReader;
use horstoeko\zugferdvisualizer\ZugferdVisualizer;

require dirname(__FILE__) . "/vendor/autoload.php";

$isCacheEnabled = true;
$xml = file_get_contents("php://input");
$fileKey = empty($_GET['id']) ? time() : $_GET['id'];
$fileName = md5($fileKey . '---' . strlen($xml)) . ".pdf";
$filePath = dirname(__FILE__) . "/generated/" . $fileName;

if (!$isCacheEnabled || !file_exists($filePath)) {
	$document = ZugferdDocumentReader::readAndGuessFromContent($xml);

	$visualizer = new ZugferdVisualizer($document);
	$visualizer->setDefaultTemplate();
	$visualizer->setPdfFontDefault("courier");
	$visualizer->renderPdfFile($filePath);
}


// Get Base URL
$protocol = (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off" || $_SERVER["SERVER_PORT"] == 443) ? "https://" : "http://";
$host = $_SERVER["HTTP_HOST"];
$requestUri = $_SERVER["REQUEST_URI"];
if (strpos($requestUri, "?") !== false) {
	$requestUri = substr($requestUri, 0, strpos($requestUri, "?"));
}
$requestUrl = $protocol . $host . $requestUri;
$patts = explode("/", $requestUrl);
if ($patts[count($patts) - 1] == "index.php") {
	array_pop($patts);
}
$baseUrl = implode("/", $patts);

// Get PDF URL
$pdfUrl = $baseUrl . "/generated/" . $fileName;

echo $pdfUrl;