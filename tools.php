<?php

require_once(__DIR__ . '/config/config.php');
require_once('php_MSSQL_' . RDBMS . '.inc.php');


function checkVariable(?array $request) : string
{
    try {
        if (isset($request['var']) || isset($request['variabile']) || isset($request['variable'])) {
            
            $paramNames = array('var', 'variabile', 'variable');
            foreach ($paramNames AS $key) {
                if (array_key_exists($key, $request)) {                    
                    $variabile = $request[$key];
                    break;
                }
            }
            
            $n_variabile = intval($variabile);    
            if ($n_variabile >= 30000 && $n_variabile <= 39999) {
                return $variabile;
            } else {
                throw new Exception('Variabile non analizzabile. Valori ammessi compresi fra 30000 e 39999');
            }
        } else {
            throw new Exception("Parametro variabile non presente nell'url o nome parametro non valido. Usare var, variable o variabile");
        }        
    } catch (Throwable $e) {
        exit($e->getMessage());
    }
}


function formatDate(string $date) : string
{
    try {
        $cleanDate = htmlspecialchars(strip_tags($date));

        $dateParts = explode('/', $cleanDate);

        $day = intval($dateParts[0]);
        $month = intval($dateParts[1]);
        $year = intval($dateParts[2]);

        if (checkdate($month, $day, $year)) {
            $formatDate = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
            
            return $formatDate;    
        } else {        
            throw new Exception('Parametro data inserito nel formato errato. Formato richiesto "gg/mm/yyyy"');
        }
    } catch (Throwable $e) {
        exit($e->getMessage());
    }
}


function setDates(?array $request) : array
{
    if (isset($request['datefrom']) && isset($request['dateto'])) {
        
        $dateFrom = formatDate($request['datefrom']);       
        $dateTo = formatDate($request['dateto']);       
        
    } elseif (isset($request['datefrom'])) {
        
        $dateFrom = formatDate($request['datefrom']);
        $dateTo = date('Y-m-d');
        
    } elseif (isset($request['dateto'])) {
        
        $dateFrom = formatDate($request['dateto']);
        $dateTo = date('Y-m-d', strtotime($dateFrom . ' +1 day'));
        
    } else {
        
        $dateFrom = date('Y-m-d');
        $dateTo = date('Y-m-d', strtotime($dateFrom . ' +1 day'));
    }
    $dateTimeFrom = New DateTime($dateFrom);
    $dateTimeTo = New DateTime($dateTo);
    
    $dates = array('datefrom' => $dateTimeFrom, 'dateto' => $dateTimeTo);
    
    return $dates;
}


function connect(string $dbName) //: resource
{
    $n = 2;
    $delay = 40000;
    $serverName = MSSQL_HOST;
    $connectionInfo = array('Database' => $dbName, 'UID' => MSSQL_USER, 'PWD' => MSSQL_PASSWORD);   
    
    try {
        for ($i=1; $i <= $n; $i++) {
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if($conn) {
                break;
            } else {
                usleep($delay);
            }
        }
        if($conn) {
            return $conn;
        } else {
            throw new Exception(error());
        }
    }
    catch (Throwable $e) {
        exit($e->getMessage());
    }   
}


function checkDates(string $db, array $dates) : array
{
    foreach ($dates as $key => $date) {
        if (is_a($date, 'DateTime')) {
            if ($db === 'SPT') {
                $dateString = $date->format('Y-m-d H:i:s');
                $checkedDates[$key] = changeTimeZone($dateString, true, true);
            } else {
                $checkedDates[$key] = $date->format('d/m/Y H:i:s');
            }
        } else {
            $checkedDates[$key] = $date;
        }
    }    
    return $checkedDates;
}


function setQueryParams(string $db, string $fileName, $mixedParam) : array
{
    switch ($fileName) {
        case 'query_scarichi':
        case 'query_variabili_scarichi':
        case 'query_variabili':
        case 'query_sfiori':
            $parametri = checkDates($db, array($mixedParam));            
            break;        
        case 'query_dati_acquisiti':
            $rawParams = $mixedParam;
            $checkedParams = checkDates($db, $rawParams);
            $parametri = array(
                $checkedParams['variabile'],
                $checkedParams['tipo_dato'],
                $checkedParams['datefrom'],
                $checkedParams['dateto'],
                $checkedParams['data_attivazione'],
                $checkedParams['data_disattivazione']
            );
            break;
    }    
    $params = array(
        'file' => $fileName,
        'parametri' => $parametri
    );
    
    return $params;
}


