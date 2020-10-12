<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace vaniacarta74\Scarichi;

require __DIR__ . '/../vendor/autoload.php';

$url = "http://localhost/telecontrollo/scarichi/github/src/index.php";

//$argc = 7;
//$argv = ['scarichi.php', '-V', '30030,30040', '-f', '-t', '-c', '-n'];

try {
    $composer = getJsonArray(__DIR__ . '/../composer.json');
    $help = getJsonArray(__DIR__ . '/config/help.json');
    $parameters = $help['parameters'];
    $names = getProperties($parameters, 'name', 'type', 'group');
    $shorts = getAssocProperties($parameters, 'short');
    $longs = getAssocProperties($parameters, 'long');
    
    if (!isset($argv)) {
        $message = getMessage($composer, $help, 'www', 'redirect');
    } else {
        if ($argc === 1 || ($argc === 2 && ($argv[1] === '-' . $shorts['help'] || $argv[1] === '--' . $longs['help']))) {
            $message = getMessage($composer, $help, 'cli', 'help'); 
        } elseif ($argc === 2 && ($argv[1] === '-' . $shorts['version'] || $argv[1] === '--' . $longs['version'])) {
            $message = getMessage($composer, $help, 'cli', 'version');
        } elseif ($argc === 2 && ($argv[1] === '-' . $shorts['default'] || $argv[1] === '--' . $longs['default'])) {
            $message = getMessage($composer, $help, 'cli', 'default');
            $values = setParameters($parameters, $argv, true);
        } elseif ($argc >= 6 && allParameterSet($parameters, $argv)) {            
            $message = getMessage($composer, $help, 'cli', 'ok');            
            $values = setParameters($parameters, $argv, false);
        } else {
            $message = getMessage($composer, $help, 'cli', 'error');            
        }
    }
    if (isset($values)) {
        $filledValues = fillParameters($parameters, $values);
        $postParams = setPostParameters($filledValues);
        $message .= goCurl($postParams, $url);
    }
    $message .= get_current_user();
    echo $message;        
} catch (\Throwable $e) {
    Utility::errorHandler($e, DEBUG_LEVEL);
    exit();
}