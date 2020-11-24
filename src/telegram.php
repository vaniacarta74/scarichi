<?php
namespace vaniacarta74\Scarichi;

require __DIR__ . '/../vendor/autoload.php';

try {
    $path = __DIR__ . '/../../telegram.json';
    $jsonIn = Utility::getJsonArray($path);
    $bots = $jsonIn['bots'];    
    foreach ($bots as $bot) {
        $objBot = new Bot($bot);
        $objBot->run();
        $telegram['bots'][] = $objBot->getProperties();
    }
    $jsonOut = json_encode($telegram);
    $response = file_put_contents($path, $jsonOut);
} catch (\Throwable $e) {
    Error::errorHandler($e, DEBUG_LEVEL, 'html');
    exit();
}
