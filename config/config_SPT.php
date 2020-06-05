<?php

require_once(__DIR__ . '/config_MSSQL_HOST.php');
require_once('php_MSSQL_' . HOST . '.inc.php');
require_once(__DIR__ . '/../tools.php');

$serverName = MSSQL_HOST;
$connectionInfo = array('Database' => SPT_DB, 'UID' => MSSQL_USER, 'PWD' => MSSQL_PASSWORD);

$connessione = connect($serverName, $connectionInfo, 5, 40000);



