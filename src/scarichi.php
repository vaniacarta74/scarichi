<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace vaniacarta74\Scarichi;

require __DIR__ . '/../vendor/autoload.php';

try {
    if (!isset($argv)) {
        echo getMessage('www', 'redirect');
    } else {
        if ($argc === 1 || ($argc === 2 && ($argv[1] === '-h' || $argv[1] === '--help'))) {
            echo getMessage('cli', 'help'); 
        } elseif ($argc === 2 && ($argv[1] === '-v' || $argv[1] === '--version')) {
            echo getMessage('cli', 'version');
        } elseif ($argc === 2 && ($argv[1] === '-d' || $argv[1] === '--default')) {
            echo getMessage('cli', 'default');
            
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
            
            foreach ($paramsRaw as $key => $value) {
                echo $key . ': ' . $value . PHP_EOL;
            }
        } else {
            foreach ($argv as $key => $value) {
                echo $key . ': ' . $value . PHP_EOL;
            }
        }
    }
} catch (Throwable $e) {
    echo errorHandler($e);
    exit();
}