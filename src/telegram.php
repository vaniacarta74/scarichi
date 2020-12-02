<?php
namespace vaniacarta74\Scarichi;

use vaniacarta74\Scarichi\Utility;
use vaniacarta74\Scarichi\Bot;
use vaniacarta74\Scarichi\Error;

require __DIR__ . '/../vendor/autoload.php';

try {
    $botPath = Utility::setDefault($argv, 1, BOTPATH, 'Utility::checkPath', array('w'));
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
