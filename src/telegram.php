<?php
namespace vaniacarta74\Scarichi;

require __DIR__ . '/../vendor/autoload.php';

try {
    $path = __DIR__ . '/../../telegram.json';
    $jsonIn = Utility::getJsonArray($path);
    $bots = $jsonIn['bots'];
    
    foreach ($bots as $bot) {
        $objBot = new Bot($bot);
        
        $telegram['bots'][] = $objBot->getProperties();
        
        var_dump($objBot);
        var_dump($telegram);
    }
    $jsonOut = json_encode($telegram);
    $response = file_put_contents($path, $jsonOut);
    //chmod($path, 0777);
    //var_dump($json);
    //echo $response;
} catch (\Throwable $e) {
    Error::errorHandler($e, DEBUG_LEVEL, 'html');
    exit();
}
