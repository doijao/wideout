<?php
require_once 'vendor/autoload.php';

$config = include('config.php');

global $config;

use App\Controller\RemoteRequest;

use App\Controller\JsonData;

use App\Controller\FormatData;

use App\Controller\FtpClient;

$filePath =  __DIR__ .'/'.$config['tempFile'];
// Download data from URL
$request = new RemoteRequest($config['jsonURL'], $config['tempFile']);
// Read and analyze JSON file
$rawData = $request->readJSONFile($filePath);
// Convert JSON to array then parse data
$filteredData = new JsonData($rawData, $config['includeColumns'], $config['excludeCategory']);
// Export data to comma delimited format
new FormatData($filteredData->results, $config['filename']);
// Transfer file to FTP
$connect = new FtpClient($config['ftp']['host'], $config['ftp']['username'], $config['ftp']['password']);
$connect->uploadFile($config['filename']);
