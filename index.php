<?php

require_once(__DIR__ . '/tools.php');
   
$variabile = checkVariable($_REQUEST);

$dates = setDates($_REQUEST);

$fileName = setFile($variabile, $dates);

$db = 'dbcore';
$conn = connect($db);
$params = setQueryParams($db, 'query_scarichi', $variabile);
$stmt = query($conn, $params);
$scarichi = setToLocal($db, fetch($stmt));

echo '<br/><b>Variabile Scarico:</b>';
var_dump($scarichi);

$scarico = $scarichi[0]['scarico'];
$db = $scarichi[0]['db'];
$variabile = $scarichi[0]['variabile'];
$tipo = $scarichi[0]['tipo'];

$params = setQueryParams($db, 'query_variabili_scarichi', $scarico);
$stmt = query($conn, $params);
$variabili_scarichi = setToLocal($db, fetch($stmt));        
close($conn);

echo '<br/><b>Variabili Correlate Scarico:</b>';
var_dump($variabili_scarichi);

$conn = connect($db);
$params = setQueryParams($db, 'query_variabili', $variabile);
$stmt = query($conn, $params);
$variabili = setToLocal($db, fetch($stmt));
close($conn);

echo '<br/><b>Variabile Volume Scaricato:</b>';
var_dump($variabili); 

$dati_acquisiti = array();
foreach ($variabili_scarichi as $record) {        

    $dati = array();
    $db = $record['db'];
    $categoria = $record['categoria'];

    $conn = connect($db);
    $params = setQueryParams($db, 'query_dati_acquisiti', array_merge($dates, $record));
    $stmt = query($conn, $params);      
    $dati[$categoria] = setToLocal($db, fetch($stmt));    
    close($conn);

    $dati_acquisiti = array_merge_recursive($dati_acquisiti, $dati);        
}

echo '<br/><b>Dati Acquisiti:</b>';
var_dump($dati_acquisiti);

switch ($tipo) {
    case 'sfioratore di superficie':
        
        $db = 'dbcore'; 
        $conn = connect($db);
        $params = setQueryParams($db, 'query_sfiori', $scarico);
        $stmt = query($conn, $params);
        $sfiori = setToLocal($db, fetch($stmt));
        close($conn);            

        $volumi = initVolumi($variabili[0], $dati_acquisiti['livello']);

        $volumi = addLivello($volumi, $dati_acquisiti['livello']);

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