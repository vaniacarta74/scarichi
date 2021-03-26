<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 *
 * $argc = 2;
 * $argv = ['scarichi.php', '-h'];
 */
namespace vaniacarta74\Scarichi;

require __DIR__ . '/../vendor/autoload.php';

//$argc = 9;
//$argv = ['scarichi.php', '-V', '30030', '-f', '01/01/2019', '-t', '02/01/2019', '-c', '-n', 'FALSE'];
//$argv = ['scarichi.php', '-v'];

try {
    $composer = COMPOSER;
    $help = CONFIG;
    $parameters = $help['parameters'];    
    
    $type = shuntTypes($parameters, $argv);
    echo getMessage($composer, $help, $type);
    $values = setParameters($parameters, $argv, $type);
    
    $filledValues = fillParameters($parameters, $values);
    $limitedValues = limitDates($filledValues);
    $params = setPostParameters($parameters, $limitedValues);
    
    $messages = callServices($type, $params);    
    $telegram = buildTelegram($messages);
    echo sendTelegram($telegram, PHP_EOL);
} catch (\Throwable $e) {
    Error::errorHandler($e, DEBUG_LEVEL, 'cli');
    exit();
}
