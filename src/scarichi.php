<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace vaniacarta74\Scarichi;

require __DIR__ . '/../vendor/autoload.php';

//$argc = 2;
//$argv = ['scarichi.php', '-h'];

try {
    $composer = COMPOSER;
    $help = CONFIG;
    $parameters = $help['parameters'];
    $url = $help['url'];

    $type = shuntTypes($parameters, $argv);
    $message = getMessage($composer, $help, $type);
    $values = setParameters($parameters, $argv, $type);
    
    $filledValues = fillParameters($parameters, $values);
    $postParams = setPostParameters($parameters, $filledValues);
    $message .= goCurl($postParams, $url);    
    
    echo $message;
} catch (\Throwable $e) {
    Error::errorHandler($e, DEBUG_LEVEL, true);
    exit();
}
