<?php

require_once(__DIR__ . '/config/config_MSSQL_HOST.php');
require_once('php_MSSQL_' . HOST . '.inc.php');


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
    $n = 5;
    $delay = 40000;
    $serverName = MSSQL_HOST;
    $connectionInfo = array('Database' => $dbName, 'UID' => MSSQL_USER, 'PWD' => MSSQL_PASSWORD);   

    for ($i=1; $i <= $n; $i++) {
        try {
            $connessione = sqlsrv_connect($serverName, $connectionInfo);            
            break;
        } 
        catch (Error $e) {
            echo $e->getMessage();
        }
        usleep($delay);
    }  
    return $connessione;
}


function query($conn, string $queryFile, array $paramValues)
{
    include __DIR__ . '/include/query/' . $queryFile . '.php';

    $query = str_replace($paramNames, $paramValues, $queryString);
    
    $stmt = sqlsrv_query($conn, $query);
    
    return $stmt;
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
