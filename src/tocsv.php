<?php
namespace vaniacarta74\Scarichi;

require __DIR__ . '/../vendor/autoload.php';

try {
    $request = checkRequest($_REQUEST, true);
    
    $scarichi = loadScarichi($request);
    $variabili_scarichi = loadVariabiliScarichi($scarichi);
    $variabili = loadVariabili($scarichi);
    $formule = loadFormule($scarichi);

    $dati_acquisiti = loadDatiAcquisiti($request, $variabili_scarichi);
    
    $dati_distinti = eraseDoubleDate($dati_acquisiti);
    $dati_uniformati = uniformaCategorie($dati_distinti);
    $dati_completi = completaDati($dati_uniformati);
    
    $tabella = addTable($variabili, $scarichi, $dati_completi, $formule);

    $dati_formattati = format($tabella, $request['field']);
    $dati_filtrati = filter($dati_formattati, $request['full'], 0);
    $dati_ripuliti = filter($dati_filtrati, false, NODATA);

    $printed = divideAndPrint($dati_ripuliti, $request['full'], $request['field']);
    
    echo response($request, $printed);
} catch (\Throwable $e) {
    Error::errorHandler($e, DEBUG_LEVEL, 'html');
    exit();
}
