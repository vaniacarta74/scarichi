<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi;

use vaniacarta74\Scarichi\Error;

/**
 * Classe gestione errori.
 *
 * Nella classe Error sono contenuti i metodi per la gestione degli errori
 * utilizzati nelle altre classi delle API del progetto Scarichi.
 *
 * @author Vania Carta
 */
class Utility
{
    
    /**
     * Stampa il nome della funzione che ha generato l'errore.
     *
     * Il metodo printErrorInfo() viene chiamato nel caso una funzione abbia
     * generato un errore. Restituisce una stringa con il nome della funzione e
     * la data e l'ora.
     *
     * @param string $functionName Nome della funzione dove si è scatenato l'errore
     * @param int $debug_level Livello di debug
     * @return void
     */
    public static function getJsonArray(string $path, ?array $keys = null, ?string $deepKey = null) : array
    {
        try {
            $response = [];
            $string = @file_get_contents($path);
            $json = json_decode($string, true);

            if ($keys !== null) {
                $jsonArray = self::getSubArray($json, $keys);
            } else {
                $jsonArray = $json;
            }
            if ($deepKey !== null) {
                if (is_array($jsonArray) && array_key_exists($deepKey, $jsonArray)) {
                    if (is_array($jsonArray[$deepKey])) {
                        throw new \Exception('Problemi con il file json. Parametro deepKey usato impropriamente');
                    } else {
                        $response[] = $jsonArray[$deepKey];
                    }
                } else {
                    throw new \Exception('Problemi con il file json. Rivedere i parametri');
                }
            } else {
                $response = $jsonArray;
            }
            return $response;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * Stampa il nome della funzione che ha generato l'errore.
     *
     * Il metodo printErrorInfo() viene chiamato nel caso una funzione abbia
     * generato un errore. Restituisce una stringa con il nome della funzione e
     * la data e l'ora.
     *
     * @param string $functionName Nome della funzione dove si è scatenato l'errore
     * @param int $debug_level Livello di debug
     * @return void
     */
    public static function getSubArray(array $master, array $keys) //: array
    {
        try {
            $key = $keys[0];
            $subKeys = array_slice($keys, 1);
            if (array_key_exists($key, $master)) {
                if (count($subKeys) > 0) {
                    $subArray = self::getSubArray($master[$key], $subKeys);
                } else {
                    $subArray = $master[$key];
                }
            } else {
                throw new \Exception('Problemi con l\'array. Chiave inesistente');
            }
            return $subArray;
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
    public static function benchmark(string $strDateTime) : string
    {
        try {
            $start = \dateTime::createFromFormat('Y-m-d H:i:s.u', $strDateTime, new \DateTimeZone('Europe/Rome'));
            if (!$start) {
                throw new \Exception('Data inizio benchmark inesistente');
            }
            $end = new \DateTime('NOW', new \DateTimeZone('Europe/Rome'));
            $dateInterval = date_diff($start, $end);            
            if ($dateInterval->h === 0) {
                if ($dateInterval->i === 0) {
                    $interval = substr($dateInterval->format('%s,%F'), 0, -3) . ' sec';
                } else {
                    $interval = $dateInterval->format('%i min e %s sec');
                }
            } else {
                $interval = $dateInterval->format('%h ora, %i min e %s sec');
            }
            return $interval;
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
    public static function callback(string $functionName, array $params)
    {
        try {
            $function = __NAMESPACE__ . '\\' . $functionName;
            if (is_callable($function)) {
                $result = call_user_func_array($function, $params);
            } else {
                throw new \Exception('Funzione inesistente');
            }        
            return $result;
        } catch (\Throwable $e) {        
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;        
        }
    }
}
