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
//$argv = ['scarichi.php', '-V', '30030,30040', '-f', '2Y6M15D', '-t', '01/02/2018', '-c', '-n'];

try {
    $composer = COMPOSER;
    $help = CONFIG;
    $parameters = $help['parameters'];
    
    $type = shuntTypes($parameters, $argv);
    echo getMessage($composer, $help, $type);
    $values = setParameters($parameters, $argv, $type);
    
    $filledValues = fillParameters($parameters, $values);
    $limitedValues = limitDates($filledValues, PERIOD, OFFSET);
    $postParams = setPostParameters($parameters, $limitedValues);
    $message = goCurl($postParams, URL, ASYNC);
    echo sendTelegram($message, PHP_EOL);
} catch (\Throwable $e) {
    Error::errorHandler($e, DEBUG_LEVEL, 'cli');
    exit();
}
