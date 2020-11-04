<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 * 
 * $argc = 2;
 ] $argv = ['scarichi.php', '-h'];
 */
namespace vaniacarta74\Scarichi;

require __DIR__ . '/../vendor/autoload.php';

try {
    $composer = COMPOSER;
    $help = CONFIG;
    $parameters = $help['parameters'];
    $url = $help['command']['url'];
    $async = $help['command']['async'];

    $type = shuntTypes($parameters, $argv);
    echo getMessage($composer, $help, $type);
    $values = setParameters($parameters, $argv, $type);
    
    $filledValues = fillParameters($parameters, $values);
    $postParams = setPostParameters($parameters, $filledValues);
    echo goCurl($postParams, $url, $async); 
} catch (\Throwable $e) {
    Error::errorHandler($e, DEBUG_LEVEL, true);
    exit();
}
