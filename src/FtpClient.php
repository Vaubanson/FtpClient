<?php
/**
 * Created by PhpStorm.
 * User: Vaubanson
 * Date: 25/10/2017
 * Time: 11:44
 */

namespace Vaubanson\Ftp;


class FtpClient implements Client
{
    private $login;

    public function __construct()
    {
        $this->login = Connexion::getInstance();
        $this->login->params([
            'ssl' => false,
            'host' => '192.168.56.1',
            'port' => 21,
            'timeout' => 91
        ]);
    }

    public function downloadFile($destinationFile, $fileToDownload, $option = [])
    {
        // TODO: Implement downloadFile() method.
        try{
            $this->login->connected();
            $ftpUser = $this->login->setCredential("aime","admin");
            if (count($option) > 0){
                $content = [];
                if ($ftpUser['status'] == true && $this->login->passiveMode() == true) {
                    $dir = $option["directory"] != null ? $option["directory"] : '.';
                    $dir .= $option["filter"] != null ? '/*.'.$option["filter"] : '';
                    $liste_fichiers = ftp_nlist($ftpUser['ftp'], $dir);
                    foreach($liste_fichiers as $fichier) {
                        $content [] = $fichier;
                    }
                }
                foreach ($content as $value) {
                    $file = explode("/", $value);
                    ftp_get($ftpUser['ftp'], $destinationFile."/".end($file), $option["directory"]."/".end($file), FTP_BINARY);
                }
            }else{
                if ($ftpUser['status'] == true && $this->login->passiveMode() == true) {
                    ftp_get($ftpUser['ftp'], $destinationFile, $fileToDownload, FTP_BINARY);
                }
            }
            $this->login->closeFtp();
        }catch (\Exception $exception){

        }
    }

    public function directoryList($directory = null, $filtre = null)
    {
        // TODO: Implement directoryList() method.
        try{
            $this->login->connected();
            $ftpUser = $this->login->setCredential("aime","admin");
            $content = [];
            if ($ftpUser['status'] && $this->login->passiveMode() == true){
                $dir = $directory != null ? $directory : '.';
                $dir .= $filtre != null ? '/*.'.$filtre : '';
                $liste_fichiers = ftp_nlist($ftpUser['ftp'], $dir);
                foreach($liste_fichiers as $fichier)
                {
                    $content [] = $fichier;
                }
            }
            $this->login->closeFtp();
            return $content;
        }catch (\Exception $exception) {}
    }

    public function uploadFileToFtpDirectory($destinationFile, $fileToUpload, $option = [])
    {
        // TODO: Implement uploadFileToFtpDirectory() method.
        try{
            $this->login->connected();
            $ftpUser = $this->login->setCredential("aime","admin");
            if (count($option) > 0){
                $content = [];
                if($dossier = opendir($option['directory'])){
                    $content = [];
                    while(false !== ($fichier = readdir($dossier))) {
                        if($fichier!="." AND $fichier!=".." AND !is_dir($fichier)){
                            $content [] = $fichier;
                        }
                    }
                    closedir($dossier);
                }
                if ($ftpUser['status'] == true && $this->login->passiveMode() == true){
                    foreach ($content as $value) {
                        ftp_put($ftpUser['ftp'], $destinationFile."/$value", $option["directory"]."/$value", FTP_BINARY);
                    }
                }
            }else{
                if ($ftpUser['status'] == true && $this->login->passiveMode() == true) {
                    ftp_put($ftpUser['ftp'], $destinationFile, $fileToUpload, FTP_BINARY);
                }
            }
            $this->login->closeFtp();
        }catch (\Exception $exception){

        }
    }
}