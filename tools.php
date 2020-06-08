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

