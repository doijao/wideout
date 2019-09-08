<?php
require_once 'vendor/autoload.php';

$config = include('config.php');

global $config;

use App\Controller\RemoteRequest;

use App\Controller\JsonData;

use App\Controller\FormatData;

$url = $config['jsonURL'];
$filePath =  __DIR__ .'/'.$config['tempFile'];
// Download data from URL
$request = new RemoteRequest($url);
// Read and analyze JSON file
$rawData = $request->getLocalFile($filePath);
// Convert JSON to array then parse data
$filteredData = new JsonData($rawData);
// Export data to comma delimited format
$stream = new FormatData($filteredData->results);
// Transfer file to FTP
$request->ftpFile();
