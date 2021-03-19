<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi\tests\Unit;

use PHPUnit\Framework\TestCase;
use vaniacarta74\Scarichi\ServiceManager;
use vaniacarta74\Scarichi\Utility;
use vaniacarta74\Scarichi\tests\classes\Reflections;

/**
 * Description of ServiceManagerTest
 *
 * @author Vania
 */
class ServiceManagerTest  extends TestCase
{    
    private $serviceManager;
    
    protected function setUp() : void
    {
        $params = [
            [
                'var' => '30030',
                'datefrom' => '01/01/2018',
                'dateto' => '02/01/2018'
            ]
        ];
        
        $this->serviceManager = new ServiceManager('tocsv', null, $params);
    }
    
    protected function tearDown() : void
    {
        $this->serviceManager = null;
    }
    
    /**
     * @coversNothing
     */
    public function constructorProvider() : array
    {
        $data = [
            'tocsv no params error' => [
                'args' => [
                    'service' => 'tocsv',
                    'token' => null,
                    'postParams' => null,
                    'config' => null
                ],
                'expected1' => [
                    'service' => 'tocsv',
                    'token' => 'tocsv',
                    'params' => [
                        [
                            'url' => 'http://localhost/scarichi/tocsv.php?full=1&field=volume',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => null,
                            'key' => null
                        ]
                    ]
                ],
                'expected2' => [
                    'responses' => [
                        '{"ok":false,"codice errore":400,"descrizione errore":"|"}'
                    ]
                ],
                'expected3' => [
                    'message' => '<b>Procedura calcolo ed esportazione dati</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL . '<b>Totali:</b> n.d.',
                ],
                'expected4' => [
                    'echos' => [
                        '1) PID 0: Elaborazione fallita.'
                    ]
                ]
            ],
            'tocsv all params' => [
                'args' => [
                    'service' => 'tocsv',
                    'token' => null,
                    'postParams' => [
                        [
                            'var' => '30030',
                            'datefrom' => '01/01/2019',
                            'dateto' => '02/01/2019',
                            'full' => '1',
                            'field' => 'volume'
                        ]
                    ],
                    'config' => null
                ],
                'expected1' => [
                    'service' => 'tocsv',
                    'token' => 'tocsv',
                    'params' => [
                        [
                            'url' => 'http://localhost/scarichi/tocsv.php?var=30030&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019&full=1&field=volume',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => null
                        ]
                    ]
                ],
                'expected2' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b>"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}'
                    ]
                ],
                'expected3' => [
                    'message' => '<b>Procedura calcolo ed esportazione dati</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL . '<b>Totali:</b> Esportati: ',
                ],
                'expected4' => [
                    'echos' => [
                        '1) PID 0: Elaborazione dati Volume variabile 30030 dal 01/01/2019 al 02/01/2019 avvenuta con successo in | sec. File CSV full esportati: |'
                    ]
                ]
            ],
            'tocsv id params' => [
                'args' => [
                    'service' => 'tocsv',
                    'token' => null,
                    'postParams' => [
                        [
                            'id' => '0',
                            'var' => '30030',
                            'datefrom' => '01/01/2019',
                            'dateto' => '02/01/2019',
                            'full' => '1',
                            'field' => 'volume'
                        ]
                    ],
                    'config' => null
                ],
                'expected1' => [
                    'service' => 'tocsv',
                    'token' => 'tocsv',
                    'params' => [
                        [
                            'url' => 'http://localhost/scarichi/tocsv.php?var=30030&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019&full=1&field=volume',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => '0'
                        ]
                    ]
                ],
                'expected2' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b>"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}'
                    ]
                ],
                'expected3' => [
                    'message' => '<b>Procedura calcolo ed esportazione dati</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL . '<b>Totali:</b> Esportati: ',
                ],
                'expected4' => [
                    'echos' => [
                        '1) PID 0: Elaborazione dati Volume variabile 30030 dal 01/01/2019 al 02/01/2019 avvenuta con successo in | sec. File CSV full esportati: |'
                    ]
                ]
            ],
            'tocsv multi params' => [
                'args' => [
                    'service' => 'tocsv',
                    'token' => null,
                    'postParams' => [
                        [
                            'id' => '0',
                            'var' => '30040',
                            'datefrom' => '01/01/2019',
                            'dateto' => '02/01/2019',
                            'full' => '1',
                            'field' => 'volume'
                        ],
                        [
                            'id' => '1',
                            'var' => '30030',
                            'datefrom' => '01/01/2019',
                            'dateto' => '02/01/2019',
                            'full' => '1',
                            'field' => 'volume'
                        ]
                    ],
                    'config' => null
                ],
                'expected1' => [
                    'service' => 'tocsv',
                    'token' => 'tocsv',
                    'params' => [
                        [
                            'url' => 'http://localhost/scarichi/tocsv.php?var=30040&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019&full=1&field=volume',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => '0'
                        ],
                        [
                            'url' => 'http://localhost/scarichi/tocsv.php?var=30030&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019&full=1&field=volume',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => '1'
                        ]
                        
                    ]
                ],
                'expected2' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"var":"30040","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b>"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}',
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30040<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b>"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}'
                    ]
                ],
                'expected3' => [
                    'message' => '<b>Procedura calcolo ed esportazione dati</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL . '<b>Totali:</b> Esportati: ',
                ],
                'expected4' => [
                    'echos' => [
                        ') PID 0: Elaborazione dati Volume variabile 30040 dal 01/01/2019 al 02/01/2019 avvenuta con successo in | sec. File CSV full esportati: |',
                        ') PID 1: Elaborazione dati Volume variabile 30030 dal 01/01/2019 al 02/01/2019 avvenuta con successo in | sec. File CSV full esportati: |'
                    ]
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager
     * @dataProvider constructorProvider
     */
    public function testConstructorEquals(array $args, array $expected1, array $expected2, array $expected3, array $expected4) : void
    {
        Reflections::invokeConstructor($this->serviceManager, $args);
        
        $actual1['service'] = Reflections::getProperty($this->serviceManager, 'service');
        $actual1['token'] = Reflections::getProperty($this->serviceManager, 'token');
        $actual1['params'] = Reflections::getProperty($this->serviceManager, 'params');
        $actual2 = Reflections::getProperty($this->serviceManager, 'responses');
        $actual3 = Reflections::getProperty($this->serviceManager, 'message');
        $start = Reflections::getProperty($this->serviceManager, 'start');
                
        $this->assertEquals($expected1, $actual1);
        
        $actual2All = implode('', $actual2);
        foreach ($expected2['responses'] as $response) {    
            $expecteds = explode('|', $response);    
            foreach ($expecteds as $expected) {
                $this->assertStringContainsString($expected, $actual2All);
            }
        }
        
        $expecteds = explode('|', $expected3['message']);    
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual3);
        }
        
        $actual4 = $this->getActualOutput();
        foreach ($expected4['echos'] as $echo) {
            $expecteds = explode('|', $echo);        
            foreach ($expecteds as $expected) {
                $this->assertStringContainsString($expected, $actual4);
            }
        }
        
        $this->assertRegExp('/^[0-9]{4}[-][0-9]{2}[-][0-9]{2}[\s][0-9]{2}[:][0-9]{2}[:][0-9]{2}[\.][0-9]{6}$/', $start);
    }
    
    /**
     * @coversNothing
     */
    public function constructorExceptionProvider()
    {
        $data = [
            'wrong service' => [
                'args' => [
                    'service' => 'pippo',
                    'token' => null,
                    'params' => null
                ]    
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::__construct
     * @dataProvider constructorExceptionProvider
     */
    public function ConstructorException($args)
    {
        $this->expectException(\Exception::class);
        
        Reflections::invokeConstructor($this->serviceManager, $args);
    }
    
}
