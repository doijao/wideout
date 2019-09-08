<?php
declare(strict_types=1);

namespace App\Controller;

class FormatData
{
    protected $glob;

    private $rawData;

    public function __construct(array $rawData)
    {
        global $config;
        $this->glob =& $config;
        $this->rawData = $rawData;
        $this->toCommaDelimiter();
    }
    
    // Split data by comma delimited and export to a file.
    public function toCommaDelimiter() : void
    {
        $fileName = $this->glob['filename'];
        //Open file pointer.
        $fp = fopen($fileName, 'w');

        foreach ($this->rawData as $key => $item) {
            fputcsv($fp, $item);
        }

        //Finally, close the file pointer.
        fclose($fp);

        echo "--> Saving $fileName <br />";
    }
}
