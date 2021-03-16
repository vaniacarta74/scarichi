<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi;

use vaniacarta74\Scarichi\Accessor;
use vaniacarta74\Scarichi\Curl;
use vaniacarta74\Scarichi\Error;
use vaniacarta74\Scarichi\Utility;

/**
 * Description of ServiceManager
 *
 * @author Vania
 */
class ServiceManager extends Accessor
{
    protected static $globalSend = GLOBALMSG;
    protected static $services = SERVICES;
    protected static $telegram = TELSCARICHI;
    protected $service;
    protected $token;
    protected $params;
    protected $responses;
    protected $message;
    protected $start;
        
    /**
     * @param string $service
     * @param array $params
     * @throws \Exception
     */
    public function __construct(string $service, ?string $token = null, ?array $params = null)
    {
        $this->start = Utility::getMicroTime();
        if (!array_key_exists($service, self::$services)) {
            throw new \Exception('Servizio inesistente');
        }
        $this->service = $service;
        $this->setToken($token);        
        $postParams = $this->buildParams($params);        
        $this->setParams($postParams);
        $this->callService();
        $this->setMessage();
    }
    
    /**
     * @return string
     * @throws \Throwable
     * @throws \Exception
     */
    private function setUrl() : string
    {
        try {        
            $host = self::$services[$this->service]['host'];
            if ($host === 'localhost') {
                $url = 'http://' . LOCALHOST . '/' . self::$services[$this->service]['path'];
            } elseif ($host === 'remotehost') {
                $url = 'http://' . REMOTE_HOST . '/' . self::$services[$this->service]['path'];
            } else {
                $url = 'http://' . $host . '/' . self::$services[$this->service]['path'];
            }
            if (!Utility::checkUrl($url)) {
                throw new \Exception('Url non valido');
            }            
            return $url;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * @param string|null $token
     * @return void
     * @throws \Throwable
     */
    private function setToken(?string $rawToken = null) : void
    {
        try {
            if (isset($rawToken)) {
                if (array_key_exists($rawToken, self::$services[$this->service]['params'])) {
                    $token = $rawToken;
                } else {
                    throw new \Exception('Token non valido');
                }                
            } else {
                $token = $this->service;
            }
            $this->token = $token;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * @param array $postParams
     * @return array
     * @throws \Throwable
     */
    private function buildParams(?array $postParams = null) : array
    {
        try {
            $params = $postParams ?? [];
            $serviceParams = self::$services[$this->service]['params'][$this->token];            
            if (count($serviceParams) > 0) {
                $defaultParams = $this->getDefaults($serviceParams);
                if (count($params) > 0) {
                    $purged = $this->purgeParams($params, $defaultParams);
                    $filled = $this->fillParams($purged, $defaultParams);
                } else {
                    $filled[] = $defaultParams;
                }                
            } else {
                $filled = $params;
            }
            return $filled;            
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * @param array $serviceParams
     * @return array
     * @throws \Throwable
     */
    private function getDefaults(array $serviceParams) : array
    {
        try {
            foreach ($serviceParams as $key => $default) {
                $defParams[$key] = $default['default'];
            }
            return $defParams;            
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * @param array $multiQueryParams
     * @param array $defaultParams
     * @return array
     * @throws \Throwable
     */
    private function purgeParams(array $multiQueryParams, array $defaultParams) : array
    {
        try {
            foreach ($multiQueryParams as $idCall => $queryParams) {
                foreach ($queryParams as $key => $value) {
                    if (array_key_exists($key, $defaultParams)) {
                        $purged[$idCall][$key] = $value;
                    }                    
                }
            }
            return $purged;            
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * @param array $multiPurgedParams
     * @param array $defaultParams
     * @return array
     * @throws \Throwable
     */
    private function fillParams(array $multiPurgedParams, array $defaultParams) : array
    {
        try {
            foreach ($multiPurgedParams as $idCall => $purgedParams) {
                $filled[$idCall] = array_merge($purgedParams, $defaultParams); 
            }
            return $filled;            
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * @param array $postParams
     * @return void
     * @throws \Throwable
     * @throws \Exception
     */
    private function setParams(array $postParams) : void
    {
        try {        
            $i = 0;
            $setParams = [];
            $url = $this->setUrl();
            $method = self::$services[$this->service]['method'];            
            if (!in_array($method, array('GET','POST','PUT','PATCH','DELETE'))) {
                throw new \Exception('Metodo HTTP non ammesso');
            }
            $key = self::$services[$this->service]['key'];
            $isJson = self::$services[$this->service]['isJson'];
            if (count($postParams) === 0 && $method === 'POST') {
                throw new \Exception('Parametri metodo POST assenti');
            } elseif (count($postParams) === 0 && $method !== 'POST') {
                $setParams = [
                    [
                        'url' => $url,
                        'method' => $method,
                        'params' => [],
                        'isJson' => null,
                        'key' => null
                    ]
                ];
            } else {
                foreach ($postParams as $idCall => $params) {            
                    $callParams = [];
                    if ($method === 'POST') {
                        $callParams['url'] = $url;  
                        $callParams['params'] = $params;
                    } else {
                        $callParams['url'] = $url . '?' . http_build_query($params);
                        $callParams['params'] = [];
                    }
                    $callParams['method'] = $method;
                    $callParams['isJson'] = $isJson;
                    $callParams['key'] = $this->setKey($key, $idCall, $params);
                    $setParams[] = $callParams;
                }
            }
            $this->params = $setParams;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * @param string|null $rawKey
     * @param int $i
     * @param array $params
     * @return string|null
     * @throws \Throwable
     */
    private function setKey(?string $rawKey, int $i, array $params) : ?string
    {
        try {      
            if (isset($rawKey) && array_key_exists($rawKey, $params)) {
                $key = $params[$rawKey];
            } elseif (isset($rawKey) && !array_key_exists($rawKey, $params)) {
                $key = str_replace('*', $i, $rawKey);
            } else {
                $key = null;
            }            
            return $key;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }

    /**
     * @return void
     * @throws \Throwable
     */
    private function callService() : void
    {
        try {
            $responses = [];
            $setParams = $this->params;
            $async = self::$services[$this->service]['isAsync'];
            $className = str_replace(__NAMESPACE__ . '\\', '', get_class());
            $printFunction = $className . '::formatResponse';
            $n_param = count($setParams);
            if ($n_param > 0) {
                if ($async && $n_param > 1) {
                    $responses = Curl::runMultiAsync($setParams, $printFunction);
                } else {
                    $responses = Curl::runMultiSync($setParams, $printFunction);
                }
            }
            $this->responses = $responses;
        // @codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * @param string $report
     * @param int $debug_level
     * @return string
     * @throws \Throwable
     * @throws \Exception
     */
    protected static function checkServiceResponse(string $report, int $debug_level) : array
    {
        try {
            if ($debug_level > 2) {
                throw new \Exception('Livello di debug non definito');
            }
            $response = json_decode($report, true);
            if (!$response['ok']) {
                $message = 'Elaborazone fallita.';
                if ($debug_level === 1) {
                    $message .= ' Verificare il log degli errori (' . realpath(LOG_PATH) . '/' . ERROR_LOG . ').';
                }
                $checked[] = $message;
            } else {
                $checked = $response['response']['body'];
            }
            return $checked;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * @param string $report
     * @param int $i
     * @param string $key
     * @return string
     * @throws \Throwable
     */
    public static function formatResponse(string $report, int $i, string $key) : string
    {
        try {
            $responses = self::checkServiceResponse($report, DEBUG_LEVEL);           
            $j = 0;
            $message = '';
            $nSubProccess = count($responses);
            foreach ($responses as $htmlMessage) {
                $response = htmlspecialchars(strip_tags($htmlMessage));
                if ($nSubProccess === 1) {
                    $message .= $i . ') PID ' . $key . ': ' . $response . PHP_EOL;
                } else {
                    $message .= $i . '.' . $j . ') PID ' . $key . '.' . $j . ': ' . $response . PHP_EOL;
                }
                $j++;
            }    
            return $message;
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {        
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;        
        }
        //@codeCoverageIgnoreEnd
    }
    
    /**
     * @return array
     * @throws \Throwable
     */
    private function setHeader() : array
    {
        try {
            $header = self::$telegram['header'];
            $telegramHeader['title'] = '<b>' . $header['title'][$this->token] . '</b>';
            $telegramHeader['start'] = $header['start'] . ' <b>' . Utility::microToLatinTime($this->start) . '</b>';
                    
            return $telegramHeader;
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {        
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;        
        }
        //@codeCoverageIgnoreEnd
    }
    
    /**
     * @param string $repString
     * @return array
     * @throws \Throwable
     */
    private function setFooter(string $repString) : array
    {
        try {
            $footer = self::$telegram['footer'];                       
            $telegramFooter['totals'] = '<b>' . $footer['report'][$this->token]['out'] . '</b> ' . $repString;
            $telegramFooter['stop'] = $footer['stop'] . ' <b>' . Utility::getLatinTime() . '</b>';
            $telegramFooter['time'] = $footer['time'] . ' <b>' . Utility::benchmark($this->start) . '</b>';
                    
            return $telegramFooter;
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {        
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;        
        }
        //@codeCoverageIgnoreEnd
    }
    
    /**
     * @param array $responses
     * @return string
     * @throws \Throwable
     */
    private function setReport(array $responses) : string
    {
        try {
            $repTitle = self::$telegram['footer']['report'][$this->token]['in'];
            foreach ($responses as $json) {
                $response = json_decode($json, true);
                if ($response['ok']) {                        
                    $resFooter = str_replace(' ', '', str_replace($repTitle, '', $response['response']['footer'][2]));
                    $reports = explode('|', $resFooter);
                    foreach ($reports as $report) {
                        $vars = explode(':', $report);
                        $var = ucfirst($vars[0]);
                        if (isset($totals[$var])) {
                            $totals[$var] += intval($vars[1]);
                        } else {
                            $totals[$var] = intval($vars[1]);
                        }
                    }         
                }
            }
            $repString = str_replace('=', ': ' , str_replace('&', ' | ', http_build_query($totals)));
            
            return $repString;
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {        
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;        
        }
        //@codeCoverageIgnoreEnd
    }
    
    /**
     * @return void
     * @throws \Throwable
     */
    private function setMessage() : void
    {
        try {
            $responses = $this->responses ?? [];
            if (count($responses) > 0) {
                $header = $this->setHeader(); 
                $body = $this->setBody($responses);
                $repString = $this->setReport($responses);
                $footer = $this->setFooter($repString);

                $telegram = $header['title'] . PHP_EOL . $header['start'] . PHP_EOL . $body . $footer['stop'] . PHP_EOL . $footer['time'] . PHP_EOL . $footer['totals'];
            } else {
                $telegram = '';
            }        
            $this->message = $telegram;
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {        
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;        
        }
        //@codeCoverageIgnoreEnd
    }
    
    /**
     * @return void
     * @throws \Throwable
     */
    private function setBody(array $responses) : string
    {
        try {
            if (self::$globalSend || !self::$services[$this->service]['withBody']) {
                $body = '';
            } else {
                $message = '';
                foreach ($responses as $json) {
                    $response = json_decode($json, true);
                    if ($response['ok']) {
                        $message .= $response['response']['body'][0] . PHP_EOL;                        
                    }
                }
                $body = PHP_EOL . $message . PHP_EOL;
            }
            
            return $body;
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {        
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;        
        }
        //@codeCoverageIgnoreEnd
    }
}
