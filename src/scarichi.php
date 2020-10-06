<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace vaniacarta74\Scarichi;

require __DIR__ . '/../vendor/autoload.php';

//$argc = 7;
//$argv = ['scarichi.php', '-V', '30030,30040,30050', '-f', '-t', '-c', '-n'];

try {
    if (!isset($argv)) {
        $message = getMessage('www', 'redirect');
    } else {
        if ($argc === 1 || ($argc === 2 && ($argv[1] === '-h' || $argv[1] === '--help'))) {
            $message = getMessage('cli', 'help'); 
        } elseif ($argc === 2 && ($argv[1] === '-v' || $argv[1] === '--version')) {
            $message = getMessage('cli', 'version');
        } elseif ($argc === 2 && ($argv[1] === '-d' || $argv[1] === '--default')) {
            $message = getMessage('cli', 'default');
            
            $now = new \DateTime();
            $yearInt = new \DateInterval('P1Y');
            $dateTo = $now->format('d/m/Y');
            $lastYear = $now->sub($yearInt);
            $dateFrom = $lastYear->format('d/m/Y');            
            
            $paramsRaw = [
                'var' => 'ALL',
                'datefrom' => $dateFrom,
                'dateto' => $dateTo,
                'full' => '1',
                'field' => 'volume'
            ];            
            
        } elseif ($argc >= 6 && in_array('-V', $argv) && in_array('-f', $argv) && in_array('-t', $argv) && in_array('-c', $argv) && in_array('-n', $argv)) {
            $message = getMessage('cli', 'ok');

            $variables = setVariables($argv);
            foreach ($variables as $key => $value) {
                echo $key . ': ' . $value . PHP_EOL;
            }
        } else {
            $message = getMessage('cli', 'error');            
        }
    }
    echo $message;
    foreach ($argv as $key => $value) {
        echo $key . ': ' . $value . PHP_EOL;
    }    
} catch (\Throwable $e) {
    Utility::errorHandler($e);
    exit();
}