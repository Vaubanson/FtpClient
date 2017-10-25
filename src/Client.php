<?php
/**
 * Created by PhpStorm.
 * User: Vaubanson
 * Date: 25/10/2017
 * Time: 11:41
 */

namespace Vaubanson\Ftp;


interface Client
{

    public function downloadFile($destinationFile, $fileToDownload, $option = []);

    public function directoryList($directory = null, $filtre = null);

    public function uploadFileToFtpDirectory($destinationFile, $fileToUpload, $option = []);
}