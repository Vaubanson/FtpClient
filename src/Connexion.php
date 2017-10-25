<?php
/**
 * Created by PhpStorm.
 * User: Vaubanson
 * Date: 25/10/2017
 * Time: 11:56
 */

namespace Vaubanson\Ftp;


class Connexion

{
    private  $config = [];
    private  $ftp;
    private static $instance;

    /**
     * @return Connexion
     * Return one Connexion of this Class
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)){
            self::$instance = new Connexion();
        }
        return self::$instance;
    }

    /**
     * @param array $config
     * get the connection parameters
     * @return $this
     */
    public function params(array $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @param string $key
     */
    private function getKeyReturn(string $keySearch)
    {
        foreach ($this->config as $key => $value){
            if ($key === $keySearch){
                return $this->config[$key];
            }
        }
    }

    /**
     * @return $this
     * connects the user to the ftp account
     */
    public function connected()
    {
        if (isset($this->config['ssl']) && $this->config['ssl'] != false){

            $this->ftp = ftp_ssl_connect(
                $this->config['host'],
                $this->config['port'],
                $this->config['timeout']
            );
        }else{
            $this->ftp = ftp_connect(
                $this->config['host'],
                $this->config['port'],
                $this->config['timeout']
            );
        }
        return $this;
    }

    /**
     * @param $username
     * @param $password
     * @return array
     * set credential of the compte ftp
     */
    public function setCredential($username, $password)
    {
        if (ftp_login($this->ftp, $username, $password)){

            return array('status' => true, 'ftp' => $this->ftp);
        }
        return array('status' => false);
    }

    /**
     * close ftp connexion
     */
    public function closeFtp()
    {
        ftp_close($this->ftp);
    }
    public function passiveMode()
    {
        return ftp_pasv($this->ftp, true);
    }
}