<?php

try {
    require_once(__DIR__ . '/tools.php');

    $variabile = checkVariable($_REQUEST);
    
    $filtered = checkFilter($_REQUEST);

    $dates = checkInterval($_REQUEST);

    $fileName = setFile($variabile, $dates);

    $db = 'dbcore';
    $queryFileName = 'query_scarichi';
    $parametri = array(
        'variabile' => $variabile
    );
    $scarichi = getDataFromDB($db, $queryFileName, $parametri);

    echo '<br/><b>Variabile Scarico:</b>';
    var_dump($scarichi);

    $db = 'dbcore';
    $queryFileName = 'query_variabili_scarichi';
    $parametri = array(
        'scarico' => $scarichi[0]['scarico']
    );
    $variabili_scarichi = getDataFromDB($db, $queryFileName, $parametri);

    echo '<br/><b>Variabili Correlate Scarico:</b>';
    var_dump($variabili_scarichi);

    $db = $scarichi[0]['db'];
    $queryFileName = 'query_variabili';
    $parametri = array(
        'variabile' => $scarichi[0]['variabile']
    );
    $variabili = getDataFromDB($db, $queryFileName, $parametri);

    echo '<br/><b>Variabile Volume Scaricato:</b>';
    var_dump($variabili); 

    $dati_acquisiti = array();
    foreach ($variabili_scarichi as $record) {        

        $dati = array();
        $categoria = $record['categoria'];

        $db = $record['db'];
        $queryFileName = 'query_dati_acquisiti';
        $parametri = array(
            'variabile' => $record['variabile'],
            'tipo_dato' => $record['tipo_dato'],
            'data_iniziale' => $dates['datefrom'],
            'data_finale' => $dates['dateto'],
            'data_attivazione' => $record['data_attivazione'],
            'data_disattivazione' => $record['data_disattivazione']        
        );
        $dati[$categoria] = getDataFromDB($db, $queryFileName, $parametri);

        $dati_acquisiti = array_merge_recursive($dati_acquisiti, $dati);        
    }

    echo '<br/><b>Dati Acquisiti:</b>';
    var_dump($dati_acquisiti);

    switch ($scarichi[0]['tipo']) {
        case 'sfioratore di superficie':

            $db = 'dbcore';
            $queryFileName = 'query_sfiori';
            $parametri = array(
                'scarico' => $scarichi[0]['scarico']
            );
            $sfiori = getDataFromDB($db, $queryFileName, $parametri);

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
    
    $volumi = filter($volumi, $filtered);

    echo '<br/><b>Volumi Formattati:</b>';
    var_dump($volumi);

    divideAndPrint($volumi);
    
} catch (Throwable $e) {
    errorHandler($e);
    exit();    
}