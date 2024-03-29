<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi;

use vaniacarta74\Scarichi\Error;

require __DIR__ . '/../vendor/autoload.php';

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
            if (self::checkPath($path)) {            
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
            } else {
                throw new \Exception('Problemi con il file json. File inesistente');
            }
            return $response;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, Error::debugLevel());
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
            Error::printErrorInfo(__FUNCTION__, Error::debugLevel());
            throw $e;
        }
    }
    
    /**
     * @return string
     * @throws \Throwable
     */
    public static function getMicroTime() : string
    {
        try {
            $now = new \DateTime('NOW', new \DateTimeZone('Europe/Rome'));
            $microTime = $now->format('Y-m-d H:i:s.u');
            return $microTime;
        // @codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, Error::debugLevel());
            throw $e;
        }
        // @codeCoverageIgnoreEnd
    }
    
    /**
     * @return string
     * @throws \Throwable
     */
    public static function getLatinTime() : string
    {
        try {
            $now = new \DateTime('NOW', new \DateTimeZone('Europe/Rome'));
            $latinTime = $now->format('d/m/Y H:i:s');
            return $latinTime;
        // @codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, Error::debugLevel());
            throw $e;
        }
        // @codeCoverageIgnoreEnd
    }
    
    /**
     * @param string $microTime
     * @return string
     * @throws \Throwable
     */
    public static function microToLatinTime(string $microTime) : string
    {
        try {
            if (!preg_match('/^[0-9]{4}[-][0-9]{2}[-][0-9]{2}[\s][0-9]{2}[:][0-9]{2}[:][0-9]{2}[\.][0-9]{6}$/', $microTime)) {
                throw new \Exception('Formato microtime errato. Utilizzare: Y-m-d H:i:s.u');
            }
            $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s.u', $microTime, new \DateTimeZone('Europe/Rome'));
            $latinTime = $dateTime->format('d/m/Y H:i:s');
            return $latinTime;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, Error::debugLevel());
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
            Error::printErrorInfo(__FUNCTION__, Error::debugLevel());
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
            Error::printErrorInfo(__FUNCTION__, Error::debugLevel());
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
    public static function checkDate(string $date, ?bool $isSmall = null) : bool
    {
        try {
            $isSmallDate = $isSmall ?? false;
            if (preg_match('/^[0-9]{2}[\/][0-9]{2}[\/][0-9]{4}$/', $date)) {
                if (preg_match('/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/', $date)) {
                    if ($isSmallDate) {
                        $max = \DateTime::createFromFormat('d/m/Y', '06/06/2079');
                        $current = \DateTime::createFromFormat('d/m/Y', $date);
                        $isDate = ($current < $max) ? true : false;
                    } else {
                        $isDate = true;
                    }
                } else {
                    $isDate = false;
                }
            } else {
                throw new \Exception('Formato data non analizzabile. Utilizzare dd/mm/yyyy');
            }
            return $isDate;
        } catch (\Throwable $e) {        
            Error::printErrorInfo(__FUNCTION__, Error::debugLevel());
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
    public static function checkPath(string $path, ?string $mode = null) : bool
    {
        try {
            if (preg_match('/^([\/][\w._-]+)*[\/][\w._-]+[\.](php|json|csv)$/', $path) && file_exists($path)) {
                $isPath = true;
                if (isset($mode)) {
                    switch ($mode) {
                        case 'r':
                            if (!is_readable($path)) {
                                // @codeCoverageIgnoreStart
                                $isPath = false;
                                // @codeCoverageIgnoreEnd
                            }
                            break;
                        case 'w':
                            if (!is_writable($path)) {
                                // @codeCoverageIgnoreStart
                                $isPath = false;
                                // @codeCoverageIgnoreEnd
                            }
                            break;
                        case 'rw':
                            if (!is_readable($path) || !is_writable($path)) {
                                // @codeCoverageIgnoreStart
                                $isPath = false;
                                // @codeCoverageIgnoreEnd
                            }
                            break;
                        default :
                            throw new \Exception('Parametro no definito: usare r o w.');
                            break;
                    }
                }
            } else {
                $isPath = false;
            }
            return $isPath;
        } catch (\Throwable $e) {        
            Error::printErrorInfo(__FUNCTION__, Error::debugLevel());
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
    public static function checkUrl(string $url) : bool
    {
        try {
            $regex = '/^(http[s]?:)[\/]{2}(' . LOCALHOST . '|([0-9][.]|[1-9][0-9][.]|[1][0-9][0-9][.]|[2][0-4][0-9][.]|[2][5][0-5][.]){3}([0-9]|[1-9][0-9]|[1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]){1})[\/]{1}([\w._-]*[\/])*([\w._-]+[.](json|html|php(\?{1}[\w._-]+[=]{1}[\w\/:.-]+(&{1}[\w._-]+[=]{1}[\w\/:.-]+)*)?))*$/';
            if (preg_match($regex, $url)) {
                $isUrl = true;
            } else {
                $isUrl = false;
            }
            return $isUrl;
        // @codeCoverageIgnoreStart
        } catch (\Throwable $e) {        
            Error::printErrorInfo(__FUNCTION__, Error::debugLevel());
            throw $e;        
        }
        // @codeCoverageIgnoreEnd
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
    public static function catchParam(?array $array = null, ?int $key = null, ?array $arrAssoc = null, ?string $keyAssoc = null, ?string $default) : string
    {
        try {
            if (isset($default)) {
                $toCheck = $default;
            } else {
                throw new \Exception('Parametro default necessario');
            }
            $key = $key ?? 0;
            $keyAssoc = $keyAssoc ?? '';            
            $arrAssoc = $arrAssoc ?? [];
            if (isset($array) && count($array) > 1 && $key < count($array)) {
                $toCheck = $array[$key];
            } elseif (count($arrAssoc) > 0 && array_key_exists($keyAssoc, $arrAssoc)) {
                $toCheck = $arrAssoc[$keyAssoc];
            }
            return $toCheck;
        } catch (\Throwable $e) {        
            Error::printErrorInfo(__FUNCTION__, Error::debugLevel());
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
    public static function checkParam(string $toCheck, string $checkMethod, ?array $methodParams = null) : string
    {
        try { 
            $methodParams = $methodParams ?? [];
            $checkParams = array_merge(array($toCheck), $methodParams);
            if (self::callback($checkMethod, $checkParams)) {
                $param = $toCheck;
            } else {
                throw new \Exception('Parametro non definito correttamente');
            }
            return $param;
        } catch (\Throwable $e) {        
            Error::printErrorInfo(__FUNCTION__, Error::debugLevel());
            throw $e;        
        }
    }
    
    public static function countTags(string $html, ?string $tag = null) : int
    {
        try {
            $tagName = $tag ?? 'INNER';                
            if (self::checkHtml($html)) {
                $dom = new \DOMDocument;
                $dom->loadHTML($html); 
                if ($tagName === 'INNER') {                
                    $htmlElements = $dom->getElementsByTagName('html');
                    $bodyElements = $dom->getElementsByTagName('body');
                    $paragraphElements = $dom->getElementsByTagName('p');
                    $allElements = $dom->getElementsByTagName('*');
                    $n_elements = $allElements->length - $htmlElements->length - $bodyElements->length - $paragraphElements->length;                
                } else {
                    $allElements = $dom->getElementsByTagName($tagName);            
                    $n_elements = $allElements->length;
                }
            } else {
                $n_elements = 0;
            }
            return $n_elements;
        // @codeCoverageIgnoreStart
        } catch (\Throwable $e) {        
            Error::printErrorInfo(__FUNCTION__, Error::debugLevel());
            throw $e;        
        }
        // @codeCoverageIgnoreEnd
    }
    
    public static function checkHtml(string $html) : bool
    {
        try {
            $dom = new \DOMDocument;
            try {
                $dom->loadHTML($html);
                return true;
            } catch (\Throwable $e) {
                return false;
            }
        // @codeCoverageIgnoreStart
        } catch (\Throwable $e) {        
            Error::printErrorInfo(__FUNCTION__, Error::debugLevel());
            throw $e;        
        }
        // @codeCoverageIgnoreEnd
    }
    
    public static function purgeHtml(string $html, array $admittedTag) : string
    {
        try {            
            $tags = self::getTags($html);
//            foreach ($tags as $key => $value) {
//                if (!in_array($key, $admittedTag) || $value !== 0) {
//                    if ($key !== 'a') {
//                        $html = str_replace('</' . $key . '>', '', str_replace('<' . $key . '>', '', $html));
//                    }
//                }
//            }
            
//            echo '<pre>';
//            var_dump($tags);
//            echo '</pre>';
            
            foreach ($tags as $key => $value) {
                if (!in_array($key, $admittedTag) || $value !== 0) {
                    if ($key !== 'a') {
                        $html = str_replace('</' . $key . '>', '', str_replace('<' . $key . '>', '', $html));
                    } else {
                        $html = preg_replace('/<\/?a[^>]*>/', '', str_replace('</a>', '', $html));
                    }
                    //echo PHP_EOL . $html . PHP_EOL;
                }
            }
            
            if (!self::checkHtml($html)) {
                $html = strip_tags($html);
            }
            
//            if (array_key_exists('a', $tags) && $tags['a'] !== 0) {
//                $html = preg_replace('/<\/?a[^>]*>/', '', str_replace('</a>', '', $html));
//            }
            
//            if (!self::checkHtml($html)) {
//                $html = htmlspecialchars(strip_tags($html));
//            }
            
            return $html;
        // @codeCoverageIgnoreStart
        } catch (\Throwable $e) {        
            Error::printErrorInfo(__FUNCTION__, Error::debugLevel());
            throw $e;        
        }
        // @codeCoverageIgnoreEnd
    }
    
    public static function checkTags(string $html) : bool
    {
        try {
            if ($html !== '') {
                $groupped = self::getTags($html);
                $isOk = self::areAllZero($groupped);
            } else {
                $isOk = true;
            }
            return $isOk;
        // @codeCoverageIgnoreStart
        } catch (\Throwable $e) {        
            Error::printErrorInfo(__FUNCTION__, Error::debugLevel());
            throw $e;        
        }
        // @codeCoverageIgnoreEnd
    }
    
    public static function getTags(string $html) : array
    {
        try {
            $groupped = [];
            if ($html !== '') {
                preg_match_all('/<([\/]?[a-z0-9]*)/i', $html, $match); 
                foreach ($match[1] as $tag) {
                    $key = str_replace('/', '', $tag);
                    if (!array_key_exists($key, $groupped) && ($key == $tag)) {
                        $groupped[$key] = 1;
                    } elseif (!array_key_exists($key, $groupped) && ($key != $tag)) {
                        $groupped[$key] = -999999;
                    } elseif (array_key_exists($key, $groupped) && ($key == $tag)) {
                        $groupped[$key]++;
                    } elseif (array_key_exists($key, $groupped) && ($key != $tag)) {
                        $groupped[$key]--;
                    }
                }
            }
            return $groupped;
        // @codeCoverageIgnoreStart
        } catch (\Throwable $e) {        
            Error::printErrorInfo(__FUNCTION__, Error::debugLevel());
            throw $e;        
        }
        // @codeCoverageIgnoreEnd
    }
    
    public static function areAllZero(array $groupped) : bool
    {
        try {            
            $isOk = true;
            foreach ($groupped as $count) {
                if ($count !== 0) {
                    $isOk = false;
                    break;
                }
            }
            return $isOk;
        // @codeCoverageIgnoreStart
        } catch (\Throwable $e) {        
            Error::printErrorInfo(__FUNCTION__, Error::debugLevel());
            throw $e;        
        }
        // @codeCoverageIgnoreEnd
    }   
}
