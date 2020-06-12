<?php

require_once(__DIR__ . '/tools.php');

if (isset($_REQUEST['var'])) {
    
    $variabile = htmlspecialchars(strip_tags($_REQUEST['var']));
    
    $dates = setDates($_REQUEST);
    
    $fileName = setFile($variabile, $dates);
    
    $conn = connect('dbcore');        
    $stmt = query($conn, 'query_scarichi', array($variabile)); 
    $scarichi = fetch($stmt);
    
    echo '<br/><b>Variabile Scarico:</b>';
    var_dump($scarichi);
    
    $scarico = $scarichi[0]['scarico'];
    $db = $scarichi[0]['db'];
    $variabile = $scarichi[0]['variabile'];
    $tipo = $scarichi[0]['tipo'];
    
    $stmt = query($conn, 'query_variabili_scarichi', array($scarico));       
    $variabili_scarichi = fetch($stmt);        
    close($conn);
    
    echo '<br/><b>Variabili Correlate Scarico:</b>';
    var_dump($variabili_scarichi);
    
    $conn = connect($db);        
    $stmt = query($conn, 'query_variabili', array($variabile)); 
    $variabili = fetch($stmt);
    close($conn);
    
    echo '<br/><b>Variabile Volume Scaricato:</b>';
    var_dump($variabili); 
        
    foreach ($variabili_scarichi as $key => $record) {

        $conn = connect($record['db']);
        $stmt = query($conn, 'query_dati_acquisiti', array($record['variabile'], $record['tipo_dato'], $dates['datefrom'], $dates['dateto']));
        $dati_acquisiti[$key] = fetch($stmt);        
        close($conn);
    }
    
    switch ($tipo) {
        case 'sfioratore di superficie':
            
            $conn = connect('dbcore');
            $stmt = query($conn, 'query_sfiori', array($scarico));
            $sfiori = fetch($stmt);
            close($conn);
            
            echo '<br/><b>Caratteristiche Sfioro:</b>';
            var_dump($sfiori);            
            
            $volumi = initVolumi($variabili[0], $dati_acquisiti[0]);
                        
            $volumi = addLivello($volumi, $dati_acquisiti[0]);
            
            $volumi = addMedia($volumi, 'livello');
            
            $volumi = addAltezza($volumi, $sfiori[0]['quota'], 'media');
            
            $volumi = addPortata($volumi, $sfiori[0]);
                        
            $volumi = addDelta($volumi, 'data_e_ora');
            
            $volumi = addVolume($volumi);          
            
            echo '<br/><b>Volumi Sfiorati:</b>';
            var_dump($volumi);
            
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

    $volumi = format($volumi);
    
    echo '<br/><b>Volumi Formattati:</b>';
    var_dump($volumi);
    
    printToCSV($volumi, $fileName);           
    
} else {
    echo 'Richiesta variabile';
}