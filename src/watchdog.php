<?php
namespace vaniacarta74\Scarichi;

use vaniacarta74\Scarichi\Error;
use vaniacarta74\Scarichi\Curl;

require __DIR__ . '/../vendor/autoload.php';

try {
    $n = 12;
    $delay = 5000000;
    for ($i=1; $i <= $n; $i++) {
        $dateTime = new \DateTime('NOW', new \DateTimeZone('Europe/Rome'));
        echo $dateTime->format('d/m/y h:i:s') . PHP_EOL;
        Curl::run('http://localhost/scarichi/telegram.php');
        usleep($delay);
    }    
} catch (\Throwable $e) {
    Error::errorHandler($e, DEBUG_LEVEL, 'cli');
    exit();
}
