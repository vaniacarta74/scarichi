<?php

require_once(__DIR__ . '/tools.php');

if (isset($_REQUEST['var'])) {
    
    $variabile = htmlspecialchars(strip_tags($_REQUEST['var']));
    
    $dates = setDates($_REQUEST);
    
    $conn = connect('dbcore');        
    $stmt = query($conn, 'query_scarichi', array($variabile)); 
    $scarichi = fetch($stmt);
    
    var_dump($scarichi);
    
    $scarico = $scarichi[0]['scarico'];
    $tipo = $scarichi[0]['tipo'];
    
    $stmt = query($conn, 'query_variabili', array($scarico));       
    $variabili = fetch($stmt);        
    close($conn);
    
    var_dump($variabili);
    
    foreach ($variabili as $key => $record) {

        $conn = connect($record['db']);
        $stmt = query($conn, 'query_dati_acquisiti', array($record['variabile'], $record['tipo_dato'], $dates['datefrom'], $dates['dateto']));
        $dati_acquisiti[$key] = fetch($stmt);        
        close($conn);

        $dati_acquisiti[$key] = addMedia($dati_acquisiti[$key]);
        $dati_acquisiti[$key] = addDelta($dati_acquisiti[$key]);
    }
    
    var_dump($dati_acquisiti);
    
    switch ($tipo) {
        case 'sfioratore di superficie':
            
            $conn = connect('dbcore');
            $stmt = query($conn, 'query_sfiori', array($scarico));
            $sfiori = fetch($stmt);
            close($conn);
            
            var_dump($sfiori);
            
            break;
        case 'scarico di superficie':

            break;
        case 'scarico di mezzofondo':

            break;
        case 'scarico di fondo':

            break;
        case 'galleria':

            break;
    }    
} else {
    echo 'Richiesta variabile';
}