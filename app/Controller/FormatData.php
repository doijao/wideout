<?php
declare(strict_types=1);

namespace App\Controller;

class FormatData
{
    protected $glob;

    public function __construct() {
        global $config;
        $this->glob =& $config; 
    }

    public function toCommaDelimiter($rawData) : void
    {
        $fileName = $this->glob['filename'];
        //Open file pointer.
        $fp = fopen($fileName, 'w');

        foreach ($rawData as $key => $item) {
            fputcsv($fp, $item);
        }

        //Finally, close the file pointer.
        fclose($fp);

        echo "--> Saving $fileName <br />";
    }
}
