<?php
declare(strict_types=1);

namespace App\Controller;

class JsonData
{
    protected $glob;

    public $results;
                        
    public function __construct(array $object)
    {
        global $config;
        $this->glob =& $config;

        $i = 0;
        
        foreach ($object['skus'] as $rows) {
            foreach ($rows as $key => $value) {
                if ($this->filterCategories($rows['mCategories']) === false) {
                    if (!$this->filterProduct($rows['mBrand'], 'Apple')) {
                        $rows['skuDisplayName'] = $this->filterColor($rows['skuDisplayName']);
                        foreach ($this->glob['excludeColumns'] as $column) {
                            $this->results[$i][$column] = $rows[$column];
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

    private function filterProduct(string $str, string $value) : bool
    {
        if (strcasecmp($str, $value) === 0) {
            return true;
        }

        return false;
    }

    private function filterColor(string $str) : string
    {
        return preg_replace('/-[^-]*$/', '', $str);
    }


    private function filterCategories($toMatch) : bool
    {
        $excludes = array_map('strtoupper', $this->glob['excludeCategory']);
        foreach ($excludes as $exclude) {
            if (in_array($exclude, $toMatch)) {
                return true;
            }
        }
        return false;
    }

    public static function camelCase($str, array $noStrip = [])
    {
        // non-alpha and non-numeric characters become spaces
        $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
        $str = trim($str);
        // uppercase the first character of each word
        $str = ucwords($str);
        $str = str_replace(" ", "", $str);
        $str = lcfirst($str);

        return $str;
    }
}
