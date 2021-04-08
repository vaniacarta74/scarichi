<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi\tests\Unit;

use PHPUnit\Framework\TestCase;
use vaniacarta74\Scarichi\ServiceBuilder;

/**
 * Description of ServiceBuilderTest
 *
 * @author Vania
 */
class ServiceBuilderTest extends TestCase
{
    
    /**
     * @coversNothing
     */
    public function runProvider()
    {
        $data = [
            'url' => [
                'serParams' => [
                    'name' => TOCSVURL . '?var=30030&datefrom=01/01/2019&dateto=02/01/2019&full=1&field=volume'
                ],
                'multiParams' => null,
                'sendMode' => null,
                'expected' => '<b>' . TOCSVURL . '</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL
            ],
            'sync no params' => [
                'serParams' => [
                    'name' => 'telegram_REST',
                    'token' => 'sync',
                    'defaults' => [
                        'tel' => 'sendMode'
                    ],
                    'method' => 'Sync'
                ],
                'multiParams' => [],
                'sendMode' => false,
                'expected' => '<b>Procedura sincronizzazione banche dati</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL . '<b>Totali:</b> Records: 12.306 | Insert: 54 | Update: 0 | Presenti: 12.245 | Scartati: 7 | Cancellati: 0'
            ],
            'tocsv no params' => [
                'serParams' => [
                    'name' => 'tocsv',
                    'token' => null,
                    'defaults' => [],
                    'method' => 'ToCsv'
                ],
                'multiParams' => [],
                'sendMode' => false,
                'expected' => '<b>Procedura calcolo ed esportazione dati</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL . '<b>Totali:</b> n.d.'
            ],
            'watch no params' => [
                'serParams' => [
                    'name' => 'telegram_REST',
                    'token' => 'watchdog',
                    'defaults' => [
                        'tel' => 'sendMode'
                    ],
                    'method' => 'Watchdog'
                ],
                'multiParams' => [],
                'sendMode' => false,
                'expected' => '<b>Procedura caricamento dati</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL . '<b>Totali:</b> Records: 0 | Insert: 0 | Update: 0 | Presenti: 0 | Scartati: 0 | Cancellati: 0'
            ],
            'sync dummy params' => [
                'serParams' => [
                    'name' => 'telegram_REST',
                    'token' => 'sync',
                    'defaults' => [
                        'tel' => 'sendMode'
                    ],
                    'method' => 'Sync'
                ],
                'multiParams' => [
                    []
                ],
                'sendMode' => false,
                'expected' => '<b>Procedura sincronizzazione banche dati</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL . '<b>Totali:</b> Records: 12.306 | Insert: 54 | Update: 0 | Presenti: 12.245 | Scartati: 7 | Cancellati: 0'
            ],
            'sync single params' => [
                'serParams' => [
                    'name' => 'telegram_REST',
                    'token' => 'sync',
                    'defaults' => [
                        'tel' => 'sendMode'
                    ],
                    'method' => 'Sync'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => false,
                'expected' => '<b>Procedura sincronizzazione banche dati</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL . '<b>Totali:</b> Records: 1.195 | Insert: 11 | Update: 0 | Presenti: 1.178 | Scartati: 6 | Cancellati: 0'
            ],
            'tocsv single params' => [
                'serParams' => [
                    'name' => 'tocsv',
                    'token' => null,
                    'defaults' => [],
                    'method' => 'ToCsv'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => false,
                'expected' => '<b>Procedura calcolo ed esportazione dati</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL . '<b>Totali:</b> Esportati: 1'
            ],
            'watch single params' => [
                'serParams' => [
                    'name' => 'telegram_REST',
                    'token' => 'watchdog',
                    'defaults' => [
                        'tel' => 'sendMode'
                    ],
                    'method' => 'Watchdog'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => false,
                'expected' => '<b>Procedura caricamento dati</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL . '<b>Totali:</b> Records: 47 | Insert: 0 | Update: 0 | Presenti: 47 | Scartati: 0 | Cancellati: 0'
            ],
            'sync double date' => [
                'serParams' => [
                    'name' => 'telegram_REST',
                    'token' => 'sync',
                    'defaults' => [
                        'tel' => 'sendMode'
                    ],
                    'method' => 'Sync'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30030',
                        'datefrom' => '02/01/2019',
                        'dateto' => '03/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => false,
                'expected' => '<b>Procedura sincronizzazione banche dati</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL . '<b>Totali:</b> Records: 1.195 | Insert: 11 | Update: 0 | Presenti: 1.178 | Scartati: 6 | Cancellati: 0'
            ],
            'tocsv double date' => [
                'serParams' => [
                    'name' => 'tocsv',
                    'token' => null,
                    'defaults' => [],
                    'method' => 'ToCsv'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30030',
                        'datefrom' => '02/01/2019',
                        'dateto' => '03/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => false,
                'expected' => '<b>Procedura calcolo ed esportazione dati</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL . '<b>Totali:</b> Esportati: 2'
            ],
            'watch double date' => [
                'serParams' => [
                    'name' => 'telegram_REST',
                    'token' => 'watchdog',
                    'defaults' => [
                        'tel' => 'sendMode'
                    ],
                    'method' => 'Watchdog'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30030',
                        'datefrom' => '02/01/2019',
                        'dateto' => '03/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => false,
                'expected' => '<b>Procedura caricamento dati</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL . '<b>Totali:</b> Records: 47 | Insert: 0 | Update: 0 | Presenti: 47 | Scartati: 0 | Cancellati: 0'
            ],
            'sync multi params' => [
                'serParams' => [
                    'name' => 'telegram_REST',
                    'token' => 'sync',
                    'defaults' => [
                        'tel' => 'sendMode'
                    ],
                    'method' => 'Sync'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30040',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => false,
                'expected' => '<b>Procedura sincronizzazione banche dati</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL . '<b>Totali:</b> Records: 12.306 | Insert: 54 | Update: 0 | Presenti: 12.245 | Scartati: 7 | Cancellati: 0'
            ],
            'tocsv multi params' => [
                'serParams' => [
                    'name' => 'tocsv',
                    'token' => null,
                    'defaults' => [],
                    'method' => 'ToCsv'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30040',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => false,
                'expected' => '<b>Procedura calcolo ed esportazione dati</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL . '<b>Totali:</b> Esportati: 2'
            ],
            'watch multi params' => [
                'serParams' => [
                    'name' => 'telegram_REST',
                    'token' => 'watchdog',
                    'defaults' => [
                        'tel' => 'sendMode'
                    ],
                    'method' => 'Watchdog'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30040',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => false,
                'expected' => '<b>Procedura caricamento dati</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL . '<b>Totali:</b> Records: 0 | Insert: 0 | Update: 0 | Presenti: 0 | Scartati: 0 | Cancellati: 0'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group serviceBuilder
     * @covers \vaniacarta74\Scarichi\ServiceBuilder::run
     * @dataProvider runProvider     
     */
    public function testRunStringContainsString(array $serParams, ?array $multiParams, ?bool $sendMode, string $response)
    {
        $actual = ServiceBuilder::run($serParams, $multiParams, $sendMode);
        
        $expecteds = explode('|', $response);
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @coversNothing
     */
    public function runExceptionProvider()
    {
        $data = [            
            'wrong params' => [
                'serParams' => [
                    'pippo' => TOCSVURL . '?var=30030&datefrom=01/01/2019&dateto=02/01/2019&full=1&field=volume'
                ],
                'multiParams' => null,
                'sendMode' => null
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group serviceManager
     * @covers \vaniacarta74\Scarichi\ServiceBuilder::run
     * @dataProvider runExceptionProvider
     */
    public function testRunException(array $serParams, ?array $multiParams, ?bool $sendMode)
    {
        $this->expectException(\Exception::class);
        
        ServiceBuilder::run($serParams, $multiParams, $sendMode);
    }
    
    /**
     * @coversNothing
     */
    public function setParamsProvider()
    {
        $data = [
            'sync no params false' => [
                'serParams' => [
                    'name' => 'telegram_REST',
                    'token' => 'sync',
                    'defaults' => [
                        'tel' => 'sendMode'
                    ],
                    'method' => 'Sync'
                ],
                'multiParams' => [],
                'sendMode' => false,
                'expected' => [
                    [
                        'tel' => false
                    ]
                ]
            ],
            'sync no params true' => [
                'serParams' => [
                    'name' => 'telegram_REST',
                    'token' => 'sync',
                    'defaults' => [
                        'tel' => 'sendMode'
                    ],
                    'method' => 'Sync'
                ],
                'multiParams' => [],
                'sendMode' => true,
                'expected' => [
                    [
                        'tel' => true
                    ]
                ]
            ],
            'tocsv no params' => [
                'serParams' => [
                    'name' => 'tocsv',
                    'token' => null,
                    'defaults' => [],
                    'method' => 'ToCsv'
                ],
                'multiParams' => [],
                'sendMode' => false,
                'expected' => []
            ],
            'watch no params' => [
                'serParams' => [
                    'name' => 'telegram_REST',
                    'token' => 'watchdog',
                    'defaults' => [
                        'host' => 2,
                        'move' => 1,
                        'tel' => 'sendMode'
                    ],
                    'method' => 'Watchdog'
                ],
                'multiParams' => [],
                'sendMode' => false,
                'expected' => [
                    [
                        'host' => 2,
                        'move' => 1,
                        'tel' => false
                    ]
                ]
            ],
            'sync one params' => [
                'serParams' => [
                    'name' => 'telegram_REST',
                    'token' => 'sync',
                    'defaults' => [
                        'tel' => 'sendMode'
                    ],
                    'method' => 'Sync'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => true,
                'expected' => [
                    [
                        'tel' => true,
                        'variabile' => '30030',
                        'delay' => 48
                    ]
                ]
            ],
            'tocsv one params' => [
                'serParams' => [
                    'name' => 'tocsv',
                    'token' => null,
                    'defaults' => [],
                    'method' => 'ToCsv'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => false,
                'expected' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ]
            ],
            'watch one params' => [
                'serParams' => [
                    'name' => 'telegram_REST',
                    'token' => 'watchdog',
                    'defaults' => [
                        'host' => 2,
                        'move' => 1,
                        'tel' => 'sendMode'
                    ],
                    'method' => 'Watchdog'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => false,
                'expected' => [
                    [
                        'host' => 2,
                        'move' => 1,
                        'tel' => false,
                        'variabile' => '30030'
                    ]
                ]
            ],
            'sync multi date' => [
                'serParams' => [
                    'name' => 'telegram_REST',
                    'token' => 'sync',
                    'defaults' => [
                        'tel' => 'sendMode'
                    ],
                    'method' => 'Sync'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30030',
                        'datefrom' => '02/01/2019',
                        'dateto' => '03/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => true,
                'expected' => [
                    [
                        'tel' => true,
                        'variabile' => '30030',
                        'delay' => 72
                    ]
                ]
            ],
            'tocsv multi date' => [
                'serParams' => [
                    'name' => 'tocsv',
                    'token' => null,
                    'defaults' => [],
                    'method' => 'ToCsv'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30030',
                        'datefrom' => '02/01/2019',
                        'dateto' => '03/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => false,
                'expected' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30030',
                        'datefrom' => '02/01/2019',
                        'dateto' => '03/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ]
            ],
            'watch multi date' => [
                'serParams' => [
                    'name' => 'telegram_REST',
                    'token' => 'watchdog',
                    'defaults' => [
                        'host' => 2,
                        'move' => 1,
                        'tel' => 'sendMode'
                    ],
                    'method' => 'Watchdog'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30030',
                        'datefrom' => '02/01/2019',
                        'dateto' => '03/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => false,
                'expected' => [
                    [
                        'host' => 2,
                        'move' => 1,
                        'tel' => false,
                        'variabile' => '30030'
                    ]
                ]
            ],
            'sync multi params' => [
                'serParams' => [
                    'name' => 'telegram_REST',
                    'token' => 'sync',
                    'defaults' => [
                        'tel' => 'sendMode'
                    ],
                    'method' => 'Sync'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30040',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => true,
                'expected' => [
                    [
                        'tel' => true
                    ]
                ]
            ],
            'tocsv multi params' => [
                'serParams' => [
                    'name' => 'tocsv',
                    'token' => null,
                    'defaults' => [],
                    'method' => 'ToCsv'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30040',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => false,
                'expected' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30040',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ]
            ],
            'watch multi params' => [
                'serParams' => [
                    'name' => 'telegram_REST',
                    'token' => 'watchdog',
                    'defaults' => [
                        'host' => 2,
                        'move' => 1,
                        'tel' => 'sendMode'
                    ],
                    'method' => 'Watchdog'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30040',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => false,
                'expected' => [
                    [
                        'host' => 2,
                        'move' => 1,
                        'tel' => false
                    ]
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group serviceBuilder
     * @covers \vaniacarta74\Scarichi\ServiceBuilder::setParams
     * @dataProvider setParamsProvider     
     */
    public function testSetParamsEquals(array $serParams, ?array $multiParams, ?bool $sendMode, array $expected)
    {
        $actual = ServiceBuilder::setParams($serParams, $multiParams, $sendMode);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function setParamsExceptionProvider()
    {
        $data = [            
            'no method' => [
                'serParams' => [
                    'name' => 'telegram_REST',
                    'token' => 'sync',
                    'defaults' => [
                        'tel' => 'sendMode'
                    ],
                    'pippo' => 'Sync'
                ],
                'multiParams' => [],
                'sendMode' => false
            ],
            'no default' => [
                'serParams' => [
                    'name' => 'telegram_REST',
                    'token' => 'sync',
                    'pippo' => [
                        'tel' => 'sendMode'
                    ],
                    'method' => 'Sync'
                ],
                'multiParams' => [],
                'sendMode' => false
            ],
            'method wrong' => [
                'serParams' => [
                    'name' => 'telegram_REST',
                    'token' => 'sync',
                    'defaults' => [
                        'tel' => 'sendMode'
                    ],
                    'method' => 'Pippo'
                ],
                'multiParams' => [],
                'sendMode' => false
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group serviceBuilder
     * @covers \vaniacarta74\Scarichi\ServiceBuilder::setParams
     * @dataProvider setParamsExceptionProvider
     */
    public function testSetParamsException(array $serParams, ?array $multiParams, ?bool $sendMode)
    {
        $this->expectException(\Exception::class);
        
        ServiceBuilder::setParams($serParams, $multiParams, $sendMode);
    }
    
    /**
     * @coversNothing
     */
    public function setSyncParamsProvider()
    {
        $data = [
            'sync no params false' => [
                'defaults' => [
                    'tel' => 'sendMode'
                ],
                'multiParams' => [],
                'sendMode' => false,
                'expected' => [
                    [
                        'tel' => false
                    ]
                ]
            ],
            'sync no params true' => [
                'defaults' => [
                    'tel' => 'sendMode'
                ],
                'multiParams' => [],
                'sendMode' => true,
                'expected' => [
                    [
                        'tel' => true
                    ]
                ]
            ],            
            'sync one params' => [
                'defaults' => [
                    'tel' => 'sendMode'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => true,
                'expected' => [
                    [
                        'tel' => true,
                        'variabile' => '30030',
                        'delay' => 48
                    ]
                ]
            ],
            'sync multi date' => [
                'defaults' => [
                    'tel' => 'sendMode'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30030',
                        'datefrom' => '02/01/2019',
                        'dateto' => '03/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => true,
                'expected' => [
                    [
                        'tel' => true,
                        'variabile' => '30030',
                        'delay' => 72
                    ]
                ]
            ],
            'sync multi params' => [
                'defaults' => [
                    'tel' => 'sendMode'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30040',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => true,
                'expected' => [
                    [
                        'tel' => true
                    ]
                ]
            ],
            'sync no defaults no multi' => [
                'defaults' => [],
                'multiParams' => [],
                'sendMode' => false,
                'expected' => [
                    []
                ]
            ],
            'sync no defaults no multi' => [
                'defaults' => [],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => false,
                'expected' => [
                    [
                        'variabile' => '30030',
                        'delay' => 48
                    ]
                ]
            ],
            'sync multi no defaults' => [
                'defaults' => [],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30040',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => true,
                'expected' => [
                    []
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group serviceBuilder
     * @covers \vaniacarta74\Scarichi\ServiceBuilder::setSyncParams
     * @dataProvider setSyncParamsProvider     
     */
    public function testSetSyncParamsEquals(array $defaults, ?array $multiParams, ?bool $sendMode, array $expected)
    {
        $actual = ServiceBuilder::setSyncParams($defaults, $multiParams, $sendMode);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function setToCsvParamsProvider()
    {
        $data = [
            'tocsv no params false' => [
                'defaults' => [],
                'multiParams' => [],
                'sendMode' => false,
                'expected' => []
            ],
            'tocsv one params' => [
                'defaults' => [],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => true,
                'expected' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ]
            ],
            'tocsv multi date' => [
                'defaults' => [],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30030',
                        'datefrom' => '02/01/2019',
                        'dateto' => '03/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => true,
                'expected' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30030',
                        'datefrom' => '02/01/2019',
                        'dateto' => '03/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ]
            ],
            'tocsv multi params' => [
                'defaults' => [],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30040',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => true,
                'expected' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30040',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group serviceBuilder
     * @covers \vaniacarta74\Scarichi\ServiceBuilder::setToCsvParams
     * @dataProvider setToCsvParamsProvider     
     */
    public function testSetToCsvParamsEquals(array $defaults, ?array $multiParams, ?bool $sendMode, array $expected)
    {
        $actual = ServiceBuilder::setToCsvParams($defaults, $multiParams, $sendMode);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function setWatchdogParamsProvider()
    {
        $data = [
            'watch no params false' => [
                'defaults' => [
                    'host' => 2,
                    'move' => 1,
                    'tel' => 'sendMode'
                ],
                'multiParams' => [],
                'sendMode' => false,
                'expected' => [
                    [
                        'host' => 2,
                        'move' => 1,
                        'tel' => false
                    ]
                ]
            ],
            'watch one params' => [
                'defaults' => [
                    'host' => 2,
                    'move' => 1,
                    'tel' => 'sendMode'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => false,
                'expected' => [
                    [
                        'host' => 2,
                        'move' => 1,
                        'tel' => false,
                        'variabile' => '30030'
                    ]
                ]
            ],
            'watch multi date' => [
                'defaults' => [
                    'host' => 2,
                    'move' => 1,
                    'tel' => 'sendMode'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30030',
                        'datefrom' => '02/01/2019',
                        'dateto' => '03/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => false,
                'expected' => [
                    [
                        'host' => 2,
                        'move' => 1,
                        'tel' => false,
                        'variabile' => '30030'
                    ]
                ]
            ],
            'watch multi params' => [
                'defaults' => [
                    'host' => 2,
                    'move' => 1,
                    'tel' => 'sendMode'
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30040',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'sendMode' => false,
                'expected' => [
                    [
                        'host' => 2,
                        'move' => 1,
                        'tel' => false
                    ]
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group serviceBuilder
     * @covers \vaniacarta74\Scarichi\ServiceBuilder::setWatchdogParams
     * @dataProvider setWatchdogParamsProvider     
     */
    public function testSetWatchdogParamsEquals(array $defaults, ?array $multiParams, ?bool $sendMode, array $expected)
    {
        $actual = ServiceBuilder::setWatchdogParams($defaults, $multiParams, $sendMode);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function setDefaultsProvider()
    {
        $data = [
            'sync' => [
                'params' => [],
                'defaults' => [
                    'tel' => 'sendMode'
                ],
                'sendMode' => false,
                'expected' => [
                    [
                        'tel' => false
                    ]
                ]
            ],
            'no default' => [
                'params' => [],
                'defaults' => [],
                'sendMode' => false,
                'expected' => [
                    []
                ]
            ],
            'watchdog' => [
                'params' => [],
                'defaults' => [
                    'host' => 2,
                    'move' => 1,
                    'tel' => 'sendMode'
                ],
                'sendMode' => true,
                'expected' => [
                    [
                        'host' => 2,
                        'move' => 1,
                        'tel' => true
                    ]
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group serviceBuilder
     * @covers \vaniacarta74\Scarichi\ServiceBuilder::setDefaults
     * @dataProvider setDefaultsProvider     
     */
    public function testSetDefaultsEquals(array $params, array $defaults, bool $sendMode, array $expected)
    {
        ServiceBuilder::setDefaults($params, $defaults, $sendMode);
        
        $this->assertEquals($expected, $params);
    }
    
    /**
     * @coversNothing
     */
    public function appendVarProvider()
    {
        $data = [
            'no multi' => [
                'params' => [
                    [
                        'tel' => false
                    ]
                ],
                'multiParams' => [],
                'expected' => [
                    [
                        'tel' => false
                    ]
                ],
                'expected2' => false 
                
            ],
            'single multi' => [
                'params' => [
                    [
                        'tel' => false
                    ]
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'expected' => [
                    [
                        'tel' => false,
                        'variabile' => '30030'
                    ]
                ],
                'expected2' => true 
            ],
            'multi date' => [
                'params' => [
                    [
                        'tel' => false
                    ]
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30030',
                        'datefrom' => '02/01/2019',
                        'dateto' => '03/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'expected' => [
                    [
                        'tel' => false,
                        'variabile' => '30030'
                    ]
                ],
                'expected2' => true 
            ],
            'multi params' => [
                'params' => [
                    [
                        'tel' => false
                    ]
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30040',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'expected' => [
                    [
                        'tel' => false
                    ]
                ],
                'expected2' => false 
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group serviceBuilder
     * @covers \vaniacarta74\Scarichi\ServiceBuilder::appendVar
     * @dataProvider appendVarProvider     
     */
    public function testAppendVarEquals(array $params, array $multiParams, array $expected, bool $expected2)
    {
        $actual = ServiceBuilder::appendVar($params, $multiParams);
        
        $this->assertEquals($expected, $params);
        
        $this->assertEquals($expected2, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function setDelayProvider()
    {
        $data = [            
            'single multi' => [
                'params' => [
                    [
                        'tel' => false,
                        'variabile' => '30030'
                    ]
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'expected' => [
                    [
                        'tel' => false,
                        'variabile' => '30030',
                        'delay' => 48
                    ]
                ] 
            ],
            'multi date' => [
                'params' => [
                    [
                        'tel' => false,
                        'variabile' => '30030'
                    ]
                ],
                'multiParams' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ],
                    [
                        'id' => '1',
                        'var' => '30030',
                        'datefrom' => '02/01/2019',
                        'dateto' => '03/01/2019',
                        'full' => '1',
                        'field' => 'volume'
                    ]
                ],
                'expected' => [
                    [
                        'tel' => false,
                        'variabile' => '30030',
                        'delay' => 72
                    ]
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group serviceBuilder
     * @covers \vaniacarta74\Scarichi\ServiceBuilder::setDelay
     * @dataProvider setDelayProvider     
     */
    public function testSetDelayEquals(array $params, array $multiParams, array $expected)
    {
        ServiceBuilder::setDelay($params, $multiParams);
        
        $this->assertEquals($expected, $params);
    }
    
    /**
     * @coversNothing
     */
    public function getDelayProvider()
    {
        $data = [            
            'standard' => [
                'datefrom' => '01/01/2019',
                'dateto' => '02/01/2019',
                'deltaH' => null,
                'expected' => 24
            ],
            'with delta' => [
                'datefrom' => '01/01/2019',
                'dateto' => '02/01/2019',
                'deltaH' => 24,
                'expected' => 48
            ],
            'inverted' => [
                'datefrom' => '02/01/2019',
                'dateto' => '01/01/2019',
                'deltaH' => 24,
                'expected' => 48
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group serviceBuilder
     * @covers \vaniacarta74\Scarichi\ServiceBuilder::getDelay
     * @dataProvider getDelayProvider     
     */
    public function testGetDelayEquals(string $datefrom, string $dateto, ?int $delay, int $expected)
    {
        $actual = ServiceBuilder::getDelay($datefrom, $dateto, $delay);
        
        $this->assertEquals($expected, $actual);
    }
}
