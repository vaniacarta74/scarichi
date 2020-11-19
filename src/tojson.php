<?php
namespace vaniacarta74\Scarichi;

require __DIR__ . '/../vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try {
    $post = @file_get_contents('php://input');
    $arrPost = json_decode($post, true);
    $params = $arrPost ?? $_REQUEST;    
    
    $request = checkRequest($params, true);
    
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
    
    http_response_code(200);
    echo json_encode($dati_ripuliti);
} catch (\Throwable $e) {
    http_response_code(400);
    Error::errorHandler($e, DEBUG_LEVEL, 'json');
    exit();
}
