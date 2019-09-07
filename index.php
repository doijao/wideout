<?php
require_once 'vendor/autoload.php';

$config = include('config.php');

global $config;

use App\Controller\RemoteRequest;

use App\Controller\JsonData;

use App\Controller\FormatData;

$request = new RemoteRequest();
$url = $config['jsonURL'];
$request->getURLData($url);
$filePath =  __DIR__ .'/'.$config['tempFile'];
$rawData = $request->getLocalFile($filePath);
$filteredData = new JsonData($rawData);
$stream = new FormatData();
$stream->toCommaDelimiter($filteredData->results);
$request->ftpFile();
