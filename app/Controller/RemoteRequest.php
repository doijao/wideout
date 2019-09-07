<?php
declare(strict_types=1);

namespace App\Controller;

class RemoteRequest
{
    protected $glob;

    public function __construct() {
        global $config;
        $this->glob =& $config; 
    }

    public function getURLData(string $url) : void
    {
        if ($this->download($this->getUrlForParsing($url), $this->glob['tempFile'])) {
            echo "--> Downloaded json data successfully. <br />";
        } else {
            echo "--> Failed to download json data. <br />";
        }
    }

    public function ftpFile() : void
    {
        $filename = $this->glob['filename'];
        
        if (!file_exists($filename)) {
            die("Terminated: Can't find " . $filename);
        }
        //-- Connection Settings
        $ftp_server = $this->glob['ftp']['host'];

        $ftp_user_name = $this->glob['ftp']['username'];
        
        $ftp_user_pass = $this->glob['ftp']['password'];
        //where you want to throw the file on the webserver (relative to your login dir)
        $destination_file = "/";
        // set up basic connection
        $conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server");
        // login with username and password, or give invalid user message
        $login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass)
            or die("You do not have access to this ftp server!");
        // check connection
        if ((!$conn_id) || (!$login_result)) {
            // wont ever hit this, b/c of the die call on ftp_login
            echo "--> FTP connection has failed! <br />";
            echo "--> Attempted to connect to $ftp_server for user $ftp_user_name <br />";
            exit;
        }

        ftp_pasv($conn_id, true);
        // upload the file
        $upload = ftp_put($conn_id, $filename, $filename, FTP_BINARY);
        // check upload status
        if (!$upload) {
            echo "--> FTP upload of $filename has failed! <br />";
        } else {
            echo "--> Uploading $filename Completed Successfully!<br />";
        }
        
        // close the FTP stream
        ftp_close($conn_id);
    }


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

    private function getUrlForParsing(string $url) : string
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            die('Not a valid URL');
        }

        return $url;
    }

    public function getLocalFile(string $pathFile): array
    {
        if (!file_exists($pathFile)) {
            die("Terminated: Can't find " . $pathFile);
        }

        $contents = file_get_contents($pathFile);

        $toArray = json_decode($contents, true);
       
        if (empty($toArray['status'])) {
            die("Terminated: We didn't find content in the file.");
        }

        echo "--> Analyzing json file. <br />";

        return $toArray;
    }
}