function query($conn, array $paramValues)
{
    try {
        include __DIR__ . '/include/query/' . $paramValues['file'] . '.php';

        $query = str_replace($paramNames, $paramValues['parametri'], $queryString);

        $stmt = sqlsrv_query($conn, $query);
        
        if ($stmt !== false) {            
            return $stmt;                    
        } else {            
            $errorsMessage = error();
            sqlsrv_close($conn);
            throw new Exception($errorsMessage);
        } 
    } 
    catch (Throwable $e) {
        exit($e->getMessage());
    }   
}


function fetch($stmt) : ?array
{
    $record = 0;
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        foreach ($row as $key => $value) {
            $dati[$record][$key] = $value;
        }
        $record++;
    }
    if (!isset($dati)) {
        $dati = array();
    }
    
    return $dati;
}


function close($conn)
{
    sqlsrv_close($conn);
}


function error() : string
{
    $errors = sqlsrv_errors();
    $text = '<pre>' . print_r($errors, true) . '</pre>';
    
    return $text;
}


function addMedia(array $dati, string $nomeCampo) : array
{
    foreach ($dati as $record => $campi) {
        foreach ($campi as $campo => $valore) {
            
            $medie[$record][$campo] = $valore;
            
            if ($campo === $nomeCampo) {
                if ($record === 0) {
                    $media = $valore;
                } else {
                    $media = ($valore + $medie[$record - 1][$campo])/2;
                }                
                $medie[$record]['media'] = $media;
            }
        }
    }
    return $medie;
}


