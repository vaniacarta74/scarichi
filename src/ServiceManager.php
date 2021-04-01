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
    private static $telegram = TELSCARICHI;
    private $globalSend;
    private $serConfig;
    private $service;    
    private $token;
    private $url;
    private $serParams;
    private $method;
    private $key;
    private $isJson;
    private $isAsync;
    private $printFunction;
    private $withBody;
    private $params;
    private $responses;
    protected $message;
    private $start;
        
    /**
     * @param string $service
     * @param string|null $token
     * @param array $params
     * @param array $config
     * @throws \Throwable
     */
    public function __construct(string $service, ?string $token = null, ?array $params = null, ?array $config = null)
    {
        try {        
            $this->setStart();
            $this->checkService($service, $config);
            $this->setGlobalSend();        
            $this->setToken($token);
            $this->setUrl();
            $this->setSerParams();
            $this->setMethod();
            $this->setKey();
            $this->setIsJson();
            $this->setIsAsync();
            $this->setPrintFunction();
            $this->setWithBody();
            $this->setParams($params);
            $this->callService();
            $this->setMessage();
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
    private function setStart() : void
    {
        try {        
            $this->start = Utility::getMicroTime();
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
    private function setGlobalSend() : void
    {
        try {
            if (isset($this->serConfig) && array_key_exists('globalSend', $this->serConfig)) {
                $globalSend = $this->serConfig['globalSend'];
            } else {
                $globalSend = GLOBALMSG;
            }
            $this->globalSend = $globalSend;
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        //@codeCoverageIgnoreEnd
    }
    
    /**
     * @param string $rawService
     * @param array $rawConfig
     * @return void
     * @throws \Throwable
     * @throws \Exception
     */
    private function checkService(string $rawService, ?array $rawConfig = null) : void
    {
        try {
            $service = filter_var($rawService, FILTER_SANITIZE_URL);
            if (!filter_var($service, FILTER_VALIDATE_URL)) {
                $config = $rawConfig ?? SERVICES;
                if (!array_key_exists($service, $config)) {
                    throw new \Exception('Configurazione servizio inesitente');
                }
                $serConfig = $config[$service];
            } else {
                $serConfig = null;
            } 
            $this->service = $service;
            $this->serConfig = $serConfig;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * @return void
     * @throws \Throwable
     * @throws \Exception
     */
    private function setUrl() : void
    {
        try {
            if (isset($this->serConfig)) {
                if (!array_key_exists('host', $this->serConfig) || !array_key_exists('path', $this->serConfig)) {
                    throw new \Exception('Configurazione non valida');
                }
                $host = $this->serConfig['host'];
                if (defined($host)) {
                    $url = 'http://' . constant($host) . '/' . $this->serConfig['path'];
                } else {
                    $url = 'http://' . $host . '/' . $this->serConfig['path'];
                }  
            } else {
                $url = $this->service;
            }
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                throw new \Exception('Url non valido');
            }
            $this->url = $url;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * @param string|null $rawToken
     * @return void
     * @throws \Throwable
     * @throws \Exception
     */
    private function setToken(?string $rawToken = null) : void
    {
        try {
            if (isset($rawToken)) {
                if (isset($this->serConfig)) {
                    if (array_key_exists('params', $this->serConfig) && array_key_exists($rawToken, $this->serConfig['params'])) {
                        $token = $rawToken;
                    } else {
                        throw new \Exception('Configurazione o token non valido');
                    }
                } else {
                    $token = $rawToken;
                }
            } else {
                if (isset($this->serConfig)) {
                    $token = $this->service;
                } else {
                    $urlParts = explode('?', $this->service); 
                    $token = $urlParts[0];
                }
            }
            $this->token = $token;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * @return void
     * @throws \Throwable
     * @throws \Exception
     */
    private function setSerParams() : void
    {
        try {
            if (isset($this->serConfig)) {
                if (array_key_exists('params', $this->serConfig) && array_key_exists($this->token, $this->serConfig['params'])) {
                    $serParams = $this->serConfig['params'][$this->token];
                } else {
                    throw new \Exception('Configurazione parametri non valida');
                }
            } else {
                $serParams = [];
            }
            $this->serParams = $serParams;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * @return void
     * @throws \Throwable
     * @throws \Exception
     */
    private function setMethod() : void
    {
        try {
            if (isset($this->serConfig)) {
                if (array_key_exists('method', $this->serConfig)) {
                    $method = $this->serConfig['method'];            
                    if (!in_array($method, array('GET','POST','PUT','PATCH','DELETE'))) {
                        throw new \Exception('Metodo HTTP non ammesso');
                    }
                } else {
                    throw new \Exception('Configurazione metodo non valida');
                }
            } else {
                $method = 'GET';
            }
            $this->method = $method;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * @return void
     * @throws \Throwable
     * @throws \Exception
     */
    private function setKey() : void
    {
        try {
            if (isset($this->serConfig)) {
                if (array_key_exists('key', $this->serConfig)) {
                    $key = $this->serConfig['key'];
                } else {
                    throw new \Exception('Configurazione key non valida');
                }
            } else {
                $key = null;
            }
            $this->key = $key;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * @return void
     * @throws \Throwable
     * @throws \Exception
     */
    private function setIsJson() : void
    {
        try {
            if (isset($this->serConfig)) {
                if (array_key_exists('isJson', $this->serConfig)) {
                    $isJson = $this->serConfig['isJson'];
                } else {
                    throw new \Exception('Configurazione isJson non valida');
                }
            } else {
                $isJson = false;
            }
            $this->isJson = $isJson;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * @return void
     * @throws \Throwable
     * @throws \Exception
     */
    private function setIsAsync() : void
    {
        try {
            if (isset($this->serConfig)) {
                if (array_key_exists('isAsync', $this->serConfig)) {
                    $isAsync = $this->serConfig['isAsync'];
                } else {
                    throw new \Exception('Configurazione isAsync non valida');
                }
            } else {
                $isAsync = false;
            }
            $this->isAsync = $isAsync;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * @return void
     * @throws \Throwable
     * @throws \Exception
     */
    private function setPrintFunction() : void
    {
        try {
            if (isset($this->serConfig)) {
                if (array_key_exists('printf', $this->serConfig)) {
                    $function = $this->serConfig['printf'];
                    if (isset($function) && method_exists($this, $function)) {
                        $className = str_replace(__NAMESPACE__ . '\\', '', get_class());
                        $printf = $className . '::' . $function;
                    } else {
                        $printf = null;
                    }                    
                } else {
                    throw new \Exception('Configurazione printf non valida');
                }
            } else {
                $printf = null;
            }
            $this->printFunction = $printf;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * @return void
     * @throws \Throwable
     * @throws \Exception
     */
    private function setWithBody() : void
    {
        try {
            if (isset($this->serConfig)) {
                if (array_key_exists('withBody', $this->serConfig)) {
                    $withBody = $this->serConfig['withBody'];
                } else {
                    throw new \Exception('Configurazione withBody non valida');
                }
            } else {
                $withBody = false;
            }
            $this->withBody = $withBody;
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
            if (count($this->serParams) > 0) {
                $defaultParams = $this->getDefaults();
                if (count($params) > 0) {
                    $purged = $this->purgeParams($params);
                    $filled = $this->fillParams($purged, $defaultParams);
                } else {
                    $filled[] = $defaultParams;
                }                
            } else {
                $filled = $params;
            }
            return $filled;
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
    private function getDefaults() : array
    {
        try {
            $defParams = [];
            foreach ($this->serParams as $key => $default) {
                $defParams[$key] = $default['default'];
            }
            return $defParams;
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        //@codeCoverageIgnoreEnd
    }
    
    /**
     * @param array $multiQueryParams
     * @return array
     * @throws \Throwable
     */
    private function purgeParams(?array $params = null) : array
    {
        try {
            $multiQueryParams = $params ?? [];
            $multiPurged = [];
            foreach ($multiQueryParams as $idCall => $queryParams) {
                $purged = []; 
                foreach ($queryParams as $key => $value) {
                    if (array_key_exists($key, $this->serParams)) {
                        $purged[$key] = $value;
                    }                    
                }
                $multiPurged[$idCall] = $purged;
            }
            return $multiPurged;
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        //@codeCoverageIgnoreEnd
    }
    
    /**
     * @param array $multiPurgedParams
     * @param array $defaultParams
     * @return array
     * @throws \Throwable
     */
    private function fillParams(?array $purged = null, ?array $default = null) : array
    {
        try {
            $multiPurgedParams = $purged ?? [];
            $defaultParams = $default ?? [];
            $filled = [];
            foreach ($multiPurgedParams as $idCall => $purgedParams) {
                $filled[$idCall] = $purgedParams + $defaultParams;
            }
            return $filled;
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        //@codeCoverageIgnoreEnd
    }
    
    /**
     * @param array $params
     * @return void
     * @throws \Throwable
     * @throws \Exception
     */
    private function setParams(?array $params = null) : void
    {
        try {
            $defaults = [
                [
                    'url' => $this->url,
                    'method' => $this->method,
                    'params' => [],
                    'isJson' => null,
                    'key' => null
                ]
            ];
            $setParams = [];
            $postParams = $this->buildParams($params);
            if (count($postParams) === 0 && $this->method === 'POST') {
                throw new \Exception('Parametri metodo POST assenti');
            } elseif (count($postParams) === 0 && $this->method !== 'POST') {
                $setParams = $defaults;
            } else {
                $setParams = $this->setCallParams($postParams);
            }
            $this->params = $setParams;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * @param array $rawParams
     * @return array
     * @throws \Throwable
     */
    private function setCallParams(?array $rawParams = null) : array
    {
        try {
            $postParams = $rawParams ?? [];
            $prevKey = '';
            $setParams = [];
            foreach ($postParams as $idCall => $params) {            
                $queryParams = $this->setQueryParams($params);
                $urlAndParams = $this->setUrlAndParams($queryParams);
                $callParams['url'] = $urlAndParams['url']; 
                $callParams['params'] = $urlAndParams['params'];                
                $callParams['method'] = $this->method;
                $callParams['isJson'] = $this->isJson;
                $callParams['key'] = $this->setCallKey($idCall, $params, $prevKey);                
                $prevKey = $callParams['key'];
                $setParams[] = $callParams;
            }
            return $setParams;
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        //@codeCoverageIgnoreEnd
    }
    
    /**
     * @param array $queryParams
     * @return array
     * @throws \Throwable
     * @throws \Exception
     */
    private function setUrlAndParams(array $queryParams) : array
    {
        try {
            $urlAndParams['url'] = $this->url;
            $urlAndParams['params'] = [];
            if (count($queryParams) > 0) {
                if ($this->method === 'POST') {
                    $urlAndParams['params'] = $queryParams;
                } else {
                    $urlAndParams['url'] .= '?' . http_build_query($queryParams);;
                }
            } else {
                if ($this->method === 'POST') {
                    throw new \Exception('Nessun parametro con metodo POST');
                }
            }            
            return $urlAndParams;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * @param array $params
     * @return array
     * @throws \Throwable
     */
    private function setQueryParams(array $params) : array
    {
        try {            
            if (count($this->serParams) > 0) {
                $queryParams = [];
                foreach ($params as $key => $value) {
                    if (array_key_exists($key, $this->serParams) && $this->serParams[$key]['tosend']) {
                        $queryParams[$key] = $value;
                    }
                }
            } else {
                $queryParams = $params;
            }
            return $queryParams;
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        //@codeCoverageIgnoreEnd
    }
    
    /**
     * @param string $i
     * @param array $params
     * @param string $prevKey
     * @return string|null
     * @throws \Throwable
     */
    private function setCallKey(string $i, array $params, string $prevKey) : ?string
    {
        try {
            if (isset($this->key)) {
                if (array_key_exists($this->key, $params)) {
                    $currentKey = $params[$this->key];                                        
                } else {
                    $currentKey = str_replace('*', $i, $this->key);                    
                }
                if ($currentKey !== $prevKey) {
                    $key = $currentKey;
                } else {
                    $key = $currentKey . '.' . $i;
                }
            } else {
                $key = null;
            }            
            return $key;
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
    private function callService() : void
    {
        try {
            $responses = [];
            $n_param = count($this->params);
            if ($n_param > 0) {
                if ($this->isAsync && $n_param > 1) {
                    $responses = Curl::runMultiAsync($this->params, $this->printFunction);
                } else {
                    $responses = Curl::runMultiSync($this->params, $this->printFunction);
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
     * @return array
     * @throws \Throwable
     * @throws \Exception
     */
    protected static function checkServiceResponse(string $report, int $debug_level) : array
    {
        try {
            $response = json_decode($report, true);
            if (!$response['ok']) {
                $message = 'Elaborazione fallita.';
                $message .= ($debug_level === 1) ? ' Verificare il log degli errori (' . realpath(LOG_PATH) . '/' . ERROR_LOG . ').' : '';
                $checked[] = $message;
            } else {
                $checked = $response['response']['body'];
            }
            return $checked;
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        //@codeCoverageIgnoreEnd
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
            if (array_key_exists($this->token, $header['title'])) {
                $telegramHeader['title'] = '<b>' . $header['title'][$this->token] . '</b>';
            } else {
                $telegramHeader['title'] = '<b>' . $this->token . '</b>';
            }
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
            if (array_key_exists($this->token, $footer['report'])) {
                $telegramFooter['totals'] = '<b>' . $footer['report'][$this->token]['out'] . '</b> ' . $repString;
            } else {
                $telegramFooter['totals'] = '';
            }           
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
            $repConf = self::$telegram['footer']['report'];
            if (array_key_exists($this->token, $repConf)) {
                $repTitle = $repConf[$this->token]['in'];
                $totals = [];
                foreach ($responses as $json) {
                    $response = json_decode($json, true);
                    if ($response['ok']) {                        
                        $resFooter = str_replace(' ', '', str_replace($repTitle, '', $response['response']['footer'][2]));
                        $this->setTotals($totals, $resFooter);
                    }
                }
                $repString = $this->formatTotals($totals);
            } else {
                $repString = '';
            }
            return $repString;
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {        
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;        
        }
        //@codeCoverageIgnoreEnd
    }
    
    /**
     * @param array $totals
     * @param string $resFooter
     * @return void
     * @throws \Throwable
     */
    private function setTotals(array &$totals, string $resFooter) : void
    {
        try {            
            $reports = explode('|', $resFooter);
            foreach ($reports as $report) {
                $vars = explode(':', $report);                
                if (count($vars) > 1) {
                    $var = ucfirst($vars[0]);
                    if (isset($totals[$var])) {
                        $totals[$var] += intval(str_replace('.', '', $vars[1]));
                    } else {
                        $totals[$var] = intval(str_replace('.', '', $vars[1]));
                    }
                }
            }                        
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {        
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;        
        }
        //@codeCoverageIgnoreEnd
    }
    
    /**
     * @param array $totals
     * @return string
     * @throws \Throwable
     */
    private function formatTotals(array $totals) : string
    {
        try {
            $repString = 'n.d.';
            if (count($totals) > 0) {
                foreach ($totals as $key => $value) {
                   $formatted[$key] = number_format($value, 0, ',', '.');
                }
                $repString = str_replace('=', ': ' , str_replace('&', ' | ', http_build_query($formatted)));
            }            
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
            $telegram = '';            
            if (count($responses) > 0) {
                $header = $this->setHeader();                
                $body = $this->setBody($responses);
                $repString = $this->setReport($responses);
                $footer = $this->setFooter($repString);
                $telegram = $header['title'] . PHP_EOL . $header['start'] . PHP_EOL . $body . $footer['stop'] . PHP_EOL . $footer['time'] . PHP_EOL . $footer['totals'];
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
     * @param array $responses
     * @return string
     * @throws \Throwable
     */
    private function setBody(array $responses) : string
    {
        try {
            if ($this->globalSend || !$this->withBody) {
                $body = '';
            } else {
                $message = '';
                foreach ($responses as $json) {
                    $response = json_decode($json, true);
                    if ($response['ok']) {
                        $this->setBodyMsg($message, $response['response']['body']);
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
    
    /**
     * @param string $message
     * @param array $bodies
     * @return void
     * @throws \Throwable
     */
    private function setBodyMsg(string &$message, array $bodies) : void
    {
        try {
            foreach ($bodies as $key => $body) {
                if (is_numeric($key)) {
                    $message .= $body . PHP_EOL;
                } else {
                    $message .= '<b>' . $key . ':</b> ' . $body . PHP_EOL;
                }
            }
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {        
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;        
        }
        //@codeCoverageIgnoreEnd
    }
}
