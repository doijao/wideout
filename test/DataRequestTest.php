<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

use App\Controller\RemoteRequest;

use App\Controller\JsonData;

use App\Controller\FormatData;

class RequestTest extends TestCase {
    /**
     * @test
     */
    public function ReadFile() {
        
        $request = new RemoteRequest();
        $url = 'https://www.att.com/services/catalogservice/devices?includeFilters=skuState=active&mode=productList';
        $request->getURLData($url);           
        $filePath = __DIR__.'/data/productList.json'; 
        $rawData = $request->getLocalFile($filePath);
        $filteredData = new JsonData($rawData); 
        $stream = new FormatData();
        $stream->toCommaDelimiter($filteredData->results);
        $request->ftpFile();
    }
    
}

?>