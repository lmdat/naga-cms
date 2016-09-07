<?php

namespace App\Libs\Utils;


class FTPConnection{

    private $host;

    private $port;

    private $username;

    private $password;

    private $passive;

    private $timeout;

    private $ssl;

    protected $ftpConn;


    public function __construct($config=null){



        if($config == null){
            $this->host = '';

            $this->username = '';

            $this->password = '';

            $this->port = 21;

            $this->passive = false;

            $this->ssl = false;

            $this->timeout = 90;
        }
        else{

            $this->host = $config['host'];

            $this->username = $config['username'];

            $this->password = $config['password'];

            $this->port = isset($config['port']) ? $config['port'] : 21;

            $this->passive = $config['passive'];

            $this->ssl = isset($config['ssl']) ? $config['ssl'] : false;

            $this->timeout = isset($config['timeout']) ? $config['timeout'] : 90;
        }

    }

    public function connect($config=null){

        if($config != null){

            $this->host = $config['host'];

            $this->username = $config['username'];

            $this->password = $config['password'];

            $this->port = isset($config['port']) ? $config['port'] : 21;

            $this->passive = $config['passive'];

            $this->ssl = isset($config['ssl']) ? $config['ssl'] : false;

            $this->timeout = isset($config['timeout']) ? $config['timeout'] : 90;
        }

        if(!$this->ssl){
            $this->ftpConn = ftp_connect($this->host, $this->port, $this->timeout);
        }
        else{
            $this->ftpConn = ftp_ssl_connect($this->host, $this->port, $this->timeout);
        }

        if(!$this->ftpConn){
            return false;
        }

        if(!ftp_login($this->ftpConn, $this->username, $this->password)){
            return false;
        }

        ftp_pasv($this->ftpConn, $this->passive);

        return true;

    }

    public function logout(){
        if(isset($this->ftpConn)){
            ftp_close($this->ftpConn);
        }
    }


    public function getConnection(){
        return $this->ftpConn;
    }

    public function uploadFile($src_file, $dest_file, $mode=null){

        if($mode == null)
            $mode = $this->checkTransferModeByFile($src_file);

        try{
            return ftp_put($this->ftpConn, $dest_file, $src_file, $mode);
        }
        catch(\Exception $ex){
            return false;
        }
    }

    public function downloadFile($src_file, $dest_file, $mode=null){

        if($mode == null){
            $ext = end(explode('.', $src_file));
            $mode = $this->checkTransferModeByExtension($ext);
        }

        try{

            if(is_resource($dest_file)){
                return ftp_fget($this->ftpConn, $dest_file, $src_file, $mode);
            }
            else{
                return ftp_get($this->ftpConn, $dest_file, $src_file, $mode);
            }

        }
        catch(\Exception $ex){
            return false;
        }

    }

    public function delete($file_name){
        try{
            return ftp_delete($this->ftpConn, $file_name);
        }
        catch(\Exception $ex){
            return false;
        }
    }

    public function makeDir($dir_name){

        if(empty($dir_name))
            return false;

        try{
            return ftp_mkdir($this->ftpConn, $dir_name);
        }
        catch(\Exception $ex){
            return false;
        }
    }


    public function changeDir($new_dir){

        if(empty($new_dir))
            return false;

        try{
            return ftp_chdir($this->ftpConn, $new_dir);
        }
        catch(\Exception $ex){
            return false;
        }
    }

    public function moveUp(){
        try{
            return ftp_cdup($this->ftpConn);
        }
        catch(\Exception $ex){
            return false;
        }
    }

    public function changeMode($file_name, $mode=0644){
        if(empty($file_name))
            return false;

        try{
            return ftp_chmod($this->ftpConn, $mode, $file_name);
        }
        catch(\Exception $ex){
            return false;
        }
    }

    private function checkTransferModeByFile($file){

        $parts = pathinfo($file);

        if(!isset($parts['extension']))
            return FTP_BINARY;

        return $this->checkTransferModeByExtension($parts['extension']);

    }

    private function checkTransferModeByExtension($extension){
        $ext_arr = array(
            'am', 'asp', 'bat', 'c', 'cfm', 'cgi', 'conf',
            'cpp', 'css', 'dhtml', 'diz', 'h', 'hpp', 'htm',
            'html', 'in', 'inc', 'js', 'm4', 'mak', 'nfs',
            'nsi', 'pas', 'patch', 'php', 'php3', 'php4', 'php5',
            'phtml', 'pl', 'po', 'py', 'qmail', 'sh', 'shtml',
            'sql', 'tcl', 'tpl', 'txt', 'vbs', 'xml', 'xrc', 'csv'
        );

        if(in_array(strtolower($extension),$ext_arr))
            return FTP_ASCII;
        else
            return FTP_BINARY;
    }



    public function __destruct(){
        $this->logout();
    }

}