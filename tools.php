<?php

require_once(__DIR__ . '/config/config.php');
require_once('php_MSSQL_' . RDBMS . '.inc.php');


function printErrorInfo(string $functionName) : void
{
    $date = New DateTime();
    echo $date->format('d/m/Y H:i:s') . ': Errore fatale funzione <b>' . $functionName . '()</b><br/>'; 
}


function errorHandler(Throwable $e) : void
{
    echo '<br/><b>Descrizione Errore:</b><br/>';
    echo 'File: ' . $e->getFile() . '<br/>'; 
    echo 'Linea: ' . $e->getLine() . '<br/>'; 
    echo 'Codice errore: ' . $e->getCode() . '<br/>'; 
    echo 'Messaggio di errore: <b>' . $e->getMessage() . '</b><br/>';
    
    $stack = $e->getTraceAsString();
    $arrStack = explode('#', $stack);
    
    echo '<br/><b>Stack:</b>';
    foreach ($arrStack as $line) {
        echo $line . '<br/>';
    }    
}


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
        printErrorInfo(__FUNCTION__);
        throw $e;
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
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function formatDateTime(string $date) : string
{
    try {
        $dateTimeParts = explode(' ', $date);
        
        $dateParts = explode('/', $dateTimeParts[0]);
        $timeParts = explode(':', $dateTimeParts[1]);

        $day = intval($dateParts[0]);
        $month = intval($dateParts[1]);
        $year = intval($dateParts[2]);
        
        $hours = intval($timeParts[0]);
        $minutes = intval($timeParts[1]);
        $seconds = intval($timeParts[2]);
        
        $formatDateTime = date('Y-m-d H:i:s', mktime($hours, $minutes, $seconds, $month, $day, $year));

        return $formatDateTime;
        
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function setDates(?array $request) : array
{
    try {
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
        
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function setDateTimes(array $request) : array
{
    try {
        $dateFrom = formatDateTime($request['datefrom']);       
        $dateTo = formatDateTime($request['dateto']);
        
        $dateTimeFrom = New DateTime($dateFrom);
        $dateTimeTo = New DateTime($dateTo);

        $dates = array('datefrom' => $dateTimeFrom, 'dateto' => $dateTimeTo);

        return $dates;
        
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
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
        printErrorInfo(__FUNCTION__);
        throw $e;
    }   
}


function query($conn, string $fileName, array $paramValues)
{
    try {
        include __DIR__ . '/include/query/' . $fileName . '.php';

        $query = str_replace($paramNames, $paramValues, $queryString);

        $stmt = sqlsrv_query($conn, $query);
        
        if ($stmt !== false) {            
            return $stmt;                    
        } else {            
            throw new Exception(error());
        } 
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }   
}


function fetch($stmt) : ?array
{
    try {
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
        
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    } 
}


function close($conn)
{
    try {
        
        sqlsrv_close($conn);
        
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
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
        
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function addDelta(array $dati, string $nomeCampo) : array
{
    try {
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
        
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function initVolumi(array $variabili, array $dati) : array
{
    try {
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
        
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
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
        printErrorInfo(__FUNCTION__);
        throw $e;
    }   
}


function addAltezza(array $dati, float $quota, string $nomeCampo) : array
{
    try {
        foreach ($dati as $record => $campi) {
            foreach ($campi as $campo => $valore) {

                $altezze[$record][$campo] = $valore;

                if ($campo === $nomeCampo) {
                    $altezze[$record]['altezza'] = $dati[$record][$campo] - $quota;
                }
            }            
        }
        return $altezze;
        
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }   
}


function addPortata(array $dati, array $specifiche) : array
{
    $mi = $specifiche['mi'];
    $larghezza_soglia = $specifiche['larghezza'];
    $portata_massima = $specifiche['limite'];
    $nomeCampo = 'altezza';
    
    try {
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
        
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function addVolume(array $dati) : array
{
    try {
        foreach ($dati as $record => $campi) {
            foreach ($campi as $campo => $valore) {

                $volumi[$record][$campo] = $valore;

            }
            $volumi[$record]['volume'] = $volumi[$record]['portata'] * $volumi[$record]['delta'];
        }    
        return $volumi;
    
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function setFile(string $variabile, array $dates) : string
{    
    try {
        $dateFrom = $dates['datefrom']->format('YmdHi');
        $dateTo = $dates['dateto']->format('YmdHi');

        $fileName = CSV . '/Volumi_' . $variabile . '_' . $dateFrom . '_' . $dateTo . '.csv';

        return $fileName;
    
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function format(array $dati) : array
{  
    $nomiCampi = array(
        'variabile' => 'variabile',
        'valore' => 'volume',
        'data_e_ora' => 'data_e_ora',
        'tipo_dato' => 'tipo_dato'
    );
    
    try {
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
    
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}  


function changeTimeZone (string $dateIn, bool $isLocalToUTC, bool $format)
{
    try {
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
        
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function datesToString(array $dates, string $format) : array
{
    try {
        foreach ($dates as $key => $date) {
            if (is_a($date, 'DateTime')) {
                $formattedDates[$key] = $date->format($format);
            } else {
                $formattedDates[$key] = $date;
            }
        }    
        return $formattedDates;
    
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function checkDates(string $db, array $dates, bool $isLocalToUTC) : array
{
    try {    
        foreach ($dates as $key => $date) {
            if (is_a($date, 'DateTime') && ($db === 'SPT')) {
                $dateString = $date->format('Y-m-d H:i:s');
                $checkedDates[$key] = changeTimeZone($dateString, $isLocalToUTC, false);
            } else {
                $checkedDates[$key] = $date;
            }
        }    
        return $checkedDates;
        
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function setToLocal(string $db, array $dati) : ?array
{
    try {
        foreach ($dati as $record => $campi) {
            $locals[$record] = checkDates($db, $campi, false);        
        }
        return $locals;
        
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function getDataFromDB(string $db, string $queryFileName, array $parametri) : ?array
{
    try {
        $conn = connect($db);
        
        $checkedDateParams = checkDates($db, $parametri, true);
        
        $params = datesToString($checkedDateParams, 'd/m/Y H:i:s');
        
        $stmt = query($conn, $queryFileName, $params);
        
        $rawData = fetch($stmt);
        
        $data = setToLocal($db, $rawData);
        
        close($conn);
        
        return $data;
        
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
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
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function divideAndPrint(array $data) : void
{
    try {
        $i = 0;
        $printableData = array();
        foreach($data as $record) {
            if ($i === MAXRECORD) {                
                $variabile = $printableData[0]['variabile'];
                $dates = array(
                    'datefrom' => $printableData[0]['data_e_ora'],
                    'dateto' => $printableData[$i - 1]['data_e_ora']
                );
                $dateTimes = setDateTimes($dates);
                $fileName = setFile($variabile, $dateTimes);
                printToCSV($printableData, $fileName);
                $i = 0;
                $printableData = array();                
            }
            $printableData[$i] = $record;
            $i++;
        }
        if ($i > 0) {
            $variabile = $printableData[0]['variabile'];
            $dates = array(
                'datefrom' => $printableData[0]['data_e_ora'],
                'dateto' => $printableData[$i - 1]['data_e_ora']
            );
            $dateTimes = setDateTimes($dates);
            $fileName = setFile($variabile, $dateTimes);
            printToCSV($printableData, $fileName);
        }   
        
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}    
