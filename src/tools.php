<?php
/**
 * Progetto scarichi funzioni di utilità.
 *
 * In questo file sono contenute tutte le funzioni utilizzate per il calcolo del volume
 * scaricato da un invaso attraverso un particolare organo di scarico.
 *
 * @author Vania Carta
 */
namespace vaniacarta74\Scarichi;

use vaniacarta74\Scarichi\Bot;
use vaniacarta74\Scarichi\Curl;
use vaniacarta74\Scarichi\Error;
use vaniacarta74\Scarichi\Utility;

require_once('php_Router.inc.php');


function checkRequest(?array $request, bool $fullCheck) : array
{
    try {
        $variables['var'] = checkVariable($request);
        $dates = checkInterval($request);
        if ($fullCheck) {
            $filters['full'] = checkFilter($request);
            $fields['field'] = checkField($request);
            $checked = array_merge($variables, $dates, $filters, $fields);
        } else {
            $checked = array_merge($variables, $dates);
        }
        return $checked;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function checkVariable(?array $request) : string
{
    try {
        if (isset($request['var']) || isset($request['variabile']) || isset($request['variable'])) {
            $paramNames = ['var', 'variabile', 'variable'];
            foreach ($paramNames as $key) {
                if (array_key_exists($key, $request)) {
                    $variabile = htmlspecialchars(strip_tags($request[$key]));
                    break;
                }
            }
            
            $n_variabile = intval($variabile);
            if ($n_variabile >= 30000 && $n_variabile <= 39999) {
                return $n_variabile;
            } else {
                throw new \Exception('Variabile non analizzabile. Valori ammessi compresi fra 30000 e 39999');
            }
        } else {
            throw new \Exception("Parametro variabile non presente nell'url o nome parametro non valido. Usare var, variable o variabile");
        }
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}

function checkField(?array $request) : string
{
    try {
        if (isset($request['field'])) {
            $options = CONFIG['parameters']['field']['options'];
            $fieldsNames = $options['alias'];
            $shortNames = $options['costants'];
            
            $urlField = strtolower(htmlspecialchars(strip_tags($request['field'])));
            $urlShort = strtoupper($urlField);
            
            if (in_array($urlField, $fieldsNames)) {
                $field = $urlField;
            } elseif (in_array($urlShort, $shortNames)) {
                $field = $fieldsNames[array_search($urlShort, $shortNames)];
            } else {
                throw new \Exception('Nome campo non supportato. Valori ammessi: ' . implode(', ', $fieldsNames) . ' o ' . implode(', ', $shortNames));
            }
        } else {
            $field = 'volume';
        }
        return $field;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}

function checkFilter(?array $request) : bool
{
    try {
        if (isset($request['full'])) {
            $full = htmlspecialchars(strip_tags($request['full']));
            
            if ($full === '0') {
                $filtered = true;
            } elseif ($full === '1') {
                $filtered = false;
            } else {
                throw new \Exception('Valore parametro "full" non ammesso. Scegliere fra 0 e 1');
            }
        } else {
            $filtered = false;
        }
        $notFiltered = !$filtered;
        
        return $notFiltered;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function formatDate(string $date) : string
{
    try {
        $cleanDate = htmlspecialchars(strip_tags($date));

        $dateParts = explode('/', $cleanDate);
        
        if (count($dateParts) !== 3) {
            throw new \Exception('Parametro data inserito nel formato errato. Formato richiesto "gg/mm/yyyy"');
        }

        $day = intval($dateParts[0]);
        $month = intval($dateParts[1]);
        $year = intval($dateParts[2]);

        if (checkdate($month, $day, $year)) {
            $formatDate = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
            
            return $formatDate;
        } else {
            throw new \Exception('Data inserita inesistente');
        }
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function formatDateTime(string $dateTime) : string
{
    try {
        $dateTimeParts = explode(' ', $dateTime);
        if (count($dateTimeParts) !== 2) {
            throw new \Exception('Parametro data e ora inserito nel formato errato. Formato richiesto "gg/mm/yyyy hh:ii:ss"');
        }
        
        $dateString = formatDate($dateTimeParts[0]);
        
        $timeParts = explode(':', $dateTimeParts[1]);
        if (count($timeParts) !== 3) {
            throw new \Exception('Parametro data e ora inserito nel formato errato. Formato ora richiesto "hh:ii:ss"');
        }

        $hours = intval($timeParts[0]);
        $minutes = intval($timeParts[1]);
        $seconds = intval($timeParts[2]);
        
        if ($hours < 0 || $hours > 24 || $minutes < 0 || $minutes > 60 || $seconds < 0 || $seconds > 60) {
            throw new \Exception('Ora inesistente');
        }
        
        $timeString = date('H:i:s', mktime($hours, $minutes, $seconds));
        
        $dateTimeString = $dateString . ' ' . $timeString;
        
        $formatDateTime = date('Y-m-d H:i:s', strtotime($dateTimeString));
        
        return $formatDateTime;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function checkInterval(?array $request) : array
{
    try {
        if (isset($request['datefrom']) && isset($request['dateto'])) {
            $dateFrom = formatDate($request['datefrom']);
            $dateTo = formatDate($request['dateto']);
        } elseif (isset($request['datefrom'])) {
            $dateFrom = formatDate($request['datefrom']);
            
            $defaultTo = CONFIG['parameters']['dateto']['default'];
            $dateToTime = new \DateTime($defaultTo);
            
            $dateTo = $dateToTime->format('Y-m-d');
        } elseif (isset($request['dateto'])) {
            $dateTo = formatDate($request['dateto']);
            
            $defaultFrom = CONFIG['parameters']['datefrom']['default'];
            $interval = new \DateInterval('P' . $defaultFrom);
            $dateToTime = new \DateTime($dateTo);
            $dateFromTime = $dateToTime->sub($interval);
            
            $dateFrom = $dateFromTime->format('Y-m-d');
        } else {
            throw new \Exception('Parametri intervallo date assenti. Indicare uno o entrambi i parametri "datefrom" e "dateto"');
        }
        $dateTimeFrom = new \DateTime($dateFrom);
        $dateTimeTo = new \DateTime($dateTo);

        $dates = ['datefrom' => $dateTimeFrom, 'dateto' => $dateTimeTo];

        return $dates;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function setDateTimes(array $request) : array
{
    try {
        if (count($request) !== 2 && !array_key_exists('datefrom', $request) && !array_key_exists('datefrom', $request)) {
            throw new \Exception('Array non valido. Richieste due chiavi: "datefrom" e "dateto"');
        }
        $dateFrom = formatDateTime($request['datefrom']);
        $dateTo = formatDateTime($request['dateto']);
        
        $dateTimeFrom = new \DateTime($dateFrom);
        $dateTimeTo = new \DateTime($dateTo);

        $dates = ['datefrom' => $dateTimeFrom, 'dateto' => $dateTimeTo];

        return $dates;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function connect(string $dbName) //: resource
{
    $n = 5;
    $delay = 500000;
    $serverName = MSSQL_HOST;
    $connectionInfo = ['Database' => $dbName, 'UID' => MSSQL_USER, 'PWD' => MSSQL_PASSWORD];
    
    try {
        for ($i=1; $i <= $n; $i++) {
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if ($conn) {
                break;
            } else {
                usleep($delay);
            }
        }
        if ($conn) {
            return $conn;
        } else {
            throw new \Exception(error());
        }
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function query($conn, string $fileName, array $paramValues)
{
    try {
        include __DIR__ . '/inc/query/' . $fileName . '.php';

        $query = str_replace($paramNames, $paramValues, $queryString);

        $stmt = sqlsrv_query($conn, $query);
        
        if ($stmt !== false) {
            return $stmt;
        } else {
            throw new \Exception(error());
        }
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function fetch($stmt) : ?array
{
    try {
        $record = 0;
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            foreach ($row as $key => $value) {
                $dati[$record][$key] = $value;
            }
            $record++;
        }
        if (!isset($dati)) {
            $dati = [];
        }
        return $dati;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function close($conn) : void
{
    try {
        if (!sqlsrv_close($conn)) {
            throw new \Exception(error());
        }
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function error() : string
{
    $errors = sqlsrv_errors();
    $text = '<pre>' . print_r($errors, true) . '</pre>';
    
    return $text;
}


function addMedia(array $dati, string $nomeCampo) : array
{
    try {
        $medie = [];
        foreach ($dati as $record => $campi) {
            if (!array_key_exists($nomeCampo, $campi)) {
                throw new \Exception('Campo inesistente. Impossibile eseguirne la media');
            }
            foreach ($campi as $campo => $valore) {
                $medie[$record][$campo] = $valore;

                if ($campo === $nomeCampo) {
                    if ($record === 0) {
                        $media = $valore;
                    } else {
                        $media = ($valore + $medie[$record - 1][$campo])/2;
                    }
                    $medie[$record]['media ' . $nomeCampo] = $media;
                }
            }
        }
        return $medie;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function addDelta(array $dati, string $nomeCampo) : array
{
    try {
        $delta = [];
        foreach ($dati as $record => $campi) {
            if (!array_key_exists($nomeCampo, $campi)) {
                throw new \Exception('Campo inesistente. Impossibile calcolare il delta temporale');
            }
            foreach ($campi as $campo => $valore) {
                $delta[$record][$campo] = $valore;
                if ($campo === $nomeCampo) {
                    if (!is_a($valore, 'DateTime')) {
                        throw new \Exception('Per calcolare il delta temporale è neccessario che gli elementi del campo scelto siano del tipo \'DateTime\'');
                    }
                    if ($record === 0) {
                        $deltaT = NODATA;
                    } else {
                        $timestamp0 = $delta[$record - 1][$campo]->getTimestamp();
                        $timestamp1 = $valore->getTimestamp();
                        $deltaT = $timestamp1 - $timestamp0;
                    }
                    $delta[$record]['delta'] = $deltaT;
                }
            }
        }
        return $delta;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function inizializza(array $variabili, array $dati, string $base) : array
{
    try {
        $volumi = [];
        foreach ($dati as $record => $campi) {
            if (array_key_exists('id_variabile', $variabili)) {
                $volumi[$record]['variabile'] = $variabili['id_variabile'];
                foreach ($campi as $campo => $valore) {
                    if ($campo === $base) {
                        $volumi[$record][$campo] = $valore;
                    }
                }
                $volumi[$record]['tipo_dato'] = 1;
            } else {
                throw new \Exception('Problemi con le chiavi dell\'array variabili');
            }
        }
        return $volumi;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function addCategoria(array $volumi, array $dati_completi, string $categoria) : array
{
    try {
        $categorie = [];
        if (array_key_exists($categoria, $dati_completi)) {
            $dati = $dati_completi[$categoria];
        } else {
            throw new \Exception('Categoria inesistente');
        }
        if (count($volumi) !== count($dati)) {
            throw new \Exception('Array differenti');
        }
        foreach ($volumi as $record => $campi) {
            foreach ($campi as $campo => $valore) {
                $categorie[$record][$campo] = $valore;
                
                if ($campo === 'data_e_ora') {
                    $dato = $dati[$record][$campo];
                    if ($valore->format('d/m/Y H:i:s') === $dato->format('d/m/Y H:i:s')) {
                        $categorie[$record][$categoria] = convertiUnita($dati[$record], $categoria);
                    } else {
                        throw new \Exception('Date differenti');
                    }
                }
            }
        }
        return $categorie;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function addAltezza(array $dati, array $coefficienti, array $fields) : array
{
    try {
        if (count($coefficienti) === 0) {
            throw new \Exception('Formula scarico non definita');
        }
        $id_formula = count($fields);
        $quota = $coefficienti['quota'];
        $altezze = [];
        foreach ($dati as $record => $campi) {
            $formulaAltezza = 'formulaAltezza' . $id_formula;
            $altezze[$record] = Utility::callback($formulaAltezza, array($campi, $quota, $fields));
        }
        return $altezze;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function formulaAltezza1(array $campi, float $quota, array $fields) : array
{
    try {
        $nomeCampo = $fields[0];
        if (!array_key_exists($nomeCampo, $campi)) {
            throw new \Exception('Parametro ' . $nomeCampo . ' non impostato');
        }
        $record = [];
        foreach ($campi as $campo => $valore) {
            $record[$campo] = $valore;
            if ($campo === $nomeCampo) {
                if ($valore != NODATA) {
                    $record['altezza'] = $valore - $quota;
                } else {
                    $record['altezza'] = NODATA;
                }
            }
        }
        return $record;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function formulaAltezza2(array $campi, float $quota, array $fields) : array
{
    try {
        $nomeCampo = $fields[0];
        $nomeCampoAux = $fields[1];
        if (!array_key_exists($nomeCampo, $campi)) {
            throw new \Exception('Parametro ' . $nomeCampo . ' non impostato');
        }
        if (!array_key_exists($nomeCampoAux, $campi)) {
            throw new \Exception('Parametro ' . $nomeCampoAux . ' non impostato');
        }
        $record = [];
        foreach ($campi as $campo => $valore) {
            $record[$campo] = $valore;
            if ($campo === $nomeCampo) {
                $livelloMonte = $valore;
            }
            if ($campo === $nomeCampoAux) {
                $livelloValle = $valore;
            }
        }
        if ($livelloMonte != NODATA && $livelloValle != NODATA) {
            if ($livelloValle > $quota) {
                $record['altezza'] = $livelloMonte - $livelloValle;
            } else {
                $record['altezza'] = $livelloMonte - $quota;
            }
        } else {
            $record['altezza'] = NODATA;
        }
        return $record;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function addPortata(array $dati, array $coefficienti, array $nomiCampo) : array
{
    try {
        if (count($coefficienti) === 0) {
            throw new \Exception('Formula scarico non definita');
        }
        $portate = [];
        foreach ($dati as $record => $campi) {
            $noData = false;
            $parametri = [];
            foreach ($campi as $campo => $valore) {
                $portate[$record][$campo] = $valore;
                if (in_array($campo, $nomiCampo)) {
                    if ($valore != NODATA) {
                        $parametri[$campo] = $valore;
                    } else {
                        $noData = true;
                    }
                }
            }
            if ($noData) {
                $portate[$record]['portata'] = NODATA;
            } else {
                $portate[$record]['portata'] = calcolaPortata($coefficienti, $parametri, $nomiCampo);
            }
        }
        return $portate;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function addVolume(array $dati, array $coefficienti, array $fields) : array
{
    try {
        $volumi = [];
        foreach ($dati as $record => $campi) {
            if (!array_key_exists('portata', $campi) || !array_key_exists('delta', $campi)) {
                throw new \Exception('Dati di portata e delta t assenti impossibile calcolare volume scaricato');
            }
            foreach ($campi as $campo => $valore) {
                $volumi[$record][$campo] = $valore;
            }
            if ($volumi[$record]['portata'] != NODATA && $volumi[$record]['delta'] != NODATA) {
                $volumi[$record]['volume'] = $volumi[$record]['portata'] * $volumi[$record]['delta'];
            } else {
                $volumi[$record]['volume'] = NODATA;
            }
        }
        return $volumi;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function addCumulato(array $dati, array $fields) : array
{
    try {
        $cumulati = [];
        $cumulato = 0;
        foreach ($dati as $record => $campi) {
            if (!array_key_exists('volume', $campi)) {
                throw new \Exception('Dati di volume assente impossibile calcolare volume cumulato');
            }
            foreach ($campi as $campo => $valore) {
                $cumulati[$record][$campo] = $valore;
            }
            if ($cumulati[$record]['volume'] != NODATA) {
                $cumulato += $cumulati[$record]['volume'];
            }
            $cumulati[$record]['cumulato'] = $cumulato;
        }
        return $cumulati;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function setPath(string $variabile, string $path, bool $makeDir) : string
{
    try {
        if (!is_dir($path)) {
            throw new \Exception('Directory inesistente');
        }
        $pathName = $path;
        if ($makeDir) {
            $mode = 0777;
            $recursive = true;
            $pathName .= '/v' . $variabile;

            if (!file_exists($pathName)) {
                if (!mkdir($pathName, $mode, $recursive)) {
                    //@codeCoverageIgnoreStart
                    throw new \Exception('Impossibile creare directory');
                    //@codeCoverageIgnoreEnd
                }
            }
        }
        return $pathName;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function setFile(string $variabile, array $dates, bool $filtered, string $field, string $path) : string
{
    try {
        if (!is_dir($path)) {
            throw new \Exception('Directory inesistente');
        }
        
        $fieldName = str_replace(' ', '_', $field);
        $dateFrom = $dates['datefrom']->format('YmdHi');
        $dateTo = $dates['dateto']->format('YmdHi');
        
        $fileName = $path . '/' . ucfirst($fieldName) . '_' . $variabile . '_' . $dateFrom . '_' . $dateTo;
        
        if ($filtered) {
            $fileName .= '_no0.csv';
        } else {
            $fileName .= '_full.csv';
        }
        
        return $fileName;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function format(array $dati, string $field) : array
{
    $nomiCampi = [
        'variabile' => 'variabile',
        'valore' => $field,
        'data_e_ora' => 'data_e_ora',
        'tipo_dato' => 'tipo_dato'
    ];
    
    try {
        $formatted = [];
        foreach ($dati as $record => $campi) {
            if (!array_key_exists($field, $campi)) {
                throw new \Exception('Campo inesistente. Impossibile assegnarlo a \'valore\'');
            }
            foreach ($campi as $campo => $valore) {
                if (in_array($campo, $nomiCampi)) {
                    foreach ($nomiCampi as $nuovo => $vecchio) {
                        if ($campo === $vecchio) {
                            if (is_a($valore, 'DateTime')) {
                                $formatted[$record][$nuovo] = $valore->format('d/m/Y H:i:s');
                            } elseif (is_numeric($valore)) {
                                if (is_int($valore)) {
                                    $formatted[$record][$nuovo] = number_format($valore, 0, '', '');
                                } elseif (is_float($valore)) {
                                    $strFloat = number_format($valore, 3, ',', '');
                                    if ($strFloat === '0,000') {
                                        $strFloat = '0';
                                    }
                                    $formatted[$record][$nuovo] = $strFloat;
                                }
                            } else {
                                $formatted[$record][$nuovo] = $valore;
                            }
                            break;
                        }
                    }
                }
            }
        }
        return $formatted;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function changeTimeZone(string $dateIn, bool $isLocalToUTC, bool $format, bool $set)
{
    try {
        if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (?:2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/", $dateIn)) {
            throw new \Exception('Inserire la data nel formato Y-m-d H:i:s');
        }
        
        if ($isLocalToUTC) {
            $zoneIn = 'Europe/Rome';
            $zoneOut = 'Etc/GMT-1';
        } else {
            $zoneIn = 'Etc/GMT-1';
            $zoneOut = 'Europe/Rome';
        }

        $dateTime = new \DateTime($dateIn, new \DateTimeZone($zoneIn));
        if ($set) {
            $dateTime->setTimezone(new \DateTimeZone($zoneOut));
        }

        if ($format) {
            $dateOut = $dateTime->format('d/m/Y H:i:s');
        } else {
            $dateOut = $dateTime;
        }
        return $dateOut;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function datesToString(array $dates, string $format) : array
{
    try {
        $formattedDates = [];
        foreach ($dates as $key => $date) {
            if (is_a($date, 'DateTime')) {
                $formattedDates[$key] = $date->format($format);
                
                if (!\DateTime::createFromFormat($format, $formattedDates[$key])) {
                    throw new \Exception('Formato data scelto non valido. Utilizzare "d/m/Y H:i:s" o "Y-m-d H:i:s"');
                }
            } else {
                $formattedDates[$key] = $date;
            }
        }
        return $formattedDates;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function checkDates(string $db, array $dates, bool $isLocalToUTC) : array
{
    try {
        $checkedDates = [];
        foreach ($dates as $key => $date) {
            if (is_a($date, 'DateTime')) {
                $dateString = $date->format('Y-m-d H:i:s');
                if ($db === 'SPT') {
                    $checkedDates[$key] = changeTimeZone($dateString, $isLocalToUTC, false, true);
                } else {
                    $checkedDates[$key] = changeTimeZone($dateString, true, false, false);
                }
            } else {
                $checkedDates[$key] = $date;
            }
        }
        return $checkedDates;
    // @codeCoverageIgnoreStart
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
    // @codeCoverageIgnoreEnd
}


function setToLocal(string $db, array $dati) : array
{
    try {
        $locals = [];
        foreach ($dati as $record => $campi) {
            $locals[$record] = checkDates($db, $campi, false);
        }
        return $locals;
    // @codeCoverageIgnoreStart
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
    // @codeCoverageIgnoreEnd
}

function checkNull($value)
{
    try {
        if (is_null($value)) {
            throw new \Exception('Valore parametro non valido o nullo');
        } else {
            $res = $value;
        }
        return $res;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}

function changeDate(array $values) : array
{
    try {
        $res = [];
        foreach ($values as $key => $value) {
            if (is_a($value, 'DateTime')) {
                $res[$key] = $value->format('d/m/Y H:i:s');
            } else {
                $res[$key] = $value;
            }
        }
        return $res;
    // @codeCoverageIgnoreStart
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
    // @codeCoverageIgnoreEnd
}

function getDataFromDb(string $db, string $queryFileName, array $parametri) : array
{
    try {
        $data = [];
        
        $conn = connect($db);
        
        $checkedParams = array_map('\vaniacarta74\Scarichi\checkNull', $parametri);
        
        $checkedDateParams = checkDates($db, $checkedParams, true);
        
        $params = datesToString($checkedDateParams, 'd/m/Y H:i:s');
        
        $stmt = query($conn, $queryFileName, $params);
        
        $rawData = fetch($stmt);
        
        $data = setToLocal($db, $rawData);
        
        close($conn);
        
        return $data;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function printToCSV(array $dati, string $fileName) : void
{
    try {
        $mode = 'w';
        $delimiter = ';';
        
        $stream_options = [
            'ftp' => [
                'overwrite' => true
            ]
        ];
        $stream_context = stream_context_create($stream_options);
        
        $handle = @fopen($fileName, $mode, false, $stream_context);
        
        if ($handle) {
            $nomiCampi = array_keys($dati[0]);

            fputcsv($handle, $nomiCampi, $delimiter);

            foreach ($dati as $valori) {
                fputcsv($handle, $valori, $delimiter);
            }

            fclose($handle);
        } else {
            throw new \Exception('Problemi con l\'apertura del file CSV');
        }
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function divideAndPrint(array $data, bool $full, string $field, ?int $limit = null) : int
{
    $limit = $limit ?? MAXRECORD;
    $filtered = !$full;
    
    try {
        if ($limit <= 0) {
            throw new \Exception('Impostare un numero massimo di record da esportare almeno uguale ad 1');
        }
        $printed = 0;
        if (count($data) > 0) {
            $i = 0;            
            $printableData = [];
            foreach ($data as $record) {
                if ($i === $limit) {
                    printPart($printableData, $i, $filtered, $field);
                    $i = 0;
                    $printableData = [];
                    $printed++;
                }
                $printableData[$i] = $record;
                $i++;
            }
            if ($i > 0) {
                printPart($printableData, $i, $filtered, $field);
                $printed++;
            }
        }
        return $printed;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function printPart(array $printableData, int $i, bool $filtered, string $field) : void
{
    try {
        $max = $i - 1;
        if (!array_key_exists($max, $printableData)) {
            throw new \Exception('Indice array in stampa non definito');
        }
        
        $variabile = $printableData[0]['variabile'];
        $dates = [
            'datefrom' => $printableData[0]['data_e_ora'],
            'dateto' => $printableData[$max]['data_e_ora']
        ];
        $dateTimes = setDateTimes($dates);
        $path = setPath($variabile, CSV, MAKESUBDIR);
        $fileName = setFile($variabile, $dateTimes, $filtered, $field, $path);
        printToCSV($printableData, $fileName);
        changeMode(CSV, $fileName, 0777);
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function filter(array $dati, bool $full, int $filterVal) : array
{
    try {
        if ($full) {
            $filteredData = $dati;
        } else {
            $filteredData = [];
            foreach ($dati as $record => $campi) {
                if (!array_key_exists('valore', $campi)) {
                    throw new \Exception('Campo "valore" su cui eseguire il filtro non presente');
                }
                $flag = true;
                foreach ($campi as $campo => $valore) {
                    if ($campo === 'valore' && $valore === strval($filterVal)) {
                        $flag = false;
                    }
                }
                if ($flag) {
                    $filteredData[$record] = $campi;
                }
            }
        }
        return $filteredData;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function response(array $request, int $printed, string $start) : string
{
    try {
        $keys = array_flip(['var', 'datefrom', 'dateto', 'full', 'field']);
        
        if (array_diff_key($request, $keys) || array_diff_key($keys, $request)) {
            throw new \Exception('Parametri mancanti');
        }
        
        $type = $request['full'] ? 'full' : 'senza zeri';
        
        $html = 'Elaborazione dati <b>' . ucfirst($request['field']) . '</b> variabile <b>' . $request['var'] . '</b> dal <b>' . $request['datefrom']->format('d/m/Y') . '</b> al <b>' . $request['dateto']->format('d/m/Y') . '</b> avvenuta con successo in <b>' . Utility::benchmark($start) . '</b>.';
        
        if ($printed > 0) {
            $html .= ' File CSV <b>' . $type . '</b> esportati: <b>' . $printed . '</b>';
        } else {
            $html .= ' Nessun file CSV <b>' . $type . '</b> esportato per mancanza di dati.';
        }
        return $html;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function csvResponse(array $request, int $printed, string $start) : array
{
    try {
        $header = CONFIG['define']['services']['tocsv']['header'];
        $footer = CONFIG['define']['services']['tocsv']['footer'];
        
        $response['ok'] = true; 
        foreach ($request as $key => $value) {
            if (is_a($value, 'DateTime')) {
                $response['response']['params'][$key] = $value->format('d/m/Y H:i:s');
            } else {
                $response['response']['params'][$key] = $value;
            }
        }
        $response['response']['header'][0] = $header['title'];
        $response['response']['header'][1] = $header['start'] . ' ' . Utility::microToLatinTime($start);
        
        $response['response']['body'][0] = response($request, $printed, $start);      
        
        $response['response']['footer'][0] = $footer['stop'] . ' ' . Utility::getLatinTime();
        $response['response']['footer'][1] = $footer['time'] . ' ' . Utility::benchmark($start);
        $response['response']['footer'][2] = $footer['report'] . ' ' . $printed;

        return $response;
    //@codeCoverageIgnoreStart
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
    //@codeCoverageIgnoreEnd
}


function calcolaPortata(array $coefficienti, array $parametri, array $campi) : float
{
    try {
        $alias = $coefficienti['alias'];
        $funzionePortata = 'formulaPortata' . ucfirst($alias);
        
        $portata = Utility::callback($funzionePortata, array($coefficienti, $parametri, $campi));
        
        if ($portata > $coefficienti['limite']) {
            $portata = 0;
            //$portata = NODATA;
        }
        return $portata;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function formulaPortataSfioro(array $coefficienti, array $parametri, array $campi) : float
{
    try {
        $altezza = $campi[0];
        if (!array_key_exists($altezza, $parametri) || $parametri[$altezza] == NODATA) {
            throw new \Exception('Parametro ' . $altezza . ' non impostato o nodata');
        }
        $g = 9.81;
        $altezza_idrostatica = $parametri[$altezza];
        $mi = $coefficienti['mi'];
        $larghezza_soglia = $coefficienti['larghezza'];
        if ($altezza_idrostatica > 0) {
            $portata = $mi * $larghezza_soglia * $altezza_idrostatica * sqrt(2 * $g * $altezza_idrostatica);
        } else {
            $portata = 0;
        }
        return $portata;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function formulaPortataScarico1(array $coefficienti, array $parametri, array $campi) : float
{
    try {
        $altezza = $campi[0];
        $manovra = $campi[1];
        if (!array_key_exists($altezza, $parametri) || $parametri[$altezza] == NODATA) {
            throw new \Exception('Parametro ' . $altezza . ' non impostato o nodata');
        }
        if (!array_key_exists($manovra, $parametri) || $parametri[$manovra] == NODATA) {
            throw new \Exception('Parametro ' . $manovra . ' non impostato o nodata');
        }
        $g = 9.81;
        $altezza_idrostatica = $parametri[$altezza];
        $apertura_paratoia = $parametri[$manovra];
        $mi = $coefficienti['mi'];
        $larghezza_soglia = $coefficienti['larghezza'];
        $velocita = $coefficienti['velocita'];
        $altezza_cinetica = ($velocita ** 2) / (2 * $g);

        if ($altezza_idrostatica > 0) {
            $portata = $mi * $larghezza_soglia * $apertura_paratoia * sqrt(2 * $g) * (sqrt(($altezza_idrostatica + $altezza_cinetica) ** 3) - sqrt($altezza_cinetica ** 3));
        } else {
            $portata = 0;
        }
        return $portata;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function formulaPortataScarico2(array $coefficienti, array $parametri, array $campi) : float
{
    try {
        $altezza = $campi[0];
        $manovra = $campi[1];
        if (!array_key_exists($altezza, $parametri) || $parametri[$altezza] == NODATA) {
            throw new \Exception('Parametro ' . $altezza . ' non impostato o nodata');
        }
        if (!array_key_exists($manovra, $parametri) || $parametri[$manovra] == NODATA) {
            throw new \Exception('Parametro ' . $manovra . ' non impostato o nodata');
        }
        $g = 9.81;
        $altezza_idrostatica = $parametri[$altezza];
        $apertura_paratoia = $parametri[$manovra];
        $mi = $coefficienti['mi'];
        $larghezza_soglia = $coefficienti['larghezza'];

        if ($altezza_idrostatica > 0) {
            $portata = $mi * $larghezza_soglia * $apertura_paratoia * sqrt(2 * $g * $altezza_idrostatica);
        } else {
            $portata = 0;
        }
        return $portata;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function formulaPortataScarico3(array $coefficienti, array $parametri, array $campi) : float
{
    try {
        $altezza = $campi[0];
        $manovra = $campi[1];
        if (!array_key_exists($altezza, $parametri) || $parametri[$altezza] == NODATA) {
            throw new \Exception('Parametro ' . $altezza . ' non impostato o nodata');
        }
        if (!array_key_exists($manovra, $parametri) || $parametri[$manovra] == NODATA) {
            throw new \Exception('Parametro ' . $manovra . ' non impostato o nodata');
        }
        $g = 9.81;
        $altezza_idrostatica = $parametri[$altezza];
        $apertura_paratoia = $parametri[$manovra];
        $mi = $coefficienti['mi'];
        $raggio = $coefficienti['raggio'];
        $area_sezione = pi() * $raggio ** 2;

        if ($altezza_idrostatica > 0) {
            $portata = $mi * $area_sezione * $apertura_paratoia * sqrt(2 * $g * $altezza_idrostatica);
        } else {
            $portata = 0;
        }
        return $portata;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function formulaPortataScarico4(array $coefficienti, array $parametri, array $campi) : float
{
    try {
        $altezza = $campi[0];
        $manovra = $campi[1];
        if (!array_key_exists($altezza, $parametri) || $parametri[$altezza] == NODATA) {
            throw new \Exception('Parametro ' . $altezza . ' non impostato o nodata');
        }
        if (!array_key_exists($manovra, $parametri) || $parametri[$manovra] == NODATA) {
            throw new \Exception('Parametro ' . $manovra . ' non impostato o nodata');
        }
        $g = 9.81;
        $altezza_idrostatica = $parametri[$altezza];
        $apertura_paratoia = $parametri[$manovra];
        $mi = $coefficienti['mi'];
        $larghezza_soglia = $coefficienti['larghezza'];
        $tirante = $altezza_idrostatica - ($apertura_paratoia / 2);

        if ($tirante > 0) {
            $portata = $mi * $larghezza_soglia * $apertura_paratoia * sqrt(2 * $g * $tirante);
        } else {
            $portata = 0;
        }
        return $portata;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function formulaPortataVentola(array $coefficienti, array $parametri, array $campi) : float
{
    try {
        $altezza = $campi[0];
        $manovra = $campi[1];
        if (!array_key_exists($altezza, $parametri) || $parametri[$altezza] == NODATA) {
            throw new \Exception('Parametro ' . $altezza . ' non impostato o nodata');
        }
        if (!array_key_exists($manovra, $parametri) || $parametri[$manovra] == NODATA) {
            throw new \Exception('Parametro ' . $manovra . ' non impostato o nodata');
        }
        $g = 9.81;
        $altezza_idrostatica = $parametri[$altezza];
        $rad_ventola = $parametri[$manovra];
        $mi = $coefficienti['mi'];
        $larghezza = $coefficienti['larghezza'];
        $angolo_max = $coefficienti['angolo'];
        $rad_max = $angolo_max / 180 * pi();
        $altezza_max = $coefficienti['altezza'];
        $profondita_ventola = $altezza_max / sin($rad_max);
        $apertura_ventola = $altezza_max - $profondita_ventola * sin($rad_max - $rad_ventola);
        $tirante = $altezza_idrostatica + $apertura_ventola;

        if ($tirante > 0) {
            $portata = $mi * $larghezza * $tirante * sqrt(2 * $g * $tirante);
        } else {
            $portata = 0;
        }
        return $portata;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function formulaPortataSaracinesca(array $coefficienti, array $parametri, array $campi) : float
{
    try {
        $altezza = $campi[0];
        $manovra = $campi[1];
        if (!array_key_exists($altezza, $parametri) || $parametri[$altezza] == NODATA) {
            throw new \Exception('Parametro ' . $altezza . ' non impostato o nodata');
        }
        if (!array_key_exists($manovra, $parametri) || $parametri[$manovra] == NODATA) {
            throw new \Exception('Parametro ' . $manovra . ' non impostato o nodata');
        }
        $g = 9.81;
        $altezza_idrostatica = $parametri[$altezza];
        $altezza_saracinesca = $parametri[$manovra];
        $mi = $coefficienti['mi'];
        $raggio = $coefficienti['raggio'];
        $k = ($raggio - $altezza_saracinesca) / $raggio;
        $rad_angolo = 2 * acos($k);
        $area_scarico = ($rad_angolo - sin($rad_angolo)) * ($raggio ** 2) / 2;
        $tirante = $altezza_idrostatica - ($altezza_saracinesca / 2);

        if ($tirante > 0) {
            $portata = $mi * $area_scarico * sqrt(2 * $g * $tirante);
        } else {
            $portata = 0;
        }
        return $portata;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function formulaPortataGalleria(array $coefficienti, array $parametri, array $campi) : float
{
    try {
        $livello = $campi[0];
        $altezza = $campi[1];
        $manovra = $campi[2];
        if (!array_key_exists($livello, $parametri) || $parametri[$livello] == NODATA) {
            throw new \Exception('Parametro ' . $livello . ' non impostato o nodata');
        }
        if (!array_key_exists($altezza, $parametri) || $parametri[$altezza] == NODATA) {
            throw new \Exception('Parametro ' . $altezza . ' non impostato o nodata');
        }
        if (!array_key_exists($manovra, $parametri) || $parametri[$manovra] == NODATA) {
            throw new \Exception('Parametro ' . $manovra . ' non impostato o nodata');
        }
        $g = 9.81;
        $livello_monte = $parametri[$livello];
        $altezza_idrostatica = $parametri[$altezza];
        $altezza_saracinesca = $parametri[$manovra];

        $scabrosita = $coefficienti['scabrosita'];
        $lunghezza_galleria = $coefficienti['lunghezza'];
        $raggio = $coefficienti['raggio'];
        $quota = $coefficienti['quota'];
        $angolo_limite = $coefficienti['angolo'];
        $quota_limite = $coefficienti['limite'];

        $R = $raggio / 2;
        $chi = 87 / (1 + $scabrosita / sqrt($R));
        $J = $altezza_idrostatica / $lunghezza_galleria;
        $area = pi() * $raggio ** 2;

        $portata_base = $chi * $area * sqrt($R * $J);

        $rad_limite = $angolo_limite / 180 * pi();
        $altezza_saracinesca_limite = $raggio * (1 - cos($rad_limite / 2));
        $area_limite = ($rad_limite - sin($rad_limite)) * ($raggio ** 2) / 2;
        $arco_limite = $rad_limite * $raggio;
        $R_limite = $area_limite / $arco_limite;
        $chi_limite = 87 / (1 + $scabrosita / sqrt($R_limite));
        $J_limite = ($quota_limite - $quota) / $lunghezza_galleria;

        $portata_limite = $chi_limite * $area_limite * sqrt($R_limite * $J_limite);

        $energia_limite = $altezza_saracinesca_limite + (($portata_limite ** 2) / (2 * $g * $area_limite ** 2));

        if ($livello_monte > $quota_limite) {
            $portata = $portata_base * $altezza_saracinesca / $energia_limite;
        } else {
            $portata = 0;
        }
        return $portata;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function uniformaCategorie(array $dati_acquisiti) : array
{
    try {
        if (count($dati_acquisiti) === 0) {
            throw new \Exception('Nessuna categoria definita');
        }
        
        $uniformati = [];
        foreach ($dati_acquisiti as $categoria => $dati) {
            $dati_target = [];
            $dati_confronto = [];
            
            $dati_target[$categoria] = $dati;
            $dati_confronto = array_diff_key($dati_acquisiti, $dati_target);

            $uniformati[$categoria] = integraDate($dati, $dati_confronto);
        }
        return $uniformati;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function integraDate(array $targets, array $checkers) : array
{
    try {
        foreach ($checkers as $categoria => $dati) {
            foreach ($dati as $dato) {
                if (count($dato) === 0) {
                    throw new \Exception('Nessun dato presente nel checker');
                }
                $targets = insertNoData($targets, $dato);
                $iMax = count($targets) - 1;
                $prototype = [
                    $iMax => [
                        'variabile' => $targets[$iMax]['variabile'],
                        'valore' => null,
                        'unita_misura' => $targets[$iMax]['unita_misura'],
                        'data_e_ora' => $dato['data_e_ora'],
                        'tipo_dato' => $targets[$iMax]['tipo_dato']
                    ]
                ];
                if ($dato['data_e_ora'] < $targets[0]['data_e_ora']) {
                    array_splice($targets, 0, 0, $prototype);
                } elseif ($dato['data_e_ora'] > $targets[$iMax]['data_e_ora']) {
                    array_splice($targets, count($targets), 0, $prototype);
                } else {
                    for ($i = 1; $i <= $iMax; $i++) {
                        if ($dato['data_e_ora'] > $targets[$i - 1]['data_e_ora'] && $dato['data_e_ora'] < $targets[$i]['data_e_ora']) {
                            array_splice($targets, $i, 0, $prototype);
                            break;
                        }
                    }
                }
            }
        }
        return $targets;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function insertNoData(array $targets, array $dato) : array
{
    try {
        if (count($dato) === 0) {
            throw new \Exception('Nessun dato di riferimento presente nel checker');
        }
        if (count($targets) === 0) {
            $prototype = [
                [
                    'variabile' => NODATA,
                    'valore' => NODATA,
                    'unita_misura' => NODATA,
                    'data_e_ora' => $dato['data_e_ora'],
                    'tipo_dato' => NODATA
                ]
            ];
            $targets = $prototype;
        }
        return $targets;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}

    
function completaDati(array $dati_uniformi) : array
{
    try {
        if (count($dati_uniformi) === 0) {
            throw new \Exception('Nessuna categoria definita');
        }
        $completi = [];
        foreach ($dati_uniformi as $categoria => $dati) {
            if (count($dati) > 0) {
                $completi[$categoria] = riempiCode($dati, $categoria);
                if ($categoria === 'manovra') {
                    $completi[$categoria] = riempiNull($completi[$categoria]);
                } else {
                    $completi[$categoria] = interpolaNull($completi[$categoria]);
                }
            } else {
                $completi[$categoria] = [];
            }
        }
        return $completi;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function riempiCode(array $dati, string $categoria) : array
{
    try {
        if (count($dati) === 0) {
            throw new \Exception('Nessun dato presente per questa categoria');
        }
        $boundaries = [];
        $capi = trovaCapi($dati, $categoria);

        foreach ($dati as $record => $campi) {
            foreach ($campi as $key => $valore) {
                if ($key === 'valore') {
                    if ($record <= $capi['testa']['id']) {
                        $boundaries[$record][$key] = $capi['testa']['valore'];
                    } elseif ($record >= $capi['coda']['id']) {
                        $boundaries[$record][$key] = $capi['coda']['valore'];
                    } else {
                        $boundaries[$record][$key] = $valore;
                    }
                } else {
                    $boundaries[$record][$key] = $valore;
                }
            }
        }
        return $boundaries;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function trovaCapi(array $dati, string $categoria) : array
{
    try {
        if (count($dati) === 0) {
            throw new \Exception('Nessun dato presente impossibile trovare i capi');
        }
        if ($categoria === 'manovra') {
            $testa = trovaTesta($dati);
            $coda = trovaCoda($dati);
            $capi = [
                'testa' => $testa,
                'coda' => $coda
            ];
        } else {
            $capi = trovaEntrambi($dati);
        }
        return $capi;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function trovaEntrambi(array $dati) : array
{
    try {
        if (count($dati) === 0) {
            throw new \Exception('Nessun dato presente impossibile determinarne gli estremi');
        }
        $iMax = count($dati) - 1;
        if (isset($dati[0]['valore']) && isset($dati[$iMax]['valore'])) {
            $testa = [
                'id' => 0,
                'valore' => $dati[0]['valore']
            ];
            $coda = [
                'id' => $iMax,
                'valore' => $dati[$iMax]['valore']
            ];
        } else {
            foreach ($dati as $record => $campi) {
                foreach ($campi as $key => $valore) {
                    if ($key === 'valore' && isset($valore)) {
                        if (!isset($testa)) {
                            $testa = [
                                'id' => $record,
                                'valore' => $valore
                            ];
                        }
                        $coda = [
                            'id' => $record,
                            'valore' => $valore
                        ];
                    }
                }
            }
        }        
        $capi = [
            'testa' => $testa ?? ['id' => 0, 'valore' => NODATA],
            'coda' => $coda ?? ['id' => $iMax, 'valore' => NODATA]
        ];
        return $capi;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function trovaTesta(array $dati) : array
{
    try {
        if (count($dati) === 0) {
            throw new \Exception('Nessun dato presente impossibile determinare la testa');
        }
        $testa['id'] = 0;
        if (isset($dati[0]['valore'])) {
            $testa['valore'] = $dati[0]['valore'];
        } else {
            $dummy['dummy'] = [];
            $parametri = [
                'variabile' => $dati[0]['variabile'],
                'tipo_dato' => $dati[0]['tipo_dato'],
                'data_iniziale' => $dati[0]['data_e_ora']    
            ];
            $lastPrevData = selectLastPrevData('SSCP_data', $parametri, $dummy, 'dummy');
            if (count($lastPrevData['dummy']) > 0) {
                $testa['valore'] = $lastPrevData['dummy'][0]['valore'];                
            } else {
                $testa['valore'] = NODATA;
            }
        }
        return $testa;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function trovaCoda(array $dati) : array
{
    try {
        if (count($dati) === 0) {
            throw new \Exception('Nessun dato presente impossibile determinare la coda');
        }
        $iMax = count($dati) - 1;
        if (isset($dati[$iMax]['valore'])) {
            $coda = [
                'id' => $iMax,
                'valore' => $dati[$iMax]['valore']
            ];
        } else {
            foreach ($dati as $record => $campi) {
                foreach ($campi as $key => $valore) {
                    if ($key === 'valore' && isset($valore)) {                        
                        $coda = [
                            'id' => $record,
                            'valore' => $valore
                        ];
                    }
                }
            }
        }
        return $coda ?? ['id' => $iMax, 'valore' => NODATA];
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function riempiNull(array $dati) : array
{
    try {
        $pieni = [];
        foreach ($dati as $record => $campi) {
            foreach ($campi as $key => $valore) {
                if ($key === 'valore') {
                    if (isset($valore)) {
                        $pieni[$record][$key] = $valore;
                        $prev = $valore;
                    } else {
                        if (isset($prev)) {
                            $pieni[$record][$key] = $prev;
                        } else {
                            throw new \Exception('Nessun valore di riferimento trovato');
                            //$pieni[$record][$key] = $valore;
                        }
                    }
                } else {
                    $pieni[$record][$key] = $valore;
                }
            }
        }
        return $pieni;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function interpolaNull(array $dati) : array
{
    try {
        $interpolati = [];
        $iMax = count($dati) - 1;
        
        foreach ($dati as $record => $campi) {
            foreach ($campi as $key => $valore) {
                if ($key === 'valore') {
                    if (isset($valore)) {
                        $interpolati[$record][$key] = $valore;
                        $r1 = $record;
                    } else {
                        if (isset($r1)) {
                            $r2 = $r1 + 1;
                            while (!isset($dati[$r2][$key]) && $r2 < $iMax) {
                                $r2++;
                            }
                            $x1 = $dati[$r1]['data_e_ora']->getTimestamp();
                            $y1 = $dati[$r1][$key];
                            
                            $x2 = $dati[$r2]['data_e_ora']->getTimestamp();
                            $y2 = $dati[$r2][$key];
                            
                            $x = $dati[$record]['data_e_ora']->getTimestamp();
                            $y = interpola($x1, $x2, $y1, $y2, $x);
                            
                            $interpolati[$record][$key] = $y;
                        } else {
                            throw new \Exception('Nessun valore di riferimento trovato');
                        }
                    }
                } else {
                    $interpolati[$record][$key] = $valore;
                }
            }
        }
        return $interpolati;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function interpola(float $x1, float $x2, float $y1, float $y2, float $x) : float
{
    try {
        if ($x2 === $x1) {
            throw new \Exception('Divisione per zero');
        } else {
            $y = ($x - $x1) / ($x2 - $x1) * ($y2 - $y1) + $y1;
        }
        return $y;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function convertiUnita(array $dati, string $categoria) : float
{
    try {
        if (array_key_exists('unita_misura', $dati) && array_key_exists('valore', $dati)) {
            $unita = $dati['unita_misura'];
            $valore = $dati['valore'];
        } else {
            throw new \Exception('Conversione non riuscita: valore o unita di misura non trovati');
        }
        if ($valore != NODATA) {
            switch ($categoria) {
                case 'manovra':
                    switch ($unita) {
                        case '%':
                            $converted = $valore / 100;
                            break;
                        case 'cm':
                            $converted = $valore / 100;
                            break;
                        case 'gradi':
                            $converted = $valore / 180 * pi();
                            break;
                        default:
                            $converted = $valore;
                            break;
                    }
                    break;
                default:
                    $converted = $valore;
                    break;
            }
        } else {
            $converted = $valore;
        }
        return $converted;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function eraseDoubleDate(array $dati_acquisiti) : array
{
    try {
        if (count($dati_acquisiti) === 0) {
            throw new \Exception('Nessuna categoria definita');
        }
        $erased = [];
        foreach ($dati_acquisiti as $categoria => $dati) {
            if (count($dati) > 0) {
                $erased[$categoria][] = $dati[0];
                $iMax = count($dati) - 1;
                for ($i = 1; $i <= $iMax; $i++) {
                    if ($dati[$i]['data_e_ora'] != $dati[$i - 1]['data_e_ora']) {
                        $erased[$categoria][] = $dati[$i];
                    }
                }
            } else {
                $erased[$categoria] = [];
            }
        }
        return $erased;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function debugOnCSV(array $dati, string $fileName) : string
{
    try {
        $filePath = CSV . '/' . $fileName . '.csv';
        $changedDatas = array_map('\vaniacarta74\Scarichi\changeDate', $dati);
        printToCSV($changedDatas, $filePath);
        return $filePath;
    // @codeCoverageIgnoreStart
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
    // @codeCoverageIgnoreEnd
}


function getMessage(array $composer, array $help, string $type) : string
{
    try {
        if (count($composer) === 0 || count($help) === 0) {
            throw new \Exception('Dati configurazione non presenti');
        }
        $eol = ($type === 'html') ? '<br/>' : PHP_EOL;
        $funzioneMessaggio = 'setMsg' . ucfirst($type);
        $message = Utility::callback($funzioneMessaggio, array($composer, $help, $eol));

        return $message;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function setHeader(array $composer) : string
{
    try {
        if (count($composer) === 0) {
            throw new \Exception('Dati configurazione non presenti');
        }
        $version = $composer['version'];
        $author = $composer['authors'][0]['name'];
        $message = 'scarichi ' . $version .  ' by ' . $author . ' and contributors';
        return $message;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function setMsgHtml(array $composer, array $help, string $eol) : string
{
    try {
        if (count($composer) === 0 || count($help) === 0) {
            throw new \Exception('Dati configurazione non presenti');
        }
        $header = setHeader($composer);
        $command = $help['command']['sintax'];
        $param = propertyToString($help['parameters'], 'help', 'short');
        
        $message = $header . $eol;
        $message .= 'Utilizzare il terminale.' . $eol;
        $message .= 'Sintassi help:' . $eol;
        $message .= '[user@localhost ~]# ' . $command . ' -' . $param . $eol;
                      
        return $message;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function setMsgVersion(array $composer, array $help, string $eol) : string
{
    try {
        if (count($composer) === 0 || count($help) === 0) {
            throw new \Exception('Dati configurazione non presenti');
        }
        $header = setHeader($composer);
        
        $message = $header . $eol;
                      
        return $message;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function setMsgDefault(array $composer, array $help, string $eol) : string
{
    try {
        if (count($composer) === 0 || count($help) === 0) {
            throw new \Exception('Dati configurazione non presenti');
        }
        $header = setHeader($composer);
        $command = $help['command']['sintax'];
        $shorts = getProperties($help['parameters'], 'short', null, 'type', 'group', null, null, '-');
        $defaults = getProperties($help['parameters'], 'default', null, 'type', 'group');
        $mixed = array_combine($shorts, $defaults);
        foreach ($mixed as $short => $default) {
            $couples[] = $short . ' ' . $default;
        }
        $params = implode(' ', $couples);
        
        $message = $header . $eol;
        $message .= $command . ' ' . $params . $eol;
        $message .= substr(START, 0, -7) . $eol;
        
        return $message;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function setMsgHelp(array $composer, array $help, string $eol) : string
{
    try {
        if (count($composer) === 0 || count($help) === 0) {
            throw new \Exception('Dati configurazione non presenti');
        }
        $header = setHeader($composer);
        $description = $composer['description'];
        $command = $help['command']['sintax'];
        $console = setConsole($help, $eol);
        $singles = getProperties($help['parameters'], 'short', null, 'type', 'single', null, null, '-');
        $params = implode('|', $singles);
        $shorts = getProperties($help['parameters'], 'short', null, 'type', 'group', null, null, '-');
        foreach ($shorts as $short) {
            $couples[] = $short . ' [options]';
        }
        $options = implode(' ', $couples);

        $message = $header . $eol;
        $message .= $description . $eol;
        $message .= $eol;
        $message .= 'Usage:' . $eol;
        $message .= '  ' . $command . ' [' . $params . ']' . $eol;
        $message .= '  ' . $command . ' ' . $options . $eol;
        $message .= $eol;
        $message .= $console;
        $message .= $eol;
                      
        return $message;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function setMsgOk(array $composer, array $help, string $eol) : string
{
    try {
        if (count($composer) === 0 || count($help) === 0) {
            throw new \Exception('Dati configurazione non presenti');
        }
        $header = setHeader($composer);
        
        $message = $header . $eol;
        $message .= substr(START, 0, -7) . $eol;
                      
        return $message;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function setMsgError(array $composer, array $help, string $eol) : string
{
    try {
        if (count($composer) === 0 || count($help) === 0) {
            throw new \Exception('Dati configurazione non presenti');
        }
        $header = setHeader($composer);
        $command = $help['command']['sintax'];
        $param = propertyToString($help['parameters'], 'help', 'short');
        
        $message = $header . $eol;
        $message .= 'Parametri errati o insufficienti. Per info digitare: ' . $command . ' -' . $param . $eol;
                      
        return $message;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function getHelpLines(array $parameters, array $sections) : array
{
    try {
        $i = 0;
        $lines = [];
        $jMax = count($sections) - 1;
        if (count($parameters) === 0 || $jMax < 0) {
            throw new \Exception('Parametri o sezioni non definite');
        }
        foreach ($parameters as $properties) {
            $cellText[$i] = fillLineSections($properties, $sections);
            if ($sections[$jMax] === 'descriptions') {
                foreach ($properties['descriptions'] as $key => $text) {
                    for ($j = 0; $j < $jMax; $j++) {
                        if ($key === 0) {
                            $lines[$i][$sections[$j]] = $cellText[$i][$j];
                        } else {
                            $lines[$i][$sections[$j]] = '';
                        }
                    }
                    $lines[$i][$sections[$jMax]] = $text;
                    $i++;
                }
            } else {
                foreach ($sections as $key => $secName) {
                    $lines[$i][$secName] = $cellText[$i][$key];
                }
                $i++;
            }
        }
        return $lines;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function fillLineSections(array $properties, array $sections) : array
{
    try {
        if (count($properties) === 0) {
            throw new \Exception('Proprieta non definite');
        }
        foreach ($sections as $secName) {
            $funzioneFormat = 'format' . ucfirst($secName);
            $cellsText[] = Utility::callback($funzioneFormat, array($properties));
        }
        return $cellsText;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function formatDescriptions(array $properties) : string
{
    try {
        if (!array_key_exists('descriptions', $properties)) {
            throw new \Exception('Proprieta "descriptions" non definita');
        }
        $formatted = '';
        foreach ($properties['descriptions'] as $key => $text) {
            if ($key === 0) {
                $formatted .= $text;
            } else {
                $formatted .= ' ' . $text;
            }
        }
        return $formatted;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function formatShort(array $properties) : string
{
    try {
        if (!array_key_exists('short', $properties) || !preg_match('/^[a-zA-Z]$/', $properties['short'])) {
            throw new \Exception('Proprieta "short" non definita o definita in modo errato');
        }
        $formatted = '-' . $properties['short'];
        return $formatted;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function formatLong(array $properties) : string
{
    try {
        if (!array_key_exists('long', $properties) || !preg_match('/^[a-z]{3,}$/', $properties['long'])) {
            throw new \Exception('Proprieta "long" non definita o definita in modo errato');
        }
        $formatted = '--' . $properties['long'];
        return $formatted;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function formatParams(array $properties) : string
{
    try {
        $short = formatShort($properties);
        $long = formatLong($properties);
        if (!preg_match('/^[-][a-zA-Z]$/', $short) || !preg_match('/^[-]{2}[a-z]{3,}$/', $long)) {
            //@codeCoverageIgnoreStart
            throw new \Exception('Proprieta "short" o "long" non definita correttamente');
            //@codeCoverageIgnoreEnd
        }
        $formatted = $short . ' ' . $long;
        return $formatted;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function formatDefault(array $properties) : string
{
    try {
        if (!array_key_exists('default', $properties) || ($properties['default'] !== '' && !preg_match('/^((([0-9]{1,3})?[A-Z])|[A-Z]+)$/', $properties['default']))) {
            throw new \Exception('Proprieta "default" non definita o non definita correttamente');
        }
        if ($properties['default'] !== "") {
            $formatted = '[=' . $properties['default'] . ']';
        } else {
            $formatted = '';
        }
        return $formatted;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function formatPardef(array $properties) : string
{
    try {
        $params = formatParams($properties);
        $default = formatDefault($properties);
        if ((!preg_match('/^[-][a-zA-Z]\s[-]{2}[a-z]{3,}$/', $params)) || ($default !== '' && !preg_match('/^[[][=]((([0-9]{1,3})?[A-Z])|[A-Z]+)[]]$/', $default))) {
            //@codeCoverageIgnoreStart
            throw new \Exception('Formato parametri di default errato');
            //@codeCoverageIgnoreEnd
        }
        $formatted = $params . $default;
        return $formatted;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function formatVariables(array $properties) : string
{
    try {
        if (!array_key_exists('options', $properties) || !array_key_exists('variables', $properties['options'])) {
            throw new \Exception('Proprieta "variables" non definita');
        }
        if (count($properties['options']['variables']) > 0) {
            $prefixeds = preg_filter('/^/', '<', $properties['options']['variables']);
            $postfixeds = preg_filter('/$/', '>', $prefixeds);
            $formatted = implode(',', $postfixeds);
        } else {
            $formatted = '';
        }
        return $formatted;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function formatCostants(array $properties) : string
{
    try {
        if (!array_key_exists('options', $properties) || !array_key_exists('costants', $properties['options'])) {
            throw new \Exception('Proprieta "costants" non definita');
        }
        $costants = $properties['options']['costants'];
        $limits = $properties['options']['limits'];
        if (count($costants) > 0 && count($limits) > 0) {
            foreach ($costants as $key => $costant) {
                $mergeds[] = '[1-' . $limits[$key] . ']' . $costant;
            }
            $formatted = implode('|', $mergeds);
        } elseif (count($costants) > 0) {
            $formatted = implode('|', $costants);
        } else {
            $formatted = '';
        }
        return $formatted;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function formatOptions(array $properties) : string
{
    try {
        $variables = formatVariables($properties);
        $costants = formatCostants($properties);
        if (($variables !== '' && !preg_match('/^[<](\w)+[>]([,][<](\w)+[>])*$/', $variables)) || ($costants !== '' && !preg_match('/^([[][1][-][0-9]{1,3}[]])?([A-Z])+([|]([[][1][-][0-9]{1,3}[]])?([A-Z])+)*$/', $costants))) {
            throw new \Exception('Formato opzioni errato');
        }
        if ($variables !== '' && $costants !== '') {
            $formatted = $variables . '|' . $costants;
        } elseif ($variables !== '') {
            $formatted = $variables;
        } elseif ($costants !== '') {
            $formatted = $costants;
        } else {
            $formatted = '';
        }
        return $formatted;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function getMaxLenght(array $help, string $key) : int
{
    try {
        if (count($help) === 0) {
            throw new \Exception('Array vuoto');
        }
        foreach ($help as $line => $values) {
            if (array_key_exists($key, $values)) {
                $lenghts[] = strlen($values[$key]);
            } else {
                throw new \Exception('Chiave non presente');
            }
        }
        return max($lenghts);
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}
  

function setConsole(array $help, string $eol) : string
{
    try {
        if (count($help) === 0) {
            throw new \Exception('Array help di configurazione vuoto');
        }
        $parameters = $help['parameters'];
        $global = $help['global'];
        $sections = $global['sections'];
        $offset = $global['offset'];
        
        $helpLines = getHelpLines($parameters, $sections);
        
        $message = '';
        foreach ($helpLines as $lineSections) {
            $idSec = 0;
            $gap = 0;
            foreach ($lineSections as $secName => $secText) {
                $secLenght = strlen($secText);
                if ($idSec > 0) {
                    $i = $idSec - 1;
                    $prevSecMaxLen = getMaxLenght($helpLines, $sections[$i]);
                    $prevSecLen = strLen($lineSections[$sections[$i]]);
                    $gap = $prevSecMaxLen - $prevSecLen;
                }
                $message .= str_pad($secText, $secLenght + $gap + $offset, ' ', STR_PAD_LEFT);
                $idSec++;
            }
            $message .= $eol;
        }
        return $message;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function selectAllQuery(string $dbName, string $queryFileName) : array
{
    try {
        $data = getDataFromDb($dbName, $queryFileName, []);
        if (count($data) === 0) {
            //@codeCoverageIgnoreStart
            throw new \Exception('Nessun risultato dalla query');
            //@codeCoverageIgnoreEnd
        }
        $values = [];
        foreach ($data as $record => $fields) {
            foreach ($fields as $field => $value) {
                $values[] = strval($value);
            }
        }
        return $values;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function propertyToString(array $parameters, string $paramName, string $propertyName) : string
{
    try {
        if (count($parameters) === 0) {
            throw new \Exception('Array parametri di configurazione vuoto');
        }
        $property = '';
        foreach ($parameters as $key => $properties) {
            if ($key === $paramName && array_key_exists($propertyName, $properties)) {
                $strings = $properties[$propertyName];
                if (is_array($strings)) {
                    $items = [];
                    array_walk_recursive($strings, function ($item, $key) use (&$items) {
                        $items[] = $item;
                    });
                    $property = implode(' ', $items);
                } else {
                    $property = $strings;
                }
                break;
            }
        }
        return $property;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function getProperties(array $parameters, string $propertyName, ?bool $assoc = false, ?string $filterInName = null, ?string $filterInValue = null, ?string $filterOutName = null, ?string $filterOutValue = null, ?string $prefix = '') : array
{
    try {
        if (count($parameters) === 0) {
            throw new \Exception('Array parametri di configurazione vuoto');
        }
        $group = [];
        $filtered = filterProperties($parameters, $filterInName, $filterInValue, true);
        $excluded = filterProperties($filtered, $filterOutName, $filterOutValue, false);
        foreach ($excluded as $key => $properties) {
            if (array_key_exists($propertyName, $properties)) {
                if ($assoc) {
                    $group[$key] = $prefix . $properties[$propertyName];
                } else {
                    $group[] = $prefix . $properties[$propertyName];
                }
            }
        }
        return $group;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function filterProperties(array $parameters, ?string $field, ?string $value, bool $include) : array
{
    try {
        if (count($parameters) === 0) {
            throw new \Exception('Array parametri di configurazione vuoto');
        }
        if (!isset($field) || !isset($value)) {
            $filtered = $parameters;
        } else {
            $filtered = [];
            $filter = [
                'field' => $field,
                'value' => $value,
                'include' => $include
            ];
            array_walk($parameters, function ($properties, $key, $filter) use (&$filtered) {
                if (array_key_exists($filter['field'], $properties)) {
                    if ($filter['include']) {
                        if ($properties[$filter['field']] === $filter['value']) {
                            $filtered[$key] = $properties;
                        }
                    } else {
                        if ($properties[$filter['field']] !== $filter['value']) {
                            $filtered[$key] = $properties;
                        }
                    }
                } else {
                    $filtered[$key] = $properties;
                }
            }, $filter);
        }
        return $filtered;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function allParameterSet(array $parameters, array $arguments) : bool
{
    try {
        if (count($parameters) === 0) {
            throw new \Exception('Array parametri di configurazione vuoto');
        }
        $response = true;
        foreach ($parameters as $properties) {
            if ($properties['type'] === 'group') {
                $short = '-' . $properties['short'];
                $long = '--' . $properties['long'];
                if (!in_array($short, $arguments) && !in_array($long, $arguments)) {
                    $response = false;
                    break;
                }
            }
        }
        return $response;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function setParameter(array $parameters, string $paramName, array $arguments) : array
{
    try {
        if (count($parameters) === 0) {
            throw new \Exception('Array parametri di configurazione vuoto');
        }
        $short = propertyToString($parameters, $paramName, 'short');
        $long = propertyToString($parameters, $paramName, 'long');
        if (array_search('--' . $long, $arguments)) {
            $keyParam = array_search('--' . $long, $arguments);
        } elseif (array_search('-' . $short, $arguments)) {
            $keyParam = array_search('-' . $short, $arguments);
        }
        $keyValue = $keyParam + 1;
        if ($keyValue < count($arguments)) {
            $paramValue = $arguments[$keyValue];
        }
        $otherShortParams = getProperties($parameters, 'short', null, 'type', 'group', 'short', $short, '-');
        $otherLongParams = getProperties($parameters, 'long', null, 'type', 'group', 'long', $long, '--');
        $otherParams = array_merge($otherShortParams, $otherLongParams);
        $default = propertyToString($parameters, $paramName, 'default');
        $regex = propertyToString($parameters, $paramName, 'regex');
        if (isset($paramValue) && !in_array($paramValue, $otherParams)) {
            $values = checkParameter($paramName, $paramValue, $regex, $default);
        } else {
            $values[] = $default;
        }
        return $values;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function checkParameter(string $paramName, string $paramValue, string $regex, string $default) : array
{
    try {
        $values = [];
        if (preg_match($regex, $paramValue, $matches)) {
            $funzioneCheck = 'checkCli' . ucfirst($paramName);
            $values = Utility::callback($funzioneCheck, array($matches[0]));
        } elseif (preg_match('/^(' . $default . ')$/', $paramValue)) {
            $values[] = $default;
        } else {
            throw new \Exception('Formato parametro "' . $paramName . '" errato.');
        }
        return $values;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function checkCliVar(string $value) : array
{
    try {
        $values = [];
        $varRaw = explode(',', $value);
        foreach ($varRaw as $variable) {
            $request['var'] = $variable;
            try {
                $values[] = checkVariable($request);
            } catch (\Throwable $e) {
                Error::errorHandler($e, DEBUG_LEVEL, 'cli');
            }
        }
        if (count($values) === 0) {
            throw new \Exception('Valori parametro "var" non ammissibili.');
        }
        return $values;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function checkCliDatefrom(string $value) : array
{
    try {
        $values = [];
        if (preg_match('/^[0-9]{2}[\/][0-9]{2}[\/][0-9]{4}$/', $value, $matches)) {
            try {
                $values[] = formatDate($matches[0]);
            } catch (\Throwable $e) {
                Error::errorHandler($e, DEBUG_LEVEL, 'cli');
            }
        } else {
            $values[] = $value;
        }
        if (count($values) === 0) {
            throw new \Exception('Valori parametro "datefrom" non ammissibili.');
        }
        return $values;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function checkCliDateto(string $value) : array
{
    try {
        $values = [];
        try {
            $values[] = formatDate($value);
        } catch (\Throwable $e) {
            Error::errorHandler($e, DEBUG_LEVEL, 'cli');
        }
        if (count($values) === 0) {
            throw new \Exception('Valori parametro "dateto" non ammissibili.');
        }
        return $values;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function checkCliField(string $value) : array
{
    try {
        $values = [];
        try {
            $request['field'] = $value;
            $values[] = checkField($request);
        } catch (\Throwable $e) {
            Error::errorHandler($e, DEBUG_LEVEL, 'cli');
        }
        if (count($values) === 0) {
            throw new \Exception('Valori parametro "field" non ammissibili.');
        }
        return $values;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function checkCliFull(string $value) : array
{
    try {
        $values = [];
        try {
            if ($value === 'TRUE') {
                $request['full'] = 1;
            } elseif ($value === 'FALSE') {
                $request['full'] = 0;
            } else {
                $request['full'] = $value;
            }
            $values[] = checkFilter($request);
        } catch (\Throwable $e) {
            Error::errorHandler($e, DEBUG_LEVEL, 'cli');
        }
        if (count($values) === 0) {
            throw new \Exception('Valori parametro "full" non ammissibili.');
        }
        return $values;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function setParameters(array $parameters, ?array $arguments, string $type) : array
{
    try {
        if (count($parameters) === 0) {
            throw new \Exception('Array parametri di configurazione vuoto');
        }
        $values = [];
        if (isset($arguments) && ($type === 'default' || $type === 'ok')) {
            $names = getProperties($parameters, 'name', null, 'type', 'group');
            foreach ($parameters as $paramName => $properties) {
                if (in_array($properties['name'], $names)) {
                    if ($type === 'default') {
                        $values[$paramName][] = propertyToString($parameters, $paramName, $type);
                    } else {
                        $values[$paramName] = setParameter($parameters, $paramName, $arguments);
                    }
                }
            }
        }
        return $values;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function shuntTypes(array $parameters, ?array $arguments) : string
{
    try {
        if (count($parameters) === 0) {
            throw new \Exception('Array parametri di configurazione vuoto');
        }
        if (!isset($arguments)) {
            $type = 'html';
        } else {
            $narg = count($arguments);
            $isOk = allParameterSet($parameters, $arguments);
            $shorts = getProperties($parameters, 'short', true);
            $longs = getProperties($parameters, 'long', true);
            if ($narg === 1 || ($narg === 2 && ($arguments[1] === '-' . $shorts['help'] || $arguments[1] === '--' . $longs['help']))) {
                $type = 'help';
            } elseif ($narg === 2 && ($arguments[1] === '-' . $shorts['version'] || $arguments[1] === '--' . $longs['version'])) {
                $type = 'version';
            } elseif ($narg === 2 && ($arguments[1] === '-' . $shorts['default'] || $arguments[1] === '--' . $longs['default'])) {
                $type = 'default';
            } elseif ($narg >= 6 && $isOk) {
                $type = 'ok';
            } else {
                $type = 'error';
            }
        }
        return $type;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function fillParameters(array $parameters, array $values) : array
{
    try {
        if (count($parameters) === 0) {
            throw new \Exception('Array parametri di configurazione vuoto');
        }
        $postVars = [];
        $keys = array_keys($values);
        foreach ($keys as $key) {
            $funzioneFill = 'fill' . ucfirst($key);
            $postVars = Utility::callback($funzioneFill, array($parameters, $values, $postVars));
        }
        return $postVars;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function fillVar(array $parameters, array $values, array $postVars) : array
{
    try {
        if (count($parameters) === 0) {
            throw new \Exception('Array parametri di configurazione vuoto');
        }
        $rawValues = $values['var'];
        if ($rawValues[0] === $parameters['var']['default']) {
            $postVars['var'] = selectAllQuery('SSCP_data', 'query_variabili_ALL');
        } else {
            $postVars['var'] = $rawValues;
        }
        return $postVars;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function fillDatefrom(array $parameters, array $values, array $postVars) : array
{
    try {
        if (count($parameters) === 0) {
            throw new \Exception('Array parametri di configurazione vuoto');
        }
        $rawValues = $values['datefrom'];
        $costants = $parameters['datefrom']['options']['costants'];
        $costant = substr($rawValues[0], -1);
        if (in_array($costant, $costants)) {
            $dateto = $values['dateto'][0];
            if ($dateto === $parameters['dateto']['default']) {
                $dateTimeTo = new \DateTime();
            } else {
                $dateTimeTo = new \DateTime($dateto);
            }
            $interval = new \DateInterval('P' . $rawValues[0]);
            $dateTimeFrom = $dateTimeTo->sub($interval);
        } else {
            $dateTimeFrom = new \DateTime($rawValues[0]);
        }
        $postVars['datefrom'] = $dateTimeFrom->format('d/m/Y');
        return $postVars;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function fillDateto(array $parameters, array $values, array $postVars) : array
{
    try {
        if (count($parameters) === 0) {
            throw new \Exception('Array parametri di configurazione vuoto');
        }
        $rawValues = $values['dateto'];
        if ($rawValues[0] === $parameters['dateto']['default']) {
            $dateTimeTo = new \DateTime();
            $interval = new \DateInterval('P1D');
            $dateTimeTo->add($interval);
        } else {
            $dateTimeTo = new \DateTime($rawValues[0]);
        }
        $postVars['dateto'] = $dateTimeTo->format('d/m/Y');
        return $postVars;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function fillField(array $parameters, array $values, array $postVars) : array
{
    try {
        if (count($parameters) === 0) {
            throw new \Exception('Array parametri di configurazione vuoto');
        }
        $rawValues = $values['field'];
        $default = $parameters['field']['default'];
        if ($rawValues[0] === $default) {
            $options = $parameters['field']['options'];
            $key = array_search($default, $options['costants']);
            $postVars['field'] = $options['alias'][$key];
        } else {
            $postVars['field'] = $rawValues[0];
        }
        return $postVars;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function fillFull(array $parameters, array $values, array $postVars) : array
{
    try {
        if (count($parameters) === 0) {
            throw new \Exception('Array parametri di configurazione vuoto');
        }
        $rawValues = $values['full'];
        $postVars['full'] = filter_var($rawValues[0], FILTER_VALIDATE_BOOLEAN) ? '0' : '1';
        return $postVars;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function setPostParameters(array $parameters, array $filledValues) : array
{
    try {
        $postParams = [];
        if (count($filledValues) > 0) {
            $keys = array_keys($filledValues);
            if (array_diff($keys, array_keys($parameters)) || (!array_key_exists('var', $filledValues))) {
                throw new \Exception('Parametri errati');
            }
            $i = 0;
            foreach ($filledValues['var'] as $variable) {
                foreach ($filledValues['datefrom'] as $period => $datefrom) {
                    $dateto = $filledValues['dateto'][$period];
                    $postParams[$i]['id'] = $i;
                    foreach ($keys as $key) {
                        if ($key === 'var') {
                            $postParams[$i][$key] = $variable;
                        } elseif ($key === 'datefrom') {
                            $postParams[$i][$key] = $datefrom;
                        } elseif ($key === 'dateto') {
                            $postParams[$i][$key] = $dateto;
                        } else {
                            $postParams[$i][$key] = $filledValues[$key];
                        }                        
                    }
                    $i++;
                }
            }
        }
        return $postParams;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function buildSetParams(string $url, ?string $rawMethod = null, ?array $rawParams = null, ?string $key = null, ?bool $json = null) : array
{
    try {        
        $i = 0;
        $setParams = [];
        $method = $rawMethod ?? 'GET';
        $postParams = $rawParams ?? [];
        $isJson = $json ?? false;
        if (!Utility::checkUrl($url) || !in_array($method, array('GET','POST','PUT','PATCH','DELETE'))) {
            throw new \Exception('Url non valido o metodo non ammesso');
        }
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
                if (isset($key) && array_key_exists($key, $params)) {
                    $callParams['key'] = $params[$key];
                } elseif (isset($key) && !array_key_exists($key, $params)) {
                    $callParams['key'] = str_replace('*', $idCall, $key);
                } else {
                    $callParams['key'] = null;
                }            
                $setParams[] = $callParams;
            }
        }
        return $setParams;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function selectLastPrevData(string $db, array $parametri, array $dati, string $categoria) : array
{
    try {
        array_walk($parametri, function ($item, $key) {
            if (!isset($item)) {
                throw new \Exception('Parametro ' . $key . ' non impostato');
            }
        });
        if (array_key_exists($categoria, $dati) && count($dati[$categoria]) === 0) {
            $lastParam = [
                'variabile' => $parametri['variabile'],
                'tipo_dato' => $parametri['tipo_dato'],
                'data_e_ora' => $parametri['data_iniziale']
            ];
            $result = getDataFromDb($db, 'query_ultimo_precedente', $lastParam);
            if (count($result) > 0) {
                $strData = $lastParam['data_e_ora']->format('Y-m-d H:i:s');
                $dateTime = new \DateTime($strData, new \DateTimeZone('Europe/Rome'));
                $last[$categoria] = $result;
                $last[$categoria][0]['data_e_ora'] = $dateTime;
            } else {
                $last = $dati;
            }
        } else {
            $last = $dati;
        }
        return $last;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function addTable(array $variabili, array $scarichi, array $dati, array $formule) : array
{
    try {
        $id_modello = $scarichi[0]['modello'];
        $models = CONFIG['modelli'];
        if (!array_key_exists($id_modello, $models)) {
            throw new \Exception('Modello non definito');
        }
        $model = $models[$id_modello];
        $base = $model['base'];
        $fieldsDati = $model['dati'];
        $fieldsCalcoli = $model['calcoli'];
        $fieldsFormule = $model['formule'];
        $fieldsSommatorie = $model['sommatorie'];
        $baseDati = $fieldsDati[0];
        $datiVariabile = $variabili[0];
        $coefficienti = $formule[0];
        $table = [];
        
        $table = inizializza($datiVariabile, $dati[$baseDati], $base);
        
        $table = addCampiDati($table, $dati, $fieldsDati);
        
        $table = addCampiCalcoli($table, $fieldsCalcoli);
        
        $table = addCampiFormule($table, $coefficienti, $fieldsFormule);
        
        $table = addCampiSommatorie($table, $fieldsSommatorie);
        
        return $table;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function addCampiDati(array $table, array $dati, array $fieldsDati) : array
{
    try {
        if (count($fieldsDati) === 0) {
            throw new \Exception('Problemi di configurazione. Campi dati non definiti');
        }
        foreach ($fieldsDati as $field) {
            $table = addCategoria($table, $dati, $field);
        }
        return $table;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function addCampiCalcoli(array $table, array $fieldsCalcoli) : array
{
    try {
        foreach ($fieldsCalcoli as $calcolo => $fields) {
            foreach ($fields as $field) {
                $funzioneAdd = 'add' . ucfirst($calcolo);
                $table = Utility::callback($funzioneAdd, array($table, $field));
            }
        }
        return $table;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function addCampiFormule(array $table, array $coefficienti, array $fieldsFormule) : array
{
    try {
        foreach ($fieldsFormule as $formula => $fields) {
            $funzioneAdd = 'add' . ucfirst($formula);
            $table = Utility::callback($funzioneAdd, array($table, $coefficienti, $fields));
        }
        return $table;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function addCampiSommatorie(array $table, array $fieldsSommatorie) : array
{
    try {
        foreach ($fieldsSommatorie as $sommatoria => $fields) {
            $funzioneAdd = 'add' . ucfirst($sommatoria);
            $table = Utility::callback($funzioneAdd, array($table, $fields));
        }
        return $table;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function loadScarichi(array $request) : array
{
    try {
        if (!array_key_exists('var', $request)) {
            throw new \Exception('Parametro url non definito');
        }
        $db = 'dbcore';
        $queryFileName = 'query_scarichi';
        $parametri = [
            'variabile' => $request['var']
        ];
        $scarichi = getDataFromDb($db, $queryFileName, $parametri);
        
        return $scarichi;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function loadVariabiliScarichi(array $scarichi) : array
{
    try {
        $scarico = $scarichi[0];
        if (!array_key_exists('scarico', $scarico)) {
            throw new \Exception('Variabile non trovata. Scarico non ancora definito.');
        }
        $db = 'dbcore';
        $queryFileName = 'query_variabili_scarichi';
        $parametri = [
            'scarico' => $scarico['scarico']
        ];
        $variabili_scarichi = getDataFromDb($db, $queryFileName, $parametri);
        
        return $variabili_scarichi;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function loadVariabili(array $scarichi) : array
{
    try {
        $scarico = $scarichi[0];
        if (!array_key_exists('variabile', $scarico)) {
            throw new \Exception('Chiave variabile non definita');
        }
        $db = 'SSCP_data';
        $queryFileName = 'query_variabili';
        $parametri = [
            'variabile' => $scarico['variabile']
        ];
        $variabili = getDataFromDb($db, $queryFileName, $parametri);
        
        return $variabili;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function loadFormule(array $scarichi) : array
{
    try {
        $scarico = $scarichi[0];
        if (!array_key_exists('scarico', $scarico)) {
            throw new \Exception('Chiave scarico non definita');
        }
        $db = 'dbcore';
        $queryFileName = 'query_formule';
        $parametri = [
            'scarico' => $scarico['scarico']
        ];
        $formule = getDataFromDb($db, $queryFileName, $parametri);
        
        return $formule;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function loadDatiAcquisiti(array $request, array $variabili_scarichi) : array
{
    try {
        if (!array_key_exists('datefrom', $request) || !array_key_exists('dateto', $request)) {
            throw new \Exception('Chiave datefrom e/o dateto non definite');
        }
        $dati_acquisiti = [];
        foreach ($variabili_scarichi as $record) {
            $dati = [];
            $categoria = $record['categoria'];
            $db = $record['db'];
            $queryFileName = 'query_dati_acquisiti';
            $parametri = [
                'variabile' => $record['variabile'],
                'tipo_dato' => $record['tipo_dato'],
                'data_iniziale' => $request['datefrom'],
                'data_finale' => $request['dateto'],
                'data_attivazione' => $record['data_attivazione'],
                'data_disattivazione' => $record['data_disattivazione']
            ];
            $dati[$categoria] = getDataFromDb($db, $queryFileName, $parametri);
            $dati_manovra = selectLastPrevData($db, $parametri, $dati, 'manovra');
            $dati_acquisiti = array_merge_recursive($dati_acquisiti, $dati_manovra);
        }
        return $dati_acquisiti;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function changeMode(string $path, string $url, int $mode) : bool
{
    try {
        $isFtp = (parse_url($path, PHP_URL_SCHEME) === 'ftp') ? true : false;
        if (ASYNC && MAKESUBDIR && $isFtp) {
            //@codeCoverageIgnoreStart
            $response = false;
            //@codeCoverageIgnoreEnd
        } else {
            $response = true;
            $pathDiff = explode($path . '/', $url);
            $pathParts = explode('/', $pathDiff[1]);
            foreach ($pathParts as $part) {
                $path .= '/' . $part;
                if ($isFtp) {
                    $user = parse_url($path, PHP_URL_USER);
                    $password = parse_url($path, PHP_URL_PASS);
                    $host = parse_url($path, PHP_URL_HOST);
                    $file = parse_url($path, PHP_URL_PATH);
                    $conn = @ftp_connect($host);
                    if ($conn) {
                        $isLogged = ftp_login($conn, $user, $password);
                        $isOk = boolval(ftp_chmod($conn, $mode, $file));
                        ftp_close($conn);
                    } else {
                        throw new \Exception('Connessione al server ftp non riuscita');
                    }
                } else {
                    $isOk = @chmod($path, $mode);
                }
                if ($isOk === false) {
                    $response = false;
                }
            }
        }
        return $response;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}
    
    
function addCostants(array $parametri, array $formule) : array
{
    try {        
        if (count($parametri) === 0) {
            throw new \Exception('Non ci sono dati per le date selezionate');
        }
        $costanti = [];
        $formula = [];
        array_walk($formule[0], function ($value, $key) use (&$formula) {
            if (!is_null($value)) {
                $formula[$key] = $value;
            }
        });
        foreach ($parametri as $record => $campi) {
            $costanti[$record] = array_merge($formula, $campi);
        }
        return $costanti;
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
}


function sendTelegram(string $message, ?string $nut = null, ?int $tagLimit = null, ?int $limit = null, ?bool $force = false, ?string $chatId = null) : string
{
    try {
        if ($message !== '') {
            if ($force || TELEGRAM) {
                $isOk = Bot::checkAndSend($message, $tagLimit, $limit, $nut, $chatId);
                if ($isOk) {
                    $response = 'Messaggio Telegram inviato con successo.' . PHP_EOL;
                } else {
                    $response = 'Invio messaggio Telegram fallito.' . PHP_EOL;
                }
            } else {
                $response = 'Invio messaggio Telegram disabilitato.' . PHP_EOL;
            }
        } else {
            $response = '';
        }
        return $response;
    //@codeCoverageIgnoreStart
    } catch (\Throwable $e) {        
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;        
    }
    //@codeCoverageIgnoreEnd
}


function limitDates(array $values, ?string $limit = null, ?string $offset = null) : array
{
    try {
        $dateLimit = $limit ?? PERIOD;
        $dateOffset = $offset ?? OFFSET;
        $limited = [];
        if (count($values) > 0) {
            if (!array_key_exists('datefrom', $values) || !array_key_exists('datefrom', $values)) {
                throw new \Exception('Parametri date mancanti');
            }
            $keys = array_keys($values);
            foreach ($keys as $key) {
                if ($key !== 'datefrom' && $key !== 'dateto') {
                    $limited[$key] = $values[$key];
                }
            }
            $dateStart = \DateTime::createFromFormat('d/m/Y', $values['datefrom'], new \DateTimeZone('Europe/Rome'));
            $dateEnd = \DateTime::createFromFormat('d/m/Y', $values['dateto'], new \DateTimeZone('Europe/Rome'));
            $interval = new \DateInterval('P' . $dateLimit);
            $offset = new \DateInterval('P' . $dateOffset);
            $periods = new \DatePeriod($dateStart, $interval, $dateEnd);
            $i = 0;
            foreach ($periods as $period) {
                $datefrom = clone $period;
                $datefrom->sub($offset);
                $dateto = clone $period;
                $dateto->add($interval);            
                $limited['datefrom'][$i] = $datefrom->format('d/m/Y');
                $limited['dateto'][$i] = $dateto->format('d/m/Y');
                $i++;
            }
            $limited['datefrom'][0] = $values['datefrom'];
            $limited['dateto'][$i - 1] = $values['dateto']; 
        }
        return $limited;
    } catch (\Throwable $e) {        
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;        
    }
}

/**
 * @param array $messages
 * @return string
 * @throws \Throwable
 */
function buildTelegram(array $messages, ?bool $sendGlobal = null) : string
{
    try {
        $isGlobal = $sendGlobal ?? GLOBALMSG;
        $globalMessage = '';
        if (count($messages) > 0) {            
            if ($isGlobal) {
                $globalMessage .= '<b>' . TELSCARICHI['header']['title']['global'] . '</b>' . PHP_EOL;
                $globalMessage .= TELSCARICHI['header']['start'] . ' <b>' . Utility::microToLatinTime(START) . '</b>' . PHP_EOL;
                $globalMessage .= PHP_EOL;
                $globalMessage .= $messages['sync'] . PHP_EOL;
                $globalMessage .= PHP_EOL;
                $globalMessage .= $messages['tocsv'] . PHP_EOL;
                $globalMessage .= PHP_EOL;
                $globalMessage .= str_replace('dati', 'dati host 1', $messages['watch1']) . PHP_EOL;
                $globalMessage .= PHP_EOL;
                $globalMessage .= str_replace('dati', 'dati host 2', $messages['watch2']) . PHP_EOL;
                $globalMessage .= PHP_EOL;            
                $globalMessage .= TELSCARICHI['footer']['stop'] . ' <b>' . Utility::getLatinTime() . '</b>' . PHP_EOL;
                $globalMessage .= TELSCARICHI['footer']['time'] . ' <b>' . Utility::benchmark(START) . '</b>' . PHP_EOL;
            } else {
                $globalMessage = $messages['tocsv'];
            }            
        }
        return $globalMessage;
    //@codeCoverageIgnoreStart
    } catch (\Throwable $e) {        
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;        
    }
    //@codeCoverageIgnoreEnd
}

/**
 * @param string $type
 * @param bool $sendMode
 * @param array $csvParams
 * @return array
 * @throws \Throwable
 */
function callServices(string $type, ?array $csvParams, ?bool $mode = null) : array
{
    try {
        $sendMode = $mode ?? MODE;
        $messages = [];
        if ($type === 'ok' || $type === 'default') {            

            $syncService = New ServiceManager('telegram_REST', 'sync', [['tel' => $sendMode]]);
            $messages['sync'] = $syncService->getMessage();

            $csvService = New ServiceManager('tocsv', null, $csvParams);
            $messages['tocsv'] = $csvService->getMessage(); 

            $watchService1 = New ServiceManager('telegram_REST', 'watchdog', [['tel' => $sendMode]]);
            $messages['watch1'] = $watchService1->getMessage();

            $watchService2 = New ServiceManager('telegram_REST', 'watchdog', [['host' => 2,'move' => 1,'tel' => $sendMode]]);
            $messages['watch2'] = $watchService2->getMessage();
        }
        return $messages;
    //@codeCoverageIgnoreStart
    } catch (\Throwable $e) {
        Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
        throw $e;
    }
    //@codeCoverageIgnoreEnd
}