function addDelta(array $dati, string $nomeCampo) : array
{
    foreach ($dati as $record => $campi) {
        foreach ($campi as $campo => $valore) {
            
            $delta[$record][$campo] = $valore;
            
            if ($campo === $nomeCampo) {
                if ($record === 0) {
                    $deltaT = 0;
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
}


function setToLocal(string $db, array $dati) : array
{
    foreach ($dati as $record => $campi) {
        foreach ($campi as $campo => $valore) {
            
            if (is_a($valore, 'DateTime') && ($db === 'SPT')) {
                $dateString = $valore->format('Y-m-d H:i:s');
                $locals[$record][$campo] = changeTimeZone($dateString, false, false);
            } else {
                $locals[$record][$campo] = $valore;
            }
        }
    }
    return $locals;
}


function initVolumi(array $variabili, array $dati) : array
{
    foreach ($dati as $record => $campi) {
        
        $volumi[$record]['variabile'] = $variabili['id_variabile'];
        
        foreach ($campi as $campo => $valore) {
            if ($campo === 'data_e_ora') {
                $volumi[$record][$campo] = $valore;
            }      
        }
        
        $volumi[$record]['unita_misura'] = $variabili['unita_misura'];
        $volumi[$record]['impianto'] = $variabili['impianto'];
        $volumi[$record]['tipo_dato'] = 1;
    }
    return $volumi;
}


function addLivello(array $volumi, array $dati) : array
{
    try {
        if (count($volumi) !== count($dati)) {
            throw new Exception('Array differenti');
        }
        foreach ($volumi as $record => $campi) {
            foreach ($campi as $campo => $valore) {
                
                $livelli[$record][$campo] = $valore;
                
                if (($campo === 'data_e_ora') && ($volumi[$record][$campo] === $dati[$record][$campo])) {
                    $livelli[$record]['livello'] = $dati[$record]['valore'];
                }
                
            }            
        }
        return $livelli;
    } catch (Throwable $e) {
        exit($e->getMessage());
    }    
}


function addAltezza(array $dati, float $quota, string $nomeCampo) : array
{
    foreach ($dati as $record => $campi) {
        foreach ($campi as $campo => $valore) {

            $altezze[$record][$campo] = $valore;

            if ($campo === $nomeCampo) {
                $altezze[$record]['altezza'] = $dati[$record][$campo] - $quota;
            }
        }            
    }
    return $altezze;
}


function addPortata(array $dati, array $specifiche) : array
{
    $mi = $specifiche['mi'];
    $larghezza_soglia = $specifiche['larghezza'];
    $portata_massima = $specifiche['limite'];
    $nomeCampo = 'altezza';
    
    foreach ($dati as $record => $campi) {
        foreach ($campi as $campo => $valore) {

            $portate[$record][$campo] = $valore;

            if ($campo === $nomeCampo) {
                $altezza_sfioro = $dati[$record][$campo];
            }
        }
        if($altezza_sfioro > 0) {
            
            $portata = $mi * $larghezza_soglia * $altezza_sfioro * sqrt(2 * 9,81 * $altezza_sfioro);
            
            if ($portata <= $portata_massima) {
                $portate[$record]['portata'] = $portata;
            } else {
                $portate[$record]['portata'] = 0;
            }
        } else {
            $portate[$record]['portata'] = 0;
        }
    }
    return $portate;
}


function addVolume(array $dati) : array
{
    foreach ($dati as $record => $campi) {
        foreach ($campi as $campo => $valore) {

            $volumi[$record][$campo] = $valore;
            
        }
        $volumi[$record]['volume'] = $volumi[$record]['portata'] * $volumi[$record]['delta'];
    }    
    return $volumi;
}


function printToCSV(array $dati, string $fileName) : void
{
    $mode = 'w';
    $delimiter = ';';
    
    try {
        $handle = fopen($fileName, $mode);
        
        $nomiCampi = array_keys($dati[0]);
        
        fputcsv($handle, $nomiCampi, $delimiter);
        
        foreach ($dati AS $valori) {
            fputcsv($handle, $valori, $delimiter);
        }        
        
        fclose($handle);
    } catch (Throwable $e) {
        echo $e->getMessage();
    }
}


function setFile(string $variabile, array $dates) : string
{    
    $dateFrom = $dates['datefrom']->format('Ymd');
    $dateTo = $dates['dateto']->format('Ymd');
    
    $fileName = CSV . '/Volumi_' . $variabile . '_' . $dateFrom . '_' . $dateTo . '.csv';
    
    return $fileName;
}


function format(array $dati) : array
{  
    $nomiCampi = array(
        'variabile' => 'variabile',
        'valore' => 'volume',
        'data_e_ora' => 'data_e_ora',
        'tipo_dato' => 'tipo_dato'
    );
    
    foreach ($dati as $record => $campi) {
        foreach ($campi as $campo => $valore) {
            if(in_array($campo, $nomiCampi)) {
                foreach($nomiCampi AS $nuovo => $vecchio) {
                    if($campo === $vecchio) {
                        if(is_a($valore, 'DateTime')) {
                            $formatted[$record][$nuovo] = $valore->format('d/m/Y H:i:s');
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
}  


 function localToUtc($date_local, $db) {

    if ($db === 'SPT') {

        $zone = 'Europe/Rome';

        //Converti a ora UTC+1 (solare)
        $date_utc = new DateTime($date_local, new DateTimeZone($zone));
        $date_utc->setTimezone(new DateTimeZone('Etc/GMT-1'));

        $sql_date = date('Y-m-d H:i:s',strtotime($date_utc->format('d/m/Y H:i:s')));        
        $sql_date = $date_utc->format('d/m/Y H:i:s');

        return $sql_date;

    } else {
        return $date_local;
    }
}


 function utcToLocal($date_utc, $db) {

    if ($db === 'SPT') {
        
        $zone = 'Etc/GMT-1';
        
        //Converti a ora locale
        $date_local = new DateTime($date_utc, new DateTimeZone($zone));
        $date_local->setTimezone(new DateTimeZone('Europe/Rome'));

        $sql_date = date('Y-m-d H:i:s',strtotime($date_local->format('Y-m-d H:i:s')));

        return $sql_date;

    } else {
        return $date_db;
    }
}


function changeTimeZone (string $dateIn, bool $isLocalToUTC, bool $format)
{
    if ($isLocalToUTC) {
        $zoneIn = 'Europe/Rome';
        $zoneOut = 'Etc/GMT-1';    
    } else {
        $zoneIn = 'Etc/GMT-1';
        $zoneOut = 'Europe/Rome';
    }        
    
    $dateTime = new DateTime($dateIn, new DateTimeZone($zoneIn));
    $dateTime->setTimezone(new DateTimeZone($zoneOut));
    
    if ($format) {
        $dateOut = $dateTime->format('d/m/Y H:i:s');
    } else {
        $dateOut = $dateTime;
    }   
    return $dateOut;
}
