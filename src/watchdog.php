<?php
namespace vaniacarta74\Scarichi;

use vaniacarta74\Scarichi\Error;
use vaniacarta74\Scarichi\Curl;
use vaniacarta74\Scarichi\Utility;

require __DIR__ . '/../vendor/autoload.php';

try {
    $request = $argv ?? null;
    $n = Utility::catchParam($request, 1, $_REQUEST, 'n', NITER);
    $delay = Utility::catchParam($argv, 2, $_REQUEST, 'delay', DELAY);
    $rawUrl = Utility::catchParam($argv, 3, $_REQUEST, 'url', BOTURL);
    $url = Utility::checkParam($rawUrl, 'Utility::checkUrl');
    for ($i=1; $i <= $n; $i++) {
        $dateTime = new \DateTime('NOW', new \DateTimeZone('Europe/Rome'));
        $when = $dateTime->format('d/m/y H:i:s');
        $result = Curl::run($url);
        echo 'PID ' . $i . ' ' . $when . '.' . PHP_EOL . htmlspecialchars(strip_tags($result)); 
        usleep($delay);
    }    
} catch (\Throwable $e) {
    Error::errorHandler($e, DEBUG_LEVEL, 'cli');
    exit();
}
