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
//$argv = ['scarichi.php', '-V', '30030', '-f', '01/01/2019', '-t', '02/01/2019', '-c', '-n'];

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
    
    Curl::run('http://' . REMOTE_HOST . '/telecontrollo/bot/telegram_REST.php?token=sync&variabile=ALL&delay=168&tel=1');
    
    $setParams = buildSetParams(URL, 'GET', $postParams, 'id');
    $message = goCurl($setParams, ASYNC);
    $telegram = setMessage($message);
    echo sendTelegram($telegram, PHP_EOL);
    
    Curl::run('http://' . REMOTE_HOST . '/telecontrollo/bot/telegram_REST.php?token=watchdog&tipologia=scarichi&host=1&move=0&variabile=ALL&metodo=array&tel=1');
    Curl::run('http://' . REMOTE_HOST . '/telecontrollo/bot/telegram_REST.php?token=watchdog&tipologia=scarichi&host=2&variabile=ALL&metodo=array&tel=1');
    
} catch (\Throwable $e) {
    Error::errorHandler($e, DEBUG_LEVEL, 'cli');
    exit();
}
