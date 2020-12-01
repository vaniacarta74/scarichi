<?php
namespace vaniacarta74\Scarichi;

require __DIR__ . '/../vendor/autoload.php';

try {
    $botPath = (isset($argv) && isset($argv[1])) ? $argv[1] : BOTPATH;
    $jsonIn = Utility::getJsonArray($botPath);
    $bots = $jsonIn['bots'];    
    foreach ($bots as $bot) {
        $objBot = new Bot($bot);
        $objBot->run();
        $telegram['bots'][] = $objBot->getProperties();
    }
    $jsonOut = json_encode($telegram);
    file_put_contents($botPath, $jsonOut);
} catch (\Throwable $e) {
    Error::errorHandler($e, DEBUG_LEVEL, 'html');
    exit();
}
