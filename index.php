<?php

require_once(__DIR__ . '/tools.php');

if (isset($_REQUEST['var'])) {
    
    $dates = setDates($_REQUEST);
    
    $conn = connect('dbcore');
    
    $paramValues = array(htmlspecialchars(strip_tags($_REQUEST['var'])));
    
    $stmt = query($conn, 'query_variabili_correlate', $paramValues);
    
    if ($stmt !== false) {        
        $dati = fetch($stmt);        
        close($conn);
        
        foreach ($dati as $key => $record) {
            
            $conn = connect($record['db']);
            
            $stmt = query($conn, 'query_dati_acquisiti', array($record['variabile'], $record['tipo_dato'], $dates['datefrom'], $dates['dateto']));
            
            if ($stmt !== false) {
                
                $dati_acquisiti[$key] = fetch($stmt);        
                close($conn);
                
                var_dump($dati_acquisiti); 
                
            } else {
                die(print_r(sqlsrv_errors(), true));
                close($conn);
            } 
        }
        
        var_dump($dati);        
        
    } else {
        die(print_r(sqlsrv_errors(), true));
        close($conn);
    }    
} else {
    echo 'Richiesta variabile';
}