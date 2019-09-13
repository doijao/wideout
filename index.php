<?php
require_once 'vendor/autoload.php';

$config = include('config.php');

global $config;

use App\Controller\RemoteRequest;

use App\Controller\ReadJson;

use App\Controller\FormatData;

use App\Controller\FtpClient;

// Download data from URL
new RemoteRequest($config['jsonURL'], $config['tempFile']);
// Read and analyze JSON file
// Convert JSON to array then parse data
$filteredData = new ReadJson($config['tempFile'], $config['includeColumns'], $config['excludeCategories']);
// Export data to comma delimited format
new FormatData($filteredData->results, $config['filename']);
// Transfer file via FTP
$connect = new FtpClient($config['ftp']['host'], $config['ftp']['username'], $config['ftp']['password']);
$connect->uploadFile($config['filename']);
