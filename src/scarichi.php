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
//$argv = ['scarichi.php', '-V', '30030,30040', '-f', '01/01/2019', '-t', '02/01/2019', '-c', '-n'];

try {
    $composer = COMPOSER;
    $help = CONFIG;
    $parameters = $help['parameters'];
    $sendMode = !GLOBALMSG && TELEGRAM;
    
    $type = shuntTypes($parameters, $argv);
    echo getMessage($composer, $help, $type);
    $values = setParameters($parameters, $argv, $type);
    
    $filledValues = fillParameters($parameters, $values);
    $limitedValues = limitDates($filledValues, PERIOD, OFFSET);
    $csvParams = setPostParameters($parameters, $limitedValues);
    
    $syncService = New ServiceManager('telegram_REST', 'sync', [['tel' => $sendMode]]);
    $messages['sync'] = $syncService->getMessage();
    
    $csvService = New ServiceManager('tocsv', null, $csvParams);
    $messages['iscsv'] = $csvService->getMessage(); 
    
    $watchService1 = New ServiceManager('telegram_REST', 'watchdog', [['tel' => $sendMode]]);
    $messages['watch1'] = $watchService1->getMessage();
    
    $watchService2 = New ServiceManager('telegram_REST', 'watchdog', [['host' => 2,'move' => 1,'tel' => $sendMode]]);
    $messages['watch2'] = $watchService2->getMessage();
    
    echo sendMessages($messages);
} catch (\Throwable $e) {
    Error::errorHandler($e, DEBUG_LEVEL, 'cli');
    exit();
}
