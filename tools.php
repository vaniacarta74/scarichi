<?php

require_once(__DIR__ . '/config/config.php');
require_once('php_MSSQL_' . RDBMS . '.inc.php');


function setDates(array $request) : array
{
    if (isset($request['datefrom']) && isset($request['dateto'])) {
        
        $dateFrom = htmlspecialchars(strip_tags($request['datefrom']));
        $dateTo = htmlspecialchars(strip_tags($request['dateto']));
        
    } elseif (isset($request['datefrom'])) {
        
        $dateFrom = htmlspecialchars(strip_tags($request['datefrom']));
        $dateTo = date('d/m/Y');
        
    } elseif (isset($request['dateto'])) {
        
        $dateFrom = htmlspecialchars(strip_tags($request['dateto']));
        $dateTo = date('d/m/Y', strtotime($dateFrom . ' +1 day'));
        
    } else {
        
        $dateFrom = date('d/m/Y');
        $dateTo = date('d/m/Y', strtotime($dateFrom . ' +1 day'));
    }    
    $dates = array('datefrom' => $dateFrom, 'dateto' => $dateTo);
    
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


function query($conn, string $queryFile, array $paramValues)
{
    try {
        include __DIR__ . '/include/query/' . $queryFile . '.php';

        $query = str_replace($paramNames, $paramValues, $queryString);

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
    $dateFrom = str_replace('/', '', $dates['datefrom']);
    $dateTo = str_replace('/', '', $dates['dateto']);
    
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
                            $formatted[$record][$nuovo] = $valore->format('d/m/Y H:i');
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
