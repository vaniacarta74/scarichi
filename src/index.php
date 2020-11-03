<?php
namespace vaniacarta74\Scarichi;

require __DIR__ . '/../vendor/autoload.php';

try {
    $request = checkRequest($_REQUEST);
    
    $db = 'dbcore';
    $queryFileName = 'query_scarichi';
    $parametri = [
        'variabile' => $request['var']
    ];
    $scarichi = getDataFromDb($db, $queryFileName, $parametri);

    $db = 'dbcore';
    $queryFileName = 'query_variabili_scarichi';
    $parametri = [
        'scarico' => $scarichi[0]['scarico']
    ];
    $variabili_scarichi = getDataFromDb($db, $queryFileName, $parametri);

    $db = 'SSCP_data';
    $queryFileName = 'query_variabili';
    $parametri = [
        'variabile' => $scarichi[0]['variabile']
    ];
    $variabili = getDataFromDb($db, $queryFileName, $parametri);

    $dati_acquisiti = [];
    foreach ($variabili_scarichi as $record) {
        $dati = [];
        $categoria = $record['categoria'];

        $db = $record['db'];
        $queryFileName = 'query_dati_acquisiti';
        $parametri = [
            'variabile' => $record['variabile'],
            'tipo_dato' => $record['tipo_dato'],
            'data_iniziale' => $request['datefrom'],
            'data_finale' => $request['dateto'],
            'data_attivazione' => $record['data_attivazione'],
            'data_disattivazione' => $record['data_disattivazione']
        ];
        $dati[$categoria] = getDataFromDb($db, $queryFileName, $parametri);
        
        $dati = selectLastPrevData($db, $parametri, $dati, 'manovra');

        $dati_acquisiti = array_merge_recursive($dati_acquisiti, $dati);
    }
    
    $dati_ripuliti = eraseDoubleDate($dati_acquisiti);

    $dati_uniformati = uniformaCategorie($dati_ripuliti);
    
    $dati_completi = completaDati($dati_uniformati);
    
    $db = 'dbcore';
    $queryFileName = 'query_formule';
    $parametri = [
        'scarico' => $scarichi[0]['scarico']
    ];
    $formule = getDataFromDb($db, $queryFileName, $parametri);
    
    $tabella = addTable($variabili, $scarichi, $dati_completi, $formule);

    $tabella = format($tabella, $request['field']);
    
    $tabella = filter($tabella, $request['full'], 0);
    
    $tabella = filter($tabella, false, NODATA);

    $printed = divideAndPrint($tabella, $request['full'], $request['field']);
    
    echo response($request, $printed);
} catch (\Throwable $e) {
    Error::errorHandler($e, DEBUG_LEVEL, false);
    exit();
}
