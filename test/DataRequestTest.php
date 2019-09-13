<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

use App\Controller\RemoteRequest;

use App\Controller\ReadJson;

use App\Controller\FormatData;

use App\Controller\FtpClient;

class RequestTest extends TestCase {
    /**
     * @test
     */
    public function ReadFile() {
        
        $config = include('config.php');
        new RemoteRequest($config['jsonURL'], $config['tempFile']);
        $filteredData = new ReadJson($config['tempFile'], $config['includeColumns'], $config['excludeCategories']);
        //print_r($filteredData);
        assert(1, 1);
        new FormatData($filteredData->results, $config['filename']);
        //$connect = new FtpClient($config['ftp']['host'], $config['ftp']['username'], $config['ftp']['password']);
        //$connect->uploadFile($config['filename']);
    }
    
}

?>