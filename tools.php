<?php

require_once(__DIR__ . '/config/config_MSSQL_HOST.php');
require_once('php_MSSQL_' . HOST . '.inc.php');


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


function fetch($stmt) : array
{
    $record = 0;
    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        foreach ($row as $key => $value) {
            $dati[$record][$key] = $value;
        }
        $record++;
    }
    
    return $dati;
}
