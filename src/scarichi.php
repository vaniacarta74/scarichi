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

//$argc = 2;
//$argv = ['scarichi.php', '-V', '-f', '6M', '-t', '-c', 'ML', '-n'];

try {
    $composer = COMPOSER;
    $help = CONFIG;
    $parameters = $help['parameters'];
    
    $type = shuntTypes($parameters, $argv);
    echo getMessage($composer, $help, $type);
    $values = setParameters($parameters, $argv, $type);
    
    $filledValues = fillParameters($parameters, $values);
    $postParams = setPostParameters($parameters, $filledValues);
    echo goCurl($postParams, URL, ASYNC);
} catch (\Throwable $e) {
    Error::errorHandler($e, DEBUG_LEVEL, 'cli');
    exit();
}
