<?php

require_once(__DIR__ . '/tools.php');

if (isset($_REQUEST['var'])) {
    
    $conn = connect('dbcore');
    
    $paramValues = array(htmlspecialchars(strip_tags($_REQUEST['var'])));
    
    $stmt = query($conn, 'query_variabili_correlate_dbcore', $paramValues);
    
    if ($stmt !== false) {        
        $dati = fetch($stmt);
        
        var_dump($dati); 
        
        
    } else {
        die(print_r(sqlsrv_errors(), true));
    }    
} else {
    echo 'Richiesta variabile';
}