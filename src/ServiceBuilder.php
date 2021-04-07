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
    public static function run(array $serParams, array $multiParams, bool $sendMode) : string
    {
        try {
            $params = self::setParams($serParams, $multiParams, $sendMode);
            $service = New ServiceManager($serParams['name'], $serParams['token'], $params);
            $message = $service->getMessage();            
            return $message;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }        
    }
    
    public static function setParams(array $serParams, array $multiParams, bool $sendMode) : array
    {
        try {
            if (array_key_exists('method', $serParams)) {
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
    
    public static function setSyncParams(array $defaults, array $multiParams, bool $sendMode) : array
    {
        try {
            $params = [];
            self::setDefaults($params, $defaults, $sendMode);
            if (self::appendVar($params, $multiParams)) {
                self::setDelay($params, $multiParams);
            }
            return $params;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }        
    }
    
    public static function setToCsvParams(array $defaults, array $multiParams, bool $sendMode) : array
    {
        try {
            return $multiParams;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }        
    }
    
    public static function setWatchdogParams(array $defaults, array $multiParams, bool $sendMode) : array
    {
        try {
            $params = [];
            self::setDefaults($params, $defaults, $sendMode);
            self::appendVar($params, $multiParams);
            return $params;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }        
    }
    
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
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }        
    }
    
    public static function appendVar(array &$params, array $multiParams) : bool
    {
        try {
            $appended = false;
            $max = count($multiParams);
            if ($max > 1) {
                for ($i = 1; $i <= $max; $i++) {
                    if ($multiParams[$i]['var'] === $multiParams[$i - 1]['var']) {
                        $var = $multiParams[$i - 1]['var'];
                    } else {
                        $var = null;
                        break;
                    }
                } 
            } elseif ($max === 1) {
                $var = $multiParams[0]['var'];
            }
            if (isset($var)) {
                $params[0]['variabile'] = $var;
                $appended = true;
            }
            return $appended;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }        
    }
    
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
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }        
    }
    
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
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }        
    }
}
