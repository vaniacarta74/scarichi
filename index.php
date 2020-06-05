<?php

if (isset($_REQUEST['var'])) {
    
    $variabile = htmlspecialchars(strip_tags($_REQUEST['var']));
    echo 'Variabile: ' . $variabile;
} else {
    echo 'Richiesta variabile';
}

require_once(__DIR__ . '/config/config_DBCORE.php');

echo $connessione;

require_once(__DIR__ . '/config/config_SPT.php');

echo $connessione;

require_once(__DIR__ . '/config/config_SSCP_data.php');

echo $connessione;