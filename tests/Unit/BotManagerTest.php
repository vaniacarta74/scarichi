<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi\tests\Unit;

use PHPUnit\Framework\TestCase;
use vaniacarta74\Scarichi\BotManager;
use vaniacarta74\Scarichi\Bot;
use vaniacarta74\Scarichi\Utility;
use vaniacarta74\Scarichi\tests\classes\Reflections;

/**
 * Description of BotManagerTest
 *
 * @author Vania
 */
class BotManagerTest extends TestCase
{
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\BotManager::load     
     */
    public function testLoadEquals() : array
    {
        $path = '/var/www/html/telecontrollo/scarichi/github/tests/providers/telegramTest.json';
        $expected = [
            'bots' => [
                [
                    'botName' => 'BotScarichi',
                    'userName' => 'ScarichiBot',
                    'token' => 'pippo',
                    'offset' => 0,
                    'commands' => [
                        'volume'
                    ],
                    'chats' => [1,2,3]                    
                ],
                [
                    'botName' => 'BotTest',
                    'userName' => 'TestBot',
                    'token' => 'pluto',
                    'offset' => 0,
                    'commands' => [
                        'pluto'
                    ],
                    'chats' => [1,2,3]
                ]
            ]
        ];
        
        $actual = BotManager::load($path);
        
        $this->assertEquals($expected, $actual);
        
        return $actual;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\BotManager::exec
     */
    public function testExecEquals() : void
    {
        $arrBots = [
            'bots' => [
                [
                    'botName' => 'BotScarichi',
                    'userName' => 'ScarichiBot',
                    'token' => TOKEN,
                    'offset' => 0,
                    'commands' => [
                        'volume'
                    ],
                    'chats' => [1,2,3]                    
                ],
                [
                    'botName' => 'BotPippo',
                    'userName' => 'ScarichiBot',
                    'token' => TOKEN,
                    'offset' => 0,
                    'commands' => [
                        'pluto'
                    ],
                    'chats' => [4,5,6]                    
                ]
            ]
        ];
        $expected = [
            'bots' => [
                [
                    'botName' => 'BotScarichi',
                    'userName' => 'ScarichiBot',
                    'token' => TOKEN,
                    'offset' => 0,
                    'commands' => [
                        'volume'
                    ],
                    'chats' => [1,2,3]                    
                ],
                [
                    'botName' => 'BotPippo',
                    'userName' => 'ScarichiBot',
                    'token' => TOKEN,
                    'offset' => 0,
                    'commands' => [
                        'pluto'
                    ],
                    'chats' => [4,5,6]                    
                ]
            ],
            'results' => [
                [
                    'bot' => 'BotScarichi',
                    'ok' => true
                ],
                [
                    'bot' => 'BotPippo',
                    'ok' => true
                ]
            ]
        ];
        
        $actual = BotManager::exec($arrBots);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\BotManager::exec
     */
    public function testExecException() : void
    {
        $arrBots = [
            'pippo' => [],
            'pluto' => []
        ];
        
        $this->expectException(\Exception::class);
        
        BotManager::exec($arrBots);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\BotManager::update
     */
    public function testUpdateEquals() : void
    {
        $path = '/var/www/html/telecontrollo/scarichi/github/tests/providers/telegramTest.json';
        $result = [
            'bots' => [
                [
                    'botName' => 'BotScarichi',
                    'userName' => 'ScarichiBot',
                    'token' => 'pippo',
                    'offset' => 0,
                    'commands' => [
                        'volume'
                    ],
                    'chats' => [1,2,3]                    
                ],
                [
                    'botName' => 'BotTest',
                    'userName' => 'TestBot',
                    'token' => 'pluto',
                    'offset' => 0,
                    'commands' => [
                        'pluto'
                    ],
                    'chats' => [1,2,3]
                ]
            ]
        ];
        $expected = true;
        
        $actual = BotManager::update($result, $path);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\BotManager::update
     */
    public function testUpdateException() : void
    {
        $path = '/var/www/html/telecontrollo/scarichi/github/tests/providers/telegramTest.json';
        $result = [
            'pluto' => []
        ];
        
        $this->expectException(\Exception::class);
        
        BotManager::update($result, $path);
    }
    
    /**
     * @coversNothing
     */
    public function printProvider() : array
    {
        $data = [
            'ok' => [
                'result' => [
                    'results' => [
                        [
                            'bot' => 'BotScarichi',
                            'ok' => true
                        ],
                        [
                            'bot' => 'BotPippo',
                            'ok' => false
                        ]
                    ]
                ],
                'isOk' => true,
                'expected' => '<b>BotScarichi</b> is ok: <b>true</b>.<br/>' . PHP_EOL . '<b>BotPippo</b> is ok: <b>false</b>.<br/>' . PHP_EOL . 'Registrazione offset avvenuta con successo.<br/>' . PHP_EOL . 'Tempo di elaborazione: <b>'
            ]
        ];        
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\BotManager::print
     * @dataProvider printProvider
     */
    public function testPrintContainsString(array $result, bool $isOk, string $expected) : void
    {
        $actual = BotManager::print($result, $isOk);
        
        $this->assertStringContainsString($expected, $actual);
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\BotManager::print
     */
    public function testPrintKeyException() : void
    {        
        $result = [
            'results' => [
                [
                    'bot' => 'BotPippo',
                    'pippo' => false
                ]
            ] 
        ];
        $isOk = true;
        
        $this->expectException(\Exception::class);
        
        BotManager::print($result, $isOk);
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\BotManager::print
     */
    public function testPrintResultsKeyException() : void
    {        
        $result = [
            'pippo' => [] 
        ];
        $isOk = true;
        
        $this->expectException(\Exception::class);
        
        BotManager::print($result, $isOk);
    }
}
