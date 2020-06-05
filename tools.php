<?php

function connect(string $serverName, array $connectionInfo, int $n, int $delay) //: resource
{
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

