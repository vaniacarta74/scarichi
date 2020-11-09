<?php
namespace vaniacarta74\Scarichi;

require __DIR__ . '/../vendor/autoload.php';

try {
    $request = checkRequest($_REQUEST);
    
    $scarichi = loadScarichi($request);    
    $variabili_scarichi = loadVariabiliScarichi($scarichi);
    $variabili = loadVariabili($scarichi);    
    $formule = loadFormule($scarichi);

    $dati_acquisiti = loadDatiAcquisiti($request, $variabili_scarichi);    
    
    $dati_ripuliti = eraseDoubleDate($dati_acquisiti);
    $dati_uniformati = uniformaCategorie($dati_ripuliti);    
    $dati_completi = completaDati($dati_uniformati);
    
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
