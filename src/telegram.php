<?php
namespace vaniacarta74\Scarichi;

use vaniacarta74\Scarichi\Utility;
use vaniacarta74\Scarichi\BotManager;
use vaniacarta74\Scarichi\Error;

require __DIR__ . '/../vendor/autoload.php';

try {
    $request = $argv ?? null;
    $rawPath = Utility::catchParam($request, 1, $_REQUEST, 'path', BOTPATH);
    $botPath = Utility::checkParam($rawPath, 'Utility::checkPath', array('w'));
    $arrJson = BotManager::load($botPath);
    $result = BotManager::exec($arrJson);
    $isOk = BotManager::update($result, $botPath);    
    echo BotManager::print($result, $isOk);
} catch (\Throwable $e) {
    Error::errorHandler($e, DEBUG_LEVEL, 'html');
    exit();
}
