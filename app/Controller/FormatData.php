<?php
declare(strict_types=1);

namespace App\Controller;

class FormatData
{
    private $rawData;

    private $path_destination;

    public function __construct(array $rawData, string $path_destination)
    {
        $this->rawData              =   $rawData;
        $this->path_destination     =   $path_destination;
        $this->toCommaDelimiter();
    }
    
    // Split data by comma delimited and export to a file.
    private function toCommaDelimiter() : void
    {
        //Open file pointer.
        $fp = fopen($this->path_destination, 'w');

        foreach ($this->rawData as $key => $item) {
            fputcsv($fp, $item);
        }

        //Finally, close the file pointer.
        fclose($fp);

        echo "--> Saving $this->path_destination <br />";
    }
}
