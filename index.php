<?php

require_once(__DIR__ . '/tools.php');

if (isset($_REQUEST['var'])) {
    
    $variabile = htmlspecialchars(strip_tags($_REQUEST['var']));
    
    $dates = setDates($_REQUEST);
    
    $conn = connect('dbcore');
        
    $stmt = query($conn, 'query_scarichi', array($variabile));
    
    if ($stmt !== false) {        
        $scarichi = fetch($stmt);
        
        $scarico = $scarichi[0]['scarico'];
        $tipo = $scarichi[0]['tipo'];
        
        switch ($tipo) {
            case 'sfioratore di superficie':              
                
                $stmt = query($conn, 'query_sfiori', array($scarico));
    
                if ($stmt !== false) {        
                    $sfiori = fetch($stmt);
                } else {
                    print_r(sqlsrv_errors(), true);
                    $sfiori = array();
                }
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
                
        $stmt = query($conn, 'query_variabili', array($scarico));
    
        if ($stmt !== false) {        
            $variabili = fetch($stmt);        
            close($conn);

            foreach ($variabili as $key => $record) {

                $conn = connect($record['db']);

                $stmt = query($conn, 'query_dati_acquisiti', array($record['variabile'], $record['tipo_dato'], $dates['datefrom'], $dates['dateto']));

                if ($stmt !== false) {

                    $dati_acquisiti[$key] = fetch($stmt);        
                    close($conn);

                    $dati_acquisiti_medie[$key] = addMedia($dati_acquisiti[$key]);
                    
                    $dati_medie_delta[$key] = addDelta($dati_acquisiti_medie[$key]);

                } else {
                    die(print_r(sqlsrv_errors(), true));
                    close($conn);
                } 
            }

            //var_dump($variabili);
            //var_dump($dati_acquisiti);
            //var_dump($dati_acquisiti_medie);
            var_dump($dati_medie_delta);

        } else {
            die(print_r(sqlsrv_errors(), true));
            close($conn);
        }
        
    } else {
        die(print_r(sqlsrv_errors(), true));
        close($conn);
    }     
        
    
        
} else {
    echo 'Richiesta variabile';
}