<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi;

use vaniacarta74\Scarichi\Error;
use vaniacarta74\Scarichi\Utility;

/**
 * Description of Curl
 *
 * @author Vania
 */
class Curl
{
    
    /**
     * Stampa il tempo trascorso da una certa data.
     *
     * Il metodo benchmark() fornisce l'intervallo di tempo intercorso da una
     * certa data. Viene utilizzato per calcolare il tempo di esecuzione della
     * procedura.
     *
     * @param string $strDateTime Data nel formato "YYYY-mm-dd HH:ii:ss.millisec"
     * @return string Intervallo intercorso nel formato "secondi,millisecondi"
     */
    public static function run(string $url, ?array $params = null, ?bool $json = false) : string
    {
        try {
            if (isset($params)) {
                if (count($params) === 0) {
                    throw new \Exception('Parametri curl non definiti');
                }
                if ($json) {
                    $ch = self::setJson($url, $params);
                } else {
                    $ch = self::set($url, $params);
                }
            } else {
                $ch = self::set($url);
            }
            $report = self::exec($ch);
            
            return $report;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * Stampa il tempo trascorso da una certa data.
     *
     * Il metodo benchmark() fornisce l'intervallo di tempo intercorso da una
     * certa data. Viene utilizzato per calcolare il tempo di esecuzione della
     * procedura.
     *
     * @param string $ch Data nel formato "YYYY-mm-dd HH:ii:ss.millisec"
     * @return string Intervallo intercorso nel formato "secondi,millisecondi"
     */
    public static function exec($ch) : string
    {
        try {
            if (!is_resource($ch)) {
                throw new \Exception('Risorsa non definita');
            }
            $report = curl_exec($ch);
            curl_close($ch);
            
            return $report;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * Stampa il tempo trascorso da una certa data.
     *
     * Il metodo benchmark() fornisce l'intervallo di tempo intercorso da una
     * certa data. Viene utilizzato per calcolare il tempo di esecuzione della
     * procedura.
     *
     * @param string $strDateTime Data nel formato "YYYY-mm-dd HH:ii:ss.millisec"
     * @return string Intervallo intercorso nel formato "secondi,millisecondi"
     */
    public static function set(string $url, ?array $params = null)
    {
        try {
            $ch = curl_init();            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            if (isset($params)) {
                if (count($params) > 0) {              
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, TIMEOUT);
                    curl_setopt($ch, CURLOPT_TIMEOUT, TIMEOUT);
                } else {
                    throw new \Exception('Parametri POST non definiti');
                }
            }            
            return $ch;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * Stampa il tempo trascorso da una certa data.
     *
     * Il metodo benchmark() fornisce l'intervallo di tempo intercorso da una
     * certa data. Viene utilizzato per calcolare il tempo di esecuzione della
     * procedura.
     *
     * @param string $strDateTime Data nel formato "YYYY-mm-dd HH:ii:ss.millisec"
     * @return string Intervallo intercorso nel formato "secondi,millisecondi"
     */
    public static function setJson(string $url, array $params)
    {
        try {
            if (count($params) === 0) {
                throw new \Exception('Parametri curl non definiti');
            }
            $json = json_encode($params);
            $header = [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($json)
            ];
            $ch = curl_init();            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, TIMEOUT);
            curl_setopt($ch, CURLOPT_TIMEOUT, TIMEOUT);
            
            return $ch;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * Stampa il tempo trascorso da una certa data.
     *
     * Il metodo benchmark() fornisce l'intervallo di tempo intercorso da una
     * certa data. Viene utilizzato per calcolare il tempo di esecuzione della
     * procedura.
     *
     * @param string $strDateTime Data nel formato "YYYY-mm-dd HH:ii:ss.millisec"
     * @return string Intervallo intercorso nel formato "secondi,millisecondi"
     */
    public static function multiSet(string $url, array $postParams, ?string $key = null) : array
    {
        try {
            if (count($postParams) === 0) {
                throw new \Exception('Parametri curl non definiti');
            }
            $handlers = [];
            foreach ($postParams as $params) {
                $ch = self::set($url, $params);
                if ($key) {
                    if (!array_key_exists($key, $params)) {
                        throw new \Exception('Chiave non presente nei parametri');
                    } else {
                        $handlers[$params[$key]] = $ch;
                    }
                } else {
                    $handlers[] = $ch;
                }
            }
            return $handlers;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * Stampa il tempo trascorso da una certa data.
     *
     * Il metodo benchmark() fornisce l'intervallo di tempo intercorso da una
     * certa data. Viene utilizzato per calcolare il tempo di esecuzione della
     * procedura.
     *
     * @param string $strDateTime Data nel formato "YYYY-mm-dd HH:ii:ss.millisec"
     * @return string Intervallo intercorso nel formato "secondi,millisecondi"
     */
    public static function runMultiSync(string $url, array $postParams, ?string $paramsKey = null, ?string $funcName = null) : string
    {
        try {
            if (count($postParams) === 0) {
                throw new \Exception('Parametri curl non definiti');
            }
            $handlers = self::multiSet($url, $postParams, $paramsKey);            
            $i = 1;
            $message = '';
            foreach ($handlers as $key => $ch) {
                $report = self::exec($ch);
                if ($funcName) {
                    echo Utility::callback($funcName, array($report, $i, $key));
                } else {
                    echo $report;
                }
                $message .= $report . PHP_EOL;
                $i++;
            }
            return $message;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * Stampa il tempo trascorso da una certa data.
     *
     * Il metodo benchmark() fornisce l'intervallo di tempo intercorso da una
     * certa data. Viene utilizzato per calcolare il tempo di esecuzione della
     * procedura.
     *
     * @param string $strDateTime Data nel formato "YYYY-mm-dd HH:ii:ss.millisec"
     * @return string Intervallo intercorso nel formato "secondi,millisecondi"
     */
    //public static function runMultiAsync(string $url, array $postParams, ?string $paramsKey = null, ?string $funcName = null) : void
    public static function runMultiAsync(string $url, array $postParams, ?string $paramsKey = null, ?string $funcName = null) : string
    {
        try {
            if (count($postParams) === 0) {
                throw new \Exception('Parametri curl non definiti');
            }
            $mh = curl_multi_init();
            $handlers = self::multiSet($url, $postParams, $paramsKey);
            foreach ($handlers as $ch) {
                curl_multi_add_handle($mh, $ch);
            }
            $i = 1;
            $message = '';
            do {
                $status = curl_multi_exec($mh, $still_running);
                if ($still_running) {
                    curl_multi_select($mh);
                }
                while ($info = curl_multi_info_read($mh)) {
                    $ch = $info['handle'];
                    $key = array_search($ch, $handlers);
                    $report = curl_multi_getcontent($ch);
                    if ($funcName) {
                        echo Utility::callback($funcName, array($report, $i, $key));
                    } else {
                        echo $report;
                    }
                    $message .= $report . PHP_EOL;
                    $i++;
                }
            } while ($still_running && $status == CURLM_OK);
            foreach ($handlers as $ch) {
                curl_multi_remove_handle($mh, $ch);
                curl_close($ch);
            }
            curl_multi_close($mh);
            return $message;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
}
