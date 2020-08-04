<?php
namespace vaniacarta74\scarichi;

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

    switch ($scarichi[0]['tipo']) {
        case 'sfioratore di superficie':

            $volumi = initVolumi($variabili[0], $dati_completi['livello']);

            $volumi = addCategoria($volumi, $dati_completi, 'livello');

            $volumi = addMedia($volumi, 'livello');

            $volumi = addAltezza($volumi, $formule[0]);

            $volumi = addPortata($volumi, $formule[0]);

            $volumi = addDelta($volumi, 'data_e_ora');

            $volumi = addVolume($volumi);

            break;
        case 'scarico di superficie':
            
            $volumi = initVolumi($variabili[0], $dati_completi['livello']);
            
            $volumi = addCategoria($volumi, $dati_completi, 'livello');
            
            $volumi = addMedia($volumi, 'livello');
            
            $volumi = addCategoria($volumi, $dati_completi, 'manovra');
            
            $volumi = addAltezza($volumi, $formule[0]);

            $volumi = addPortata($volumi, $formule[0]);

            $volumi = addDelta($volumi, 'data_e_ora');

            $volumi = addVolume($volumi);

            break;
        case 'scarico di mezzofondo':
            
            $volumi = initVolumi($variabili[0], $dati_completi['livello']);
            
            $volumi = addCategoria($volumi, $dati_completi, 'livello');
            
            $volumi = addMedia($volumi, 'livello');
            
            $volumi = addCategoria($volumi, $dati_completi, 'manovra');
            
            $volumi = addAltezza($volumi, $formule[0]);

            $volumi = addPortata($volumi, $formule[0]);

            $volumi = addDelta($volumi, 'data_e_ora');

            $volumi = addVolume($volumi);
            
            break;
        case 'scarico di fondo':
            
            $volumi = initVolumi($variabili[0], $dati_completi['livello']);
            
            $volumi = addCategoria($volumi, $dati_completi, 'livello');
            
            $volumi = addMedia($volumi, 'livello');
            
            $volumi = addCategoria($volumi, $dati_completi, 'manovra');
            
            $volumi = addAltezza($volumi, $formule[0]);

            $volumi = addPortata($volumi, $formule[0]);

            $volumi = addDelta($volumi, 'data_e_ora');

            $volumi = addVolume($volumi);

            break;
        case 'scarico by-pass':
            
            $volumi = initVolumi($variabili[0], $dati_completi['livello']);
            
            $volumi = addCategoria($volumi, $dati_completi, 'livello');
            
            $volumi = addMedia($volumi, 'livello');
            
            $volumi = addCategoria($volumi, $dati_completi, 'manovra');
            
            $volumi = addAltezza($volumi, $formule[0]);

            $volumi = addPortata($volumi, $formule[0]);

            $volumi = addDelta($volumi, 'data_e_ora');

            $volumi = addVolume($volumi);

            break;
        case 'galleria':
            
            $volumi = initVolumi($variabili[0], $dati_completi['livello']);
            
            $volumi = addCategoria($volumi, $dati_completi, 'livello');
            
            $volumi = addMedia($volumi, 'livello');
            
            $volumi = addCategoria($volumi, $dati_completi, 'livello valle');
            
            $volumi = addMedia($volumi, 'livello valle');
            
            $volumi = addCategoria($volumi, $dati_completi, 'manovra');
            
            $volumi = addAltezza($volumi, $formule[0]);

            $volumi = addPortata($volumi, $formule[0]);

            $volumi = addDelta($volumi, 'data_e_ora');

            $volumi = addVolume($volumi);

            break;
    }

    $volumi = format($volumi, $request['field']);
    
    $volumi = filter($volumi, $request['full']);

    $printed = divideAndPrint($volumi, $request['full'], $request['field']);
    
    echo response($request, $printed);
} catch (Throwable $e) {
    echo errorHandler($e);
    exit();
}
