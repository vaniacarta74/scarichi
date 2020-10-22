<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace vaniacarta74\Scarichi;

require __DIR__ . '/../vendor/autoload.php';

$url = "http://localhost/telecontrollo/scarichi/github/src/index.php";
//$url = "http://192.168.1.100/telecontrollo/scarichi/github/src/index.php";

//$argc = 2;
//$argv = ['scarichi.php', '-h'];

try {
    $composer = getJsonArray(__DIR__ . '/../composer.json');
    $help = getJsonArray(__DIR__ . '/config/help.json');
    $parameters = $help['parameters'];   

    $type = shuntTypes($parameters, $argv);
    $message = getMessage($composer, $help, $type);
    $values = setParameters($parameters, $argv, $type);
    
    $filledValues = fillParameters($parameters, $values);
    $postParams = setPostParameters($parameters, $filledValues);
    $message .= goCurl($postParams, $url);
    
    echo $message;        
} catch (\Throwable $e) {
    Utility::errorHandler($e, DEBUG_LEVEL, true);
    exit();
}