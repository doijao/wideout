<?php
declare(strict_types=1);

namespace App\Controller;

class ReadJson
{
    protected $include_columns;

    protected $exclude_categories;

    public $results;
                        
    public function __construct(string $path_source, array $include_columns, array $exclude_categories)
    {
        $this->include_columns      =   $include_columns;
        $this->exclude_categories   =   $exclude_categories;
        $datas = $this->readJsonFile($path_source);
        
        $i = 0;
        foreach ($datas['skus'] as $rows) {
            foreach ($rows as $key => $value) {
                // Ignore other categories
                if ($this->filterCategories($rows['mCategories']) === false) {
                    // Ignore Apple brand
                    if (!$this->filterProduct($rows['mBrand'], 'Apple')) {
                        // Remove text colors
                        $rows['skuDisplayName'] = $this->filterColor($rows['skuDisplayName']);
                        // Get selected columns only
                        foreach ($this->include_columns as $column) {
                            $this->results[$i][$column] = (isset($rows[$column]) ? $rows[$column] : '');
                        }
                    }
                }
            }
            $i++;
        }
        
        if (isset($this->results)) {
            echo "--> Filtering data <br />";
        } else {
            echo "--> Failed to filter data <br />";
        }
    }

    // Returns TRUE if the column has the expected value
    private function filterProduct(string $str, string $value) : bool
    {
        if (strcasecmp($str, $value) === 0) {
            return true;
        }

        return false;
    }

    // Remove last words that starts with "-"
    private function filterColor(string $str) : string
    {
        return preg_replace('/-[^-]*$/', '', $str);
    }

    // Returns TRUE if the value exist in excluded category list
    private function filterCategories($toMatch) : bool
    {
        $excludes = array_map('strtoupper', $this->exclude_categories);
        foreach ($excludes as $exclude) {
            if (in_array($exclude, $toMatch)) {
                return true;
            }
        }
        return false;
    }

    // Read json file
    private function readJsonFile(string $path_source): array
    {
        if (!file_exists($path_source)) {
            die("Terminated: Can't find " . $path_source);
        }

        $contents = file_get_contents($path_source);

        $toArray = json_decode($contents, true);
       
        if (empty($toArray['status'])) {
            die("Terminated: We didn't find content in the file.");
        }

        echo "--> Analyzing json file. <br />";

        return $toArray;
    }
}
