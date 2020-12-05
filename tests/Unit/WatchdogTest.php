<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi\tests\Unit;

use PHPUnit\Framework\TestCase;
use vaniacarta74\Scarichi\Curl;

/**
 * Description of CurlTest
 *
 * @author Vania
 */
class WatchdogTest extends TestCase
{

    /**
     * coversNothing
     */
    public function watchdogCliProvider() : array
    {
        $dateTime = new \DateTime('NOW', new \DateTimeZone('Europe/Rome'));
        $when = $dateTime->format('d/m/y h');
        
        $data = [
//Attivare solo una tantum: troppo lungo            
//            'standard' => [
//                'path' => '/var/www/html/telecontrollo/scarichi/github/src/watchdog.php',
//                'n' => null,
//                'delay' => null,
//                'url' => null,
//                'expected' => 'PID 1 ' . $when
//            ],
            'iteration' => [
                'path' => '/var/www/html/telecontrollo/scarichi/github/src/watchdog.php',
                'n' => 1,
                'delay' => null,
                'url' => null,
                'expected' => 'PID 1 ' . $when
            ],
            'delay' => [
                'path' => '/var/www/html/telecontrollo/scarichi/github/src/watchdog.php',
                'n' => 1,
                'delay' => 1000,
                'url' => null,
                'expected' => 'PID 1 ' . $when
            ],
            'json' => [
                'path' => '/var/www/html/telecontrollo/scarichi/github/src/watchdog.php',
                'n' => 1,
                'delay' => 1000,
                'url' => 'http://localhost/scarichi/telegram.php',
                'expected' => 'PID 1 ' . $when
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group watchdog
     * covers watchdog.php
     * @dataProvider watchdogCliProvider
     */
    public function testWatchdogCliEquals(string $path, ?int $n, ?int $delay, ?string $url = null, string $expected) : void
    {
        $command = 'php ' . $path . ' ' . $n . ' ' . $delay . ' ' . $url;
        
        $actual = shell_exec($command);
            
        $this->assertStringContainsString($expected, $actual);
    }
    
    /**
     * coversNothing
     */
    public function watchdogUrlProvider() : array
    {
        $dateTime = new \DateTime('NOW', new \DateTimeZone('Europe/Rome'));
        $when = $dateTime->format('d/m/y h');
        
        $data = [
            'standard' => [
                'url' => 'http://localhost/scarichi/watchdog.php?n=1&delay=1000000',
                'expected' => 'PID 1 ' . $when
            ],
            'json' => [
                'url' => 'http://localhost/scarichi/watchdog.php?n=1&delay=1000000&url=http://localhost/scarichi/telegram.php',
                'expected' => 'PID 1 ' . $when
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group watchdog
     * covers watchdog.php
     * @dataProvider watchdogUrlProvider
     */
    public function testWatchdogUrlEquals(?string $url, string $expected) : void
    {
        $actual = Curl::run($url);
        
        $this->assertStringContainsString($expected, $actual);
    }
}

