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
class TelegramTest extends TestCase
{

    /**
     * coversNothing
     */
    public function telegramCliProvider() : array
    {
        $data = [
            'standard' => [
                'path' => '/var/www/html/telecontrollo/scarichi/github/src/telegram.php',
                'arg' => null,
                'expected' => '<b>BotScarichi</b> is ok: <b>true</b>.<br/>' . PHP_EOL . 'Registrazione offset avvenuta con successo.<br/>' . PHP_EOL . 'Tempo di elaborazione: <b>'
            ],
            'json' => [
                'path' => '/var/www/html/telecontrollo/scarichi/github/src/telegram.php',
                'arg' => '/var/www/html/telecontrollo/scarichi/telegram.json',
                'expected' => '<b>BotScarichi</b> is ok: <b>true</b>.<br/>' . PHP_EOL . 'Registrazione offset avvenuta con successo.<br/>' . PHP_EOL . 'Tempo di elaborazione: <b>'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group telegram
     * covers telegram.php
     * @dataProvider telegramCliProvider
     */
    public function testTelegramCliEquals(string $path, ?string $arg = null, string $expected) : void
    {
        $command = 'php ' . $path . ' ' . $arg;
        
        $actual = shell_exec($command);
            
        $this->assertStringContainsString($expected, $actual);
    }
    
    /**
     * coversNothing
     */
    public function telegramUrlProvider() : array
    {
        $data = [
            'standard' => [
                'url' => 'http://localhost/scarichi/telegram.php',
                'expected' => '<b>BotScarichi</b> is ok: <b>true</b>.<br/>' . PHP_EOL . 'Registrazione offset avvenuta con successo.<br/>' . PHP_EOL . 'Tempo di elaborazione: <b>'
            ],
            'json' => [
                'url' => 'http://localhost/scarichi/telegram.php?path=/var/www/html/telecontrollo/scarichi/telegram.json',
                'expected' => '<b>BotScarichi</b> is ok: <b>true</b>.<br/>' . PHP_EOL . 'Registrazione offset avvenuta con successo.<br/>' . PHP_EOL . 'Tempo di elaborazione: <b>'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group telegram
     * covers telegram.php
     * @dataProvider telegramUrlProvider
     */
    public function testTelegramUrlEquals(?string $url, string $expected) : void
    {
        $actual = Curl::run($url);
        
        $this->assertStringContainsString($expected, $actual);
    }
}
