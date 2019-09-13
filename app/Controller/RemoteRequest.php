<?php
declare(strict_types=1);

namespace App\Controller;

class RemoteRequest
{
    private $url_source;
    
    private $path_destination;

    public function __construct(string $url_source, string $path_destination)
    {
        $this->url_source       =   $url_source;
        $this->path_destination =   $path_destination;
        $this->getURLData();
    }

    // Download data
    private function getURLData() : void
    {
        if ($this->download($this->getUrlForParsing($this->url_source), $this->path_destination)) {
            echo "--> Downloaded json data successfully. <br />";
        } else {
            echo "--> Failed to download json data. <br />";
        }
    }

    // Stream data and write to local machine
    private function download($file_source, $file_target) : bool
    {
        $rh = fopen($file_source, 'rb');
        $wh = fopen($file_target, 'w+b');
        if (!$rh || !$wh) {
            return false;
        }

        while (!feof($rh)) {
            if (fwrite($wh, fread($rh, 4096)) === false) {
                return false;
            }
            echo ' ';
            flush();
        }

        fclose($rh);
        fclose($wh);

        return true;
    }

    // Checks if URL is valid
    private function getUrlForParsing() : string
    {
        if (filter_var($this->url_source, FILTER_VALIDATE_URL) === false) {
            die('Not a valid URL');
        }

        return $this->url_source;
    }
}
