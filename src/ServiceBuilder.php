<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi;

use vaniacarta74\Scarichi\ServiceManager;
use vaniacarta74\Scarichi\Error;
use vaniacarta74\Scarichi\Utility;

/**
 * Description of ServiceBuilder
 *
 * @author Vania
 */
class ServiceBuilder
{
    /**
     * @param array $serParams
     * @param array $multiParams
     * @param bool|null $sendMode
     * @return string
     * @throws \Throwable
     * @throws \Exception
     */
    public static function run(array $serParams, ?array $multiParams = null, ?bool $sendMode = false) : string
    {
        try {
            $params = null;
            if (array_key_exists('name', $serParams)) {
                $service = $serParams['name'];
            } else {
                throw new \Exception('Configurazione service list non valida: name neccessario');
            }
            $token = (array_key_exists('token', $serParams)) ? $serParams['token'] : null;           
            if (isset($multiParams)) {
                $params = self::setParams($serParams, $multiParams, $sendMode);
            }           
            $service = New ServiceManager($service, $token, $params);
            $message = $service->getMessage();            
            return $message;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }        
    }
    
    /**
     * @param array $serParams
     * @param array $multiParams
     * @param bool $sendMode
     * @return array
     * @throws \Throwable
     * @throws \Exception
     */
    public static function setParams(array $serParams, array $multiParams, bool $sendMode) : array
    {
        try {
            if (array_key_exists('method', $serParams) && array_key_exists('defaults', $serParams)) {
                $methodName = 'set' . $serParams['method'] . 'Params';
                if (method_exists(__CLASS__, $methodName)) {
                    $className = str_replace(__NAMESPACE__ . '\\', '', get_class());
                    $method = $className . '::' . $methodName;
                    $params = Utility::callback($method, array($serParams['defaults'], $multiParams, $sendMode));
                } else {
                    throw new \Exception('Metodo service list inesistente');
                }                    
            } else {
                throw new \Exception('Configurazione service list non valida');
            }
            return $params;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }        
    }
    
    /**
     * @param array $defaults
     * @param array $multiParams
     * @param bool $sendMode
     * @return array
     * @throws \Throwable
     */
    public static function setSyncParams(array $defaults, array $multiParams, bool $sendMode) : array
    {
        try {
            $params = [];
            self::setDefaults($params, $defaults, $sendMode);
            if (self::appendVar($params, $multiParams)) {
                self::setDelay($params, $multiParams);
            }
            return $params;
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        //@codeCoverageIgnoreEnd
    }
    
    /**
     * @param array $defaults
     * @param array $multiParams
     * @param bool $sendMode
     * @return array
     * @throws \Throwable
     */
    public static function setToCsvParams(array $defaults, array $multiParams, bool $sendMode) : array
    {
        try {
            return $multiParams;
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        //@codeCoverageIgnoreEnd
    }
    
    /**
     * @param array $defaults
     * @param array $multiParams
     * @param bool $sendMode
     * @return array
     * @throws \Throwable
     */
    public static function setWatchdogParams(array $defaults, array $multiParams, bool $sendMode) : array
    {
        try {
            $params = [];
            self::setDefaults($params, $defaults, $sendMode);
            self::appendVar($params, $multiParams);
            return $params;
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        //@codeCoverageIgnoreEnd
    }
    
    /**
     * @param array $params
     * @param array $defaults
     * @param bool $sendMode
     * @return void
     * @throws \Throwable
     */
    public static function setDefaults(array &$params, array $defaults, bool $sendMode) : void
    {
        try {
            $bases = [];
            foreach ($defaults as $var => $value) {
                if ($value === 'sendMode') {
                    $bases[$var] = $sendMode;
                } else {
                    $bases[$var] = $value;
                }
            }
            $params[] = $bases;
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        //@codeCoverageIgnoreEnd
    }
    
    /**
     * @param array $params
     * @param array $multiParams
     * @return bool
     * @throws \Throwable
     */
    public static function appendVar(array &$params, array $multiParams) : bool
    {
        try {
            $appended = false;
            $max = count($multiParams);
            if ($max > 1) {
                for ($i = 1; $i < $max; $i++) {
                    if ($multiParams[$i]['var'] === $multiParams[$i - 1]['var']) {
                        $var = $multiParams[$i - 1]['var'];
                    } else {
                        $var = null;
                        break;
                    }
                } 
            } elseif ($max === 1) {
                if (array_key_exists('var', $multiParams[0])) {
                    $var = $multiParams[0]['var'];
                }
            }
            if (isset($var)) {
                $params[0]['variabile'] = $var;
                $appended = true;
            }
            return $appended;
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        //@codeCoverageIgnoreEnd
    }
    
    /**
     * @param array $params
     * @param array $multiParams
     * @return void
     * @throws \Throwable
     */
    public static function setDelay(array &$params, array $multiParams) : void
    {
        try {
            $last = count($multiParams) - 1;
            if ($last > 0) {
                $datefrom = $multiParams[0]['datefrom'];
                $dateto = $multiParams[$last]['dateto']; 
            } elseif ($last === 0) {
                $datefrom = $multiParams[0]['datefrom'];
                $dateto = $multiParams[0]['dateto'];
            }
            $delay = self::getDelay($datefrom, $dateto, 24);
            $params[0]['delay'] = $delay;
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        //@codeCoverageIgnoreEnd        
    }
    
    /**
     * @param string $datefrom
     * @param string $dateto
     * @param int|null $deltaH
     * @return int
     * @throws \Throwable
     */
    public static function getDelay(string $datefrom, string $dateto, ?int $deltaH = null) : int
    {
        try {
            $offset = $deltaH ?? 0;
            $timeZone = new \DateTimeZone('Europe/Rome');
            $dateTimeFrom = \DateTime::createFromFormat('d/m/Y H:i:s', $datefrom . '00:00:00', $timeZone);
            $dateTimeTo = \DateTime::createFromFormat('d/m/Y H:i:s', $dateto . '00:00:00', $timeZone);
            $interval = $dateTimeFrom->diff($dateTimeTo);
            $days = $interval->format('%a');
            $delay = intval($days) * 24 + $offset;
            return $delay;
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        //@codeCoverageIgnoreEnd
    }
}
