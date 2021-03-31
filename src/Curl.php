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
     * @param string $url
     * @param string/null $httpMethod
     * @param array/null $params
     * @param bool/null $json
     * @return string
     * @throws \Throwable
     */    
    public static function run(string $url, ?string $httpMethod = null, ?array $params = null, ?bool $json = null) : string
    {
        try {
            $method = $httpMethod ?? 'GET';
            $isJson = $json ?? false;
            if ($method === 'POST') {
                if (!isset($params) || count($params) === 0) {
                    throw new \Exception('Parametri curl non definiti');
                } else {
                    $ch = self::set($url, $method, $params, $isJson);
                }
            } else {
                $ch = self::set($url, $method);
            }            
            $report = self::exec($ch);
            
            return $report;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * @param string $url
     * @param string $method
     * @param array/null $params
     * @param bool/null $json
     * @return resource
     * @throws \Throwable
     */
    public static function set(string $url, string $method, ?array $params = null, ?bool $json = null)
    {
        try {
            if (!in_array($method, array('GET', 'POST', 'PUT', 'PATCH', 'DELETE'))) {
                throw new \Exception('Formato parametri non corretto o valori non ammessi');
            }
            $isJson = $json ?? false;
            $ch = curl_init();            
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, TIMEOUT);
            curl_setopt($ch, CURLOPT_TIMEOUT, TIMEOUT);
            switch ($method) {
                case 'GET':
                    curl_setopt($ch, CURLOPT_HEADER, false);
                    break;
                case 'POST':
                    if (isset($params) && count($params) > 0) {                        
                        if ($isJson) {
                            $posts = json_encode($params);
                            $header = [
                                'Content-Type: application/json',
                                'Content-Length: ' . strlen($posts)
                            ];
                            curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                        } else {
                            $posts = $params;
                            curl_setopt($ch, CURLOPT_HEADER, false);
                        }
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $posts);                    
                    } else {
                        throw new \Exception('Parametri POST non definiti');
                    }
                    break;
                default:
                    curl_setopt($ch, CURLOPT_HEADER, false);
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
                    break;                
            }
            return $ch;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * @param resource $ch
     * @return string
     * @throws \Throwable
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
     * @param array $setParams
     * @return array
     * @throws \Throwable
     * @throws \Exception
     */
    public static function multiSet(array $setParams) : array
    {
        try {
            if (count($setParams) === 0) {
                throw new \Exception('Parametri curl non definiti');
            }
            $handlers = [];
            foreach ($setParams as $idCall => $callParams) {
                $url = $callParams['url'];
                $method = $callParams['method'];
                $params = $callParams['params'];
                $isJson = $callParams['isJson'];
                $key = $callParams['key'];                
                $ch = self::set($url, $method, $params, $isJson);
                if (isset($key)) {
                    $handlers[$key] = $ch;
                } else {
                    $handlers[$idCall] = $ch;
                }
            }
            return $handlers;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * @param array $setParams
     * @param string|null $funcName
     * @return string
     * @throws \Throwable
     * @throws \Exception
     */
    public static function runMultiSync(array $setParams, ?string $funcName = null) : array
    {
        try {
            if (count($setParams) === 0) {
                throw new \Exception('Parametri curl non definiti');
            }
            $handlers = self::multiSet($setParams);            
            $i = 1;
            $responses = [];
            foreach ($handlers as $key => $ch) {
                $report = self::exec($ch);
                if (isset($funcName)) {
                    echo Utility::callback($funcName, array($report, $i, $key));
                }
                $responses[] = $report;
                $i++;
            }
            return $responses;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * 
     * @param array $setParams
     * @param string|null $funcName
     * @return string
     * @throws \Throwable
     * @throws \Exception
     */
    public static function runMultiAsync(array $setParams, ?string $funcName = null) : array
    {
        try {
            if (count($setParams) === 0) {
                throw new \Exception('Parametri curl non definiti');
            }
            $mh = curl_multi_init();
            $handlers = self::multiSet($setParams);
            foreach ($handlers as $ch) {
                curl_multi_add_handle($mh, $ch);
            }
            $i = 1;
            $responses = [];
            do {
                $status = curl_multi_exec($mh, $still_running);
                if ($still_running) {
                    curl_multi_select($mh);
                }
                while ($info = curl_multi_info_read($mh)) {
                    $ch = $info['handle'];
                    $key = array_search($ch, $handlers);
                    $report = curl_multi_getcontent($ch);
                    if (isset($funcName)) {
                        echo Utility::callback($funcName, array($report, $i, $key));
                    }
                    $responses[] = $report;
                    $i++;
                }
            } while ($still_running && $status == CURLM_OK);
            foreach ($handlers as $ch) {
                curl_multi_remove_handle($mh, $ch);
                curl_close($ch);
            }
            curl_multi_close($mh);
            return $responses;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
}
