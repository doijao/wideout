<?php
declare(strict_types=1);

namespace App\Controller;

class FtpClient
{
    private $pathFile;
    
    protected $ftp_server;

    protected $ftp_user_name;
        
    protected $ftp_user_pass;

    public function __construct(string $ftp_server, string $ftp_user_name, $ftp_user_pass)
    {
        //-- Connection Settings
        $this->ftp_server       =   $ftp_server;
        $this->ftp_user_name    =   $ftp_user_name;
        $this->ftp_user_pass    =   $ftp_user_pass;
    }

    public function uploadFile(string $pathFile) : void
    {
        $this->pathFile = $pathFile;
        
        if (!file_exists($this->pathFile)) {
            die("Terminated: Can't find " . $this->pathFile);
        }

        $this->connectFTPClient();
        
    }

    // Initialize FTP connection
    private function connectFTPClient() : void
    {
       //where you want to throw the file on the webserver (relative to your login dir)
        $destination_file = "/";
        // set up basic connection
        $conn_id = ftp_connect($this->ftp_server) or die("Couldn't connect to $this->ftp_server");
        // login with username and password, or give invalid user message
        $login_result = ftp_login($conn_id, $this->ftp_user_name, $this->ftp_user_pass)
            or die("You do not have access to this ftp server!");
        // check connection
        if ((!$conn_id) || (!$login_result)) {
            // wont ever hit this, b/c of the die call on ftp_login
            echo "--> FTP connection has failed! <br />";
            echo "--> Attempted to connect to $this->ftp_server for user $this->ftp_user_name <br />";
            exit;
        }

        ftp_pasv($conn_id, true);
        // upload the file
        $upload = ftp_put($conn_id, $this->pathFile, $this->pathFile, FTP_BINARY);
        // check upload status
        if (!$upload) {
            echo "--> FTP upload of $this->pathFile has failed! <br />";
        } else {
            echo "--> Uploading $this->pathFile Completed Successfully!<br />";
        }
        
        // close the FTP stream
        ftp_close($conn_id);
    }
}
