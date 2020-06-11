<?php

require_once(__DIR__ . '/tools.php');

if (isset($_REQUEST['var'])) {
    
    $variabile = htmlspecialchars(strip_tags($_REQUEST['var']));
    
    $dates = setDates($_REQUEST);
    
    $conn = connect('dbcore');        
    $stmt = query($conn, 'query_scarichi', array($variabile)); 
    $scarichi = fetch($stmt);
    
    echo '<br/>Scarichi:';
    var_dump($scarichi);
    
    $scarico = $scarichi[0]['scarico'];
    $db = $scarichi[0]['db'];
    $variabile = $scarichi[0]['variabile'];
    $tipo = $scarichi[0]['tipo'];
    
    $stmt = query($conn, 'query_variabili_scarichi', array($scarico));       
    $variabili_scarichi = fetch($stmt);        
    close($conn);
    
    echo '<br/>Variabili Scarichi:';
    var_dump($variabili_scarichi);
    
    $conn = connect($db);        
    $stmt = query($conn, 'query_variabili', array($variabile)); 
    $variabili = fetch($stmt);
    close($conn);
    
    echo '<br/>Variabili:';
    var_dump($variabili); 
        
    foreach ($variabili_scarichi as $key => $record) {

        $conn = connect($record['db']);
        $stmt = query($conn, 'query_dati_acquisiti', array($record['variabile'], $record['tipo_dato'], $dates['datefrom'], $dates['dateto']));
        $dati_acquisiti[$key] = fetch($stmt);        
        close($conn);

        //$dati_acquisiti[$key] = addMedia($dati_acquisiti[$key], 'valore');
        //$dati_acquisiti[$key] = addDelta($dati_acquisiti[$key], 'data_e_ora');
    }
    
    echo '<br/>Dati Acquisiti:';
    var_dump($dati_acquisiti);
    
    switch ($tipo) {
        case 'sfioratore di superficie':
            
            $conn = connect('dbcore');
            $stmt = query($conn, 'query_sfiori', array($scarico));
            $sfiori = fetch($stmt);
            close($conn);
            
            echo '<br/>Sfiori:';
            var_dump($sfiori);            
            
            $volumi = initVolumi($variabili[0], $dati_acquisiti[0]);
            
            echo '<br/>Volumi:';
            var_dump($volumi);
            
            $volumi = addLivello($volumi, $dati_acquisiti[0]);
            
            echo '<br/>Livelli:';
            var_dump($volumi);  
            
            $volumi = addMedia($volumi, 'livello');
            
            echo '<br/>Medie:';
            var_dump($volumi);
            
            $volumi = addDelta($volumi, 'data_e_ora');
            echo '<br/>Delta:';
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
} else {
    echo 'Richiesta variabile';
}