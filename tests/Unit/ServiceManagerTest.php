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
        
        $this->serviceManager = new ServiceManager('tocsv', null, $params, null);
        //echo PHP_EOL . 'Fine setUp'. PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL . PHP_EOL;
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
                    'globalSend' => GLOBALMSG,
                    'serConfig' => [
                        'host' => 'localhost',
                        'path' => 'scarichi/tocsv.php',
                        'params' => [
                            'tocsv' => [
                                'id' => [
                                    'tosend' => false,
                                    'default' => null
                                ],
                                'var' => [
                                    'tosend' => true,
                                    'default' => null
                                ],
                                'datefrom' => [
                                    'tosend' => true,
                                    'default' => null
                                ],
                                'dateto' => [
                                    'tosend' => true,
                                    'default' => null
                                ],
                                'full' => [
                                    'tosend' => true,
                                    'default' => 1
                                ],
                                'field' => [
                                    'tosend' => true,
                                    'default' => 'volume'
                                ]                                
                            ]
                        ],
                        'method' => 'GET',
                        'key' => 'id',
                        'isJson' => false,
                        'isAsync' => true,
                        'printf' => 'formatResponse',
                        'withBody' => true,
                        'header' => [
                            'title' => 'Procedura esportazione CSV dati movimentazioni dighe',
                            'start' => 'Elaborazione iniziata in data:'
                        ],
                        'footer' => [
                            'stop' => 'Elaborazione terminata in data:',
                            'time' => 'Tempo di elaborazione:',
                            'report' => 'Numero totale file csv esportati:'
                        ]
                    ],
                    'service' => 'tocsv',
                    'token' => 'tocsv',
                    'url' => 'http://localhost/scarichi/tocsv.php',
                    'serParams' => [
                        'id' => [
                            'tosend' => false,
                            'default' => null
                        ],
                        'var' => [
                            'tosend' => true,
                            'default' => null
                        ],
                        'datefrom' => [
                            'tosend' => true,
                            'default' => null
                        ],
                        'dateto' => [
                            'tosend' => true,
                            'default' => null
                        ],
                        'full' => [
                            'tosend' => true,
                            'default' => 1
                        ],
                        'field' => [
                            'tosend' => true,
                            'default' => 'volume'
                        ]
                    ],
                    'method' => 'GET',
                    'key' => 'id',
                    'isJson' => false,
                    'isAsync' => true,
                    'printFunction' => 'ServiceManager::formatResponse',
                    'withBody' => true,                    
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
            'tocsv postParams' => [
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
                    'globalSend' => GLOBALMSG,
                    'serConfig' => [
                        'host' => 'localhost',
                        'path' => 'scarichi/tocsv.php',
                        'params' => [
                            'tocsv' => [
                                'id' => [
                                    'tosend' => false,
                                    'default' => null
                                ],
                                'var' => [
                                    'tosend' => true,
                                    'default' => null
                                ],
                                'datefrom' => [
                                    'tosend' => true,
                                    'default' => null
                                ],
                                'dateto' => [
                                    'tosend' => true,
                                    'default' => null
                                ],
                                'full' => [
                                    'tosend' => true,
                                    'default' => 1
                                ],
                                'field' => [
                                    'tosend' => true,
                                    'default' => 'volume'
                                ]                                
                            ]
                        ],
                        'method' => 'GET',
                        'key' => 'id',
                        'isJson' => false,
                        'isAsync' => true,
                        'printf' => 'formatResponse',
                        'withBody' => true,
                        'header' => [
                            'title' => 'Procedura esportazione CSV dati movimentazioni dighe',
                            'start' => 'Elaborazione iniziata in data:'
                        ],
                        'footer' => [
                            'stop' => 'Elaborazione terminata in data:',
                            'time' => 'Tempo di elaborazione:',
                            'report' => 'Numero totale file csv esportati:'
                        ]
                    ],
                    'service' => 'tocsv',
                    'token' => 'tocsv',
                    'url' => 'http://localhost/scarichi/tocsv.php',
                    'serParams' => [
                        'id' => [
                            'tosend' => false,
                            'default' => null
                        ],
                        'var' => [
                            'tosend' => true,
                            'default' => null
                        ],
                        'datefrom' => [
                            'tosend' => true,
                            'default' => null
                        ],
                        'dateto' => [
                            'tosend' => true,
                            'default' => null
                        ],
                        'full' => [
                            'tosend' => true,
                            'default' => 1
                        ],
                        'field' => [
                            'tosend' => true,
                            'default' => 'volume'
                        ]
                    ],
                    'method' => 'GET',
                    'key' => 'id',
                    'isJson' => false,
                    'isAsync' => true,
                    'printFunction' => 'ServiceManager::formatResponse',
                    'withBody' => true,
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
                    'globalSend' => GLOBALMSG,
                    'serConfig' => [
                        'host' => 'localhost',
                        'path' => 'scarichi/tocsv.php',
                        'params' => [
                            'tocsv' => [
                                'id' => [
                                    'tosend' => false,
                                    'default' => null
                                ],
                                'var' => [
                                    'tosend' => true,
                                    'default' => null
                                ],
                                'datefrom' => [
                                    'tosend' => true,
                                    'default' => null
                                ],
                                'dateto' => [
                                    'tosend' => true,
                                    'default' => null
                                ],
                                'full' => [
                                    'tosend' => true,
                                    'default' => 1
                                ],
                                'field' => [
                                    'tosend' => true,
                                    'default' => 'volume'
                                ]                                
                            ]
                        ],
                        'method' => 'GET',
                        'key' => 'id',
                        'isJson' => false,
                        'isAsync' => true,
                        'printf' => 'formatResponse',
                        'withBody' => true,
                        'header' => [
                            'title' => 'Procedura esportazione CSV dati movimentazioni dighe',
                            'start' => 'Elaborazione iniziata in data:'
                        ],
                        'footer' => [
                            'stop' => 'Elaborazione terminata in data:',
                            'time' => 'Tempo di elaborazione:',
                            'report' => 'Numero totale file csv esportati:'
                        ]
                    ],
                    'service' => 'tocsv',
                    'token' => 'tocsv',
                    'url' => 'http://localhost/scarichi/tocsv.php',
                    'serParams' => [
                        'id' => [
                            'tosend' => false,
                            'default' => null
                        ],
                        'var' => [
                            'tosend' => true,
                            'default' => null
                        ],
                        'datefrom' => [
                            'tosend' => true,
                            'default' => null
                        ],
                        'dateto' => [
                            'tosend' => true,
                            'default' => null
                        ],
                        'full' => [
                            'tosend' => true,
                            'default' => 1
                        ],
                        'field' => [
                            'tosend' => true,
                            'default' => 'volume'
                        ]
                    ],
                    'method' => 'GET',
                    'key' => 'id',
                    'isJson' => false,
                    'isAsync' => true,
                    'printFunction' => 'ServiceManager::formatResponse',
                    'withBody' => true,
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
                    'globalSend' => GLOBALMSG,
                    'serConfig' => [
                        'host' => 'localhost',
                        'path' => 'scarichi/tocsv.php',
                        'params' => [
                            'tocsv' => [
                                'id' => [
                                    'tosend' => false,
                                    'default' => null
                                ],
                                'var' => [
                                    'tosend' => true,
                                    'default' => null
                                ],
                                'datefrom' => [
                                    'tosend' => true,
                                    'default' => null
                                ],
                                'dateto' => [
                                    'tosend' => true,
                                    'default' => null
                                ],
                                'full' => [
                                    'tosend' => true,
                                    'default' => 1
                                ],
                                'field' => [
                                    'tosend' => true,
                                    'default' => 'volume'
                                ]                                
                            ]
                        ],
                        'method' => 'GET',
                        'key' => 'id',
                        'isJson' => false,
                        'isAsync' => true,
                        'printf' => 'formatResponse',
                        'withBody' => true,
                        'header' => [
                            'title' => 'Procedura esportazione CSV dati movimentazioni dighe',
                            'start' => 'Elaborazione iniziata in data:'
                        ],
                        'footer' => [
                            'stop' => 'Elaborazione terminata in data:',
                            'time' => 'Tempo di elaborazione:',
                            'report' => 'Numero totale file csv esportati:'
                        ]
                    ],
                    'service' => 'tocsv',
                    'token' => 'tocsv',
                    'url' => 'http://localhost/scarichi/tocsv.php',
                    'serParams' => [
                        'id' => [
                            'tosend' => false,
                            'default' => null
                        ],
                        'var' => [
                            'tosend' => true,
                            'default' => null
                        ],
                        'datefrom' => [
                            'tosend' => true,
                            'default' => null
                        ],
                        'dateto' => [
                            'tosend' => true,
                            'default' => null
                        ],
                        'full' => [
                            'tosend' => true,
                            'default' => 1
                        ],
                        'field' => [
                            'tosend' => true,
                            'default' => 'volume'
                        ]
                    ],
                    'method' => 'GET',
                    'key' => 'id',
                    'isJson' => false,
                    'isAsync' => true,
                    'printFunction' => 'ServiceManager::formatResponse',
                    'withBody' => true,
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
            ],
            'tocsv postParams count 0' => [
                'args' => [
                    'service' => 'tocsv',
                    'token' => null,
                    'postParams' => [],
                    'config' => null
                ],
                'expected1' => [
                    'globalSend' => GLOBALMSG,
                    'serConfig' => [
                        'host' => 'localhost',
                        'path' => 'scarichi/tocsv.php',
                        'params' => [
                            'tocsv' => [
                                'id' => [
                                    'tosend' => false,
                                    'default' => null
                                ],
                                'var' => [
                                    'tosend' => true,
                                    'default' => null
                                ],
                                'datefrom' => [
                                    'tosend' => true,
                                    'default' => null
                                ],
                                'dateto' => [
                                    'tosend' => true,
                                    'default' => null
                                ],
                                'full' => [
                                    'tosend' => true,
                                    'default' => 1
                                ],
                                'field' => [
                                    'tosend' => true,
                                    'default' => 'volume'
                                ]                                
                            ]
                        ],
                        'method' => 'GET',
                        'key' => 'id',
                        'isJson' => false,
                        'isAsync' => true,
                        'printf' => 'formatResponse',
                        'withBody' => true,
                        'header' => [
                            'title' => 'Procedura esportazione CSV dati movimentazioni dighe',
                            'start' => 'Elaborazione iniziata in data:'
                        ],
                        'footer' => [
                            'stop' => 'Elaborazione terminata in data:',
                            'time' => 'Tempo di elaborazione:',
                            'report' => 'Numero totale file csv esportati:'
                        ]
                    ],
                    'service' => 'tocsv',
                    'token' => 'tocsv',
                    'url' => 'http://localhost/scarichi/tocsv.php',
                    'serParams' => [
                        'id' => [
                            'tosend' => false,
                            'default' => null
                        ],
                        'var' => [
                            'tosend' => true,
                            'default' => null
                        ],
                        'datefrom' => [
                            'tosend' => true,
                            'default' => null
                        ],
                        'dateto' => [
                            'tosend' => true,
                            'default' => null
                        ],
                        'full' => [
                            'tosend' => true,
                            'default' => 1
                        ],
                        'field' => [
                            'tosend' => true,
                            'default' => 'volume'
                        ]
                    ],
                    'method' => 'GET',
                    'key' => 'id',
                    'isJson' => false,
                    'isAsync' => true,
                    'printFunction' => 'ServiceManager::formatResponse',
                    'withBody' => true,                    
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
            'url service' => [
                'args' => [
                    'service' => 'http://localhost/scarichi/tocsv.php?var=30030&datefrom=01/01/2019&dateto=02/01/2019&full=1&field=volume',
                    'token' => null,
                    'postParams' => null,
                    'config' => null
                ],
                'expected1' => [
                    'globalSend' => GLOBALMSG,
                    'serConfig' => null,
                    'service' => 'http://localhost/scarichi/tocsv.php?var=30030&datefrom=01/01/2019&dateto=02/01/2019&full=1&field=volume',
                    'token' => 'http://localhost/scarichi/tocsv.php',
                    'url' => 'http://localhost/scarichi/tocsv.php?var=30030&datefrom=01/01/2019&dateto=02/01/2019&full=1&field=volume',
                    'serParams' => [],
                    'method' => 'GET',
                    'key' => null,
                    'isJson' => false,
                    'isAsync' => false,
                    'printFunction' => null,
                    'withBody' => false,
                    'params' => [
                        [
                            'url' => 'http://localhost/scarichi/tocsv.php?var=30030&datefrom=01/01/2019&dateto=02/01/2019&full=1&field=volume',
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
                    'message' => '<b>http://localhost/scarichi/tocsv.php</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL,
                ],
                'expected4' => [
                    'echos' => []
                ]
            ],
            'url service with token' => [
                'args' => [
                    'service' => 'http://localhost/scarichi/tocsv.php?var=30030&datefrom=01/01/2019&dateto=02/01/2019&full=1&field=volume',
                    'token' => 'Esportazione volumi su csv',
                    'postParams' => null,
                    'config' => null
                ],
                'expected1' => [
                    'globalSend' => GLOBALMSG,
                    'serConfig' => null,
                    'service' => 'http://localhost/scarichi/tocsv.php?var=30030&datefrom=01/01/2019&dateto=02/01/2019&full=1&field=volume',
                    'token' => 'Esportazione volumi su csv',
                    'url' => 'http://localhost/scarichi/tocsv.php?var=30030&datefrom=01/01/2019&dateto=02/01/2019&full=1&field=volume',
                    'serParams' => [],
                    'method' => 'GET',
                    'key' => null,
                    'isJson' => false,
                    'isAsync' => false,
                    'printFunction' => null,
                    'withBody' => false,
                    'params' => [
                        [
                            'url' => 'http://localhost/scarichi/tocsv.php?var=30030&datefrom=01/01/2019&dateto=02/01/2019&full=1&field=volume',
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
                    'message' => '<b>Esportazione volumi su csv</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL,
                ],
                'expected4' => [
                    'echos' => []
                ]
            ],
            'url token params' => [
                'args' => [
                    'service' => 'http://localhost/scarichi/tocsv.php',
                    'token' => 'Esportazione volumi su csv',
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
                    'globalSend' => GLOBALMSG,
                    'serConfig' => null,
                    'service' => 'http://localhost/scarichi/tocsv.php',
                    'token' => 'Esportazione volumi su csv',
                    'url' => 'http://localhost/scarichi/tocsv.php',
                    'serParams' => [],
                    'method' => 'GET',
                    'key' => null,
                    'isJson' => false,
                    'isAsync' => false,
                    'printFunction' => null,
                    'withBody' => false,
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
                    'message' => '<b>Esportazione volumi su csv</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL,
                ],
                'expected4' => [
                    'echos' => []
                ]
            ],
            'with config' => [
                'args' => [
                    'service' => 'test',
                    'token' => null,
                    'postParams' => [
                        [
                            'full' => '1',
                            'field' => 'volume'
                        ]
                    ],
                    'config' => [
                        'test' => [
                            'host' => 'localhost',
                            'path' => 'scarichi/tocsv.php',
                            'params' => [
                                'test' => [
                                    'var' => [
                                        'tosend' => true,
                                        'default' => '30030'
                                    ],
                                    'datefrom' => [
                                        'tosend' => true,
                                        'default' => '01/01/2019'
                                    ],
                                    'dateto' => [
                                        'tosend' => true,
                                        'default' => '02/01/2019'
                                    ]                               
                                ]
                            ],
                            'method' => 'POST',
                            'key' => 'var',
                            'isJson' => false,
                            'isAsync' => false,
                            'printf' => null,
                            'withBody' => false,
                            'header' => [],
                            'footer' => []
                        ]
                    ]    
                ],
                'expected1' => [
                    'globalSend' => GLOBALMSG,
                    'serConfig' => [
                        'host' => 'localhost',
                        'path' => 'scarichi/tocsv.php',
                        'params' => [
                            'test' => [
                                'var' => [
                                    'tosend' => true,
                                    'default' => '30030'
                                ],
                                'datefrom' => [
                                    'tosend' => true,
                                    'default' => '01/01/2019'
                                ],
                                'dateto' => [
                                    'tosend' => true,
                                    'default' => '02/01/2019'
                                ]                               
                            ]
                        ],
                        'method' => 'POST',
                        'key' => 'var',
                        'isJson' => false,
                        'isAsync' => false,
                        'printf' => null,
                        'withBody' => false,
                        'header' => [],
                        'footer' => []
                    ],
                    'service' => 'test',
                    'token' => 'test',
                    'url' => 'http://localhost/scarichi/tocsv.php',
                    'serParams' => [
                        'var' => [
                            'tosend' => true,
                            'default' => '30030'
                        ],
                        'datefrom' => [
                            'tosend' => true,
                            'default' => '01/01/2019'
                        ],
                        'dateto' => [
                            'tosend' => true,
                            'default' => '02/01/2019'
                        ]
                    ],
                    'method' => 'POST',
                    'key' => 'var',
                    'isJson' => false,
                    'isAsync' => false,
                    'printFunction' => null,
                    'withBody' => false,
                    'params' => [
                        [
                            'url' => 'http://localhost/scarichi/tocsv.php',
                            'method' => 'POST',
                            'params' => [
                                'var' => '30030',
                                'datefrom' => '01/01/2019',
                                'dateto' => '02/01/2019'
                            ],
                            'isJson' => false,
                            'key' => '30030'
                        ]
                    ]
                ],
                'expected2' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b>"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}'
                    ]
                ],
                'expected3' => [
                    'message' => '<b>test</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL,
                ],
                'expected4' => [
                    'echos' => []
                ]
            ],
            'config token body' => [
                'args' => [
                    'service' => 'test',
                    'token' => 'withToken',
                    'postParams' => [
                        [
                            'full' => '1',
                            'field' => 'volume'
                        ]
                    ],
                    'config' => [
                        'test' => [
                            'host' => 'localhost',
                            'path' => 'scarichi/tocsv.php',
                            'params' => [
                                'withToken' => [
                                    'token' => [
                                        'tosend' => true,
                                        'default' => 'withToken'
                                    ],
                                    'var' => [
                                        'tosend' => true,
                                        'default' => '30030'
                                    ],
                                    'datefrom' => [
                                        'tosend' => true,
                                        'default' => '01/01/2019'
                                    ],
                                    'dateto' => [
                                        'tosend' => true,
                                        'default' => '02/01/2019'
                                    ]                               
                                ]
                            ],
                            'method' => 'GET',
                            'key' => 'token',
                            'isJson' => false,
                            'isAsync' => false,
                            'printf' => null,
                            'withBody' => true,
                            'globalSend' => false,
                            'header' => [],
                            'footer' => []
                        ]
                    ]
                ],
                'expected1' => [
                    'globalSend' => false,
                    'serConfig' => [
                        'host' => 'localhost',
                        'path' => 'scarichi/tocsv.php',
                        'params' => [
                            'withToken' => [
                                'token' => [
                                    'tosend' => true,
                                    'default' => 'withToken'
                                ],
                                'var' => [
                                    'tosend' => true,
                                    'default' => '30030'
                                ],
                                'datefrom' => [
                                    'tosend' => true,
                                    'default' => '01/01/2019'
                                ],
                                'dateto' => [
                                    'tosend' => true,
                                    'default' => '02/01/2019'
                                ]                               
                            ]
                        ],
                        'method' => 'GET',
                        'key' => 'token',
                        'isJson' => false,
                        'isAsync' => false,
                        'printf' => null,
                        'withBody' => true,
                        'globalSend' => false,
                        'header' => [],
                        'footer' => []
                    ],
                    'service' => 'test',
                    'token' => 'withToken',
                    'url' => 'http://localhost/scarichi/tocsv.php',
                    'serParams' => [
                        'token' => [
                            'tosend' => true,
                            'default' => 'withToken'
                        ],
                        'var' => [
                            'tosend' => true,
                            'default' => '30030'
                        ],
                        'datefrom' => [
                            'tosend' => true,
                            'default' => '01/01/2019'
                        ],
                        'dateto' => [
                            'tosend' => true,
                            'default' => '02/01/2019'
                        ]
                    ],
                    'method' => 'GET',
                    'key' => 'token',
                    'isJson' => false,
                    'isAsync' => false,
                    'printFunction' => null,
                    'withBody' => true,
                    'params' => [
                        [
                            'url' => 'http://localhost/scarichi/tocsv.php?token=withToken&var=30030&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'withToken'
                        ]
                    ]
                ],
                'expected2' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b>"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}'
                    ]
                ],
                'expected3' => [
                    'message' => '<b>withToken</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . PHP_EOL . 'Elaborazione dati <b>Volume</b> variabile <b>30030</b> dal <b>01/01/2019</b> al <b>02/01/2019</b> avvenuta con successo in <b>|</b>. File CSV <b>full</b> esportati: <b>|</b>' . PHP_EOL . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL,
                ],
                'expected4' => [
                    'echos' => []
                ]
            ],
           'body more responses' => [
                'args' => [
                    'service' => 'test',
                    'token' => 'withToken',
                    'postParams' => [
                        [
                            'full' => '1',
                            'field' => 'volume'
                        ],
                        [
                            'var' => '30040'
                        ]
                    ],
                    'config' => [
                        'test' => [
                            'host' => 'localhost',
                            'path' => 'scarichi/tocsv.php',
                            'params' => [
                                'withToken' => [
                                    'token' => [
                                        'tosend' => true,
                                        'default' => 'withToken'
                                    ],
                                    'var' => [
                                        'tosend' => true,
                                        'default' => '30030'
                                    ],
                                    'datefrom' => [
                                        'tosend' => true,
                                        'default' => '01/01/2019'
                                    ],
                                    'dateto' => [
                                        'tosend' => true,
                                        'default' => '02/01/2019'
                                    ]                               
                                ]
                            ],
                            'method' => 'GET',
                            'key' => 'token*',
                            'isJson' => false,
                            'isAsync' => true,
                            'printf' => 'formatResponse',
                            'withBody' => true,
                            'globalSend' => false,
                            'header' => [],
                            'footer' => []
                        ]
                    ]
                ],
                'expected1' => [
                    'globalSend' => false,
                    'serConfig' => [
                        'host' => 'localhost',
                        'path' => 'scarichi/tocsv.php',
                        'params' => [
                            'withToken' => [
                                'token' => [
                                    'tosend' => true,
                                    'default' => 'withToken'
                                ],
                                'var' => [
                                    'tosend' => true,
                                    'default' => '30030'
                                ],
                                'datefrom' => [
                                    'tosend' => true,
                                    'default' => '01/01/2019'
                                ],
                                'dateto' => [
                                    'tosend' => true,
                                    'default' => '02/01/2019'
                                ]                               
                            ]
                        ],
                        'method' => 'GET',
                        'key' => 'token*',
                        'isJson' => false,
                        'isAsync' => true,
                        'printf' => 'formatResponse',
                        'withBody' => true,
                        'globalSend' => false,
                        'header' => [],
                        'footer' => []
                    ],
                    'service' => 'test',
                    'token' => 'withToken',
                    'url' => 'http://localhost/scarichi/tocsv.php',
                    'serParams' => [
                        'token' => [
                            'tosend' => true,
                            'default' => 'withToken'
                        ],
                        'var' => [
                            'tosend' => true,
                            'default' => '30030'
                        ],
                        'datefrom' => [
                            'tosend' => true,
                            'default' => '01/01/2019'
                        ],
                        'dateto' => [
                            'tosend' => true,
                            'default' => '02/01/2019'
                        ]
                    ],
                    'method' => 'GET',
                    'key' => 'token*',
                    'isJson' => false,
                    'isAsync' => true,
                    'printFunction' => 'ServiceManager::formatResponse',
                    'withBody' => true,
                    'params' => [
                        [
                            'url' => 'http://localhost/scarichi/tocsv.php?token=withToken&var=30030&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019',                            
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'token0'
                        ],
                        [
                            'url' => 'http://localhost/scarichi/tocsv.php?var=30040&token=withToken&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'token1'
                        ]
                    ]
                ],
                'expected2' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b>"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}',
                        '{"ok":true,"response":{"params":{"var":"30040","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30040<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b>"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}'
                    ]
                ],
                'expected3' => [
                    'message' => '<b>withToken</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . PHP_EOL . 'Elaborazione dati <b>Volume</b> variabile <b>30030</b> dal <b>01/01/2019</b> al <b>02/01/2019</b> avvenuta con successo in <b>|</b>. File CSV <b>full</b> esportati: <b>|</b>' . PHP_EOL . 'Elaborazione dati <b>Volume</b> variabile <b>30040</b> dal <b>01/01/2019</b> al <b>02/01/2019</b> avvenuta con successo in <b>|</b>. File CSV <b>full</b> esportati: <b>|</b>' . PHP_EOL . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL,
                ],
                'expected4' => [
                    'echos' => [
                        '1) PID token0: Elaborazione dati Volume variabile 30030 dal 01/01/2019 al 02/01/2019 avvenuta con successo in | sec. File CSV full esportati: |',
                        '2) PID token1: Elaborazione dati Volume variabile 30040 dal 01/01/2019 al 02/01/2019 avvenuta con successo in | sec. File CSV full esportati: |'
                    ]
                ]
            ],
            'static params key' => [
                'args' => [
                    'service' => 'test',
                    'token' => 'withToken',
                    'postParams' => [
                        [
                            'full' => '1',
                            'field' => 'volume'
                        ],
                        [
                            'var' => '30040'
                        ]
                    ],
                    'config' => [
                        'test' => [
                            'host' => 'localhost',
                            'path' => 'scarichi/tocsv.php',
                            'params' => [
                                'withToken' => [
                                    'token' => [
                                        'tosend' => true,
                                        'default' => 'withToken'
                                    ],
                                    'var' => [
                                        'tosend' => true,
                                        'default' => '30030'
                                    ],
                                    'datefrom' => [
                                        'tosend' => true,
                                        'default' => '01/01/2019'
                                    ],
                                    'dateto' => [
                                        'tosend' => true,
                                        'default' => '02/01/2019'
                                    ]                               
                                ]
                            ],
                            'method' => 'GET',
                            'key' => 'token',
                            'isJson' => false,
                            'isAsync' => true,
                            'printf' => 'formatResponse',
                            'withBody' => true,
                            'globalSend' => false,
                            'header' => [],
                            'footer' => []
                        ]
                    ]
                ],
                'expected1' => [
                    'globalSend' => false,
                    'serConfig' => [
                        'host' => 'localhost',
                        'path' => 'scarichi/tocsv.php',
                        'params' => [
                            'withToken' => [
                                'token' => [
                                    'tosend' => true,
                                    'default' => 'withToken'
                                ],
                                'var' => [
                                    'tosend' => true,
                                    'default' => '30030'
                                ],
                                'datefrom' => [
                                    'tosend' => true,
                                    'default' => '01/01/2019'
                                ],
                                'dateto' => [
                                    'tosend' => true,
                                    'default' => '02/01/2019'
                                ]                               
                            ]
                        ],
                        'method' => 'GET',
                        'key' => 'token',
                        'isJson' => false,
                        'isAsync' => true,
                        'printf' => 'formatResponse',
                        'withBody' => true,
                        'globalSend' => false,
                        'header' => [],
                        'footer' => []
                    ],
                    'service' => 'test',
                    'token' => 'withToken',
                    'url' => 'http://localhost/scarichi/tocsv.php',
                    'serParams' => [
                        'token' => [
                            'tosend' => true,
                            'default' => 'withToken'
                        ],
                        'var' => [
                            'tosend' => true,
                            'default' => '30030'
                        ],
                        'datefrom' => [
                            'tosend' => true,
                            'default' => '01/01/2019'
                        ],
                        'dateto' => [
                            'tosend' => true,
                            'default' => '02/01/2019'
                        ]
                    ],
                    'method' => 'GET',
                    'key' => 'token',
                    'isJson' => false,
                    'isAsync' => true,
                    'printFunction' => 'ServiceManager::formatResponse',
                    'withBody' => true,
                    'params' => [
                        [
                            'url' => 'http://localhost/scarichi/tocsv.php?token=withToken&var=30030&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019',                            
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'withToken'
                        ],
                        [
                            'url' => 'http://localhost/scarichi/tocsv.php?var=30040&token=withToken&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'withToken1'
                        ]
                    ]
                ],
                'expected2' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b>"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}',
                        '{"ok":true,"response":{"params":{"var":"30040","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30040<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b>"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}'
                    ]
                ],
                'expected3' => [
                    'message' => '<b>withToken</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . PHP_EOL . 'Elaborazione dati <b>Volume</b> variabile <b>30030</b> dal <b>01/01/2019</b> al <b>02/01/2019</b> avvenuta con successo in <b>|</b>. File CSV <b>full</b> esportati: <b>|</b>' . PHP_EOL . 'Elaborazione dati <b>Volume</b> variabile <b>30040</b> dal <b>01/01/2019</b> al <b>02/01/2019</b> avvenuta con successo in <b>|</b>. File CSV <b>full</b> esportati: <b>|</b>' . PHP_EOL . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL,
                ],
                'expected4' => [
                    'echos' => [
                        '1) PID withToken: Elaborazione dati Volume variabile 30030 dal 01/01/2019 al 02/01/2019 avvenuta con successo in | sec. File CSV full esportati: |',
                        '2) PID withToken1: Elaborazione dati Volume variabile 30040 dal 01/01/2019 al 02/01/2019 avvenuta con successo in | sec. File CSV full esportati: |'
                    ]
                ]
            ],
            'static key' => [
                'args' => [
                    'service' => 'test',
                    'token' => null,
                    'postParams' => [
                        [
                            'full' => '1',
                            'field' => 'volume'
                        ],
                        [
                            'var' => '30040'
                        ]
                    ],
                    'config' => [
                        'test' => [
                            'host' => 'localhost',
                            'path' => 'scarichi/tocsv.php',
                            'params' => [
                                'test' => [
                                    'var' => [
                                        'tosend' => true,
                                        'default' => '30030'
                                    ],
                                    'datefrom' => [
                                        'tosend' => true,
                                        'default' => '01/01/2019'
                                    ],
                                    'dateto' => [
                                        'tosend' => true,
                                        'default' => '02/01/2019'
                                    ]                               
                                ]
                            ],
                            'method' => 'GET',
                            'key' => 'pippo',
                            'isJson' => false,
                            'isAsync' => false,
                            'printf' => 'formatResponse',
                            'withBody' => true,
                            'globalSend' => false,
                            'header' => [],
                            'footer' => []
                        ]
                    ]
                ],
                'expected1' => [
                    'globalSend' => false,
                    'serConfig' => [
                        'host' => 'localhost',
                        'path' => 'scarichi/tocsv.php',
                        'params' => [
                            'test' => [
                                'var' => [
                                    'tosend' => true,
                                    'default' => '30030'
                                ],
                                'datefrom' => [
                                    'tosend' => true,
                                    'default' => '01/01/2019'
                                ],
                                'dateto' => [
                                    'tosend' => true,
                                    'default' => '02/01/2019'
                                ]                               
                            ]
                        ],
                        'method' => 'GET',
                        'key' => 'pippo',
                        'isJson' => false,
                        'isAsync' => false,
                        'printf' => 'formatResponse',
                        'withBody' => true,
                        'globalSend' => false,
                        'header' => [],
                        'footer' => []
                    ],
                    'service' => 'test',
                    'token' => 'test',
                    'url' => 'http://localhost/scarichi/tocsv.php',
                    'serParams' => [
                        'var' => [
                            'tosend' => true,
                            'default' => '30030'
                        ],
                        'datefrom' => [
                            'tosend' => true,
                            'default' => '01/01/2019'
                        ],
                        'dateto' => [
                            'tosend' => true,
                            'default' => '02/01/2019'
                        ]
                    ],
                    'method' => 'GET',
                    'key' => 'pippo',
                    'isJson' => false,
                    'isAsync' => false,
                    'printFunction' => 'ServiceManager::formatResponse',
                    'withBody' => true,
                    'params' => [
                        [
                            'url' => 'http://localhost/scarichi/tocsv.php?var=30030&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019',                            
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'pippo'
                        ],
                        [
                            'url' => 'http://localhost/scarichi/tocsv.php?var=30040&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'pippo1'
                        ]
                    ]
                ],
                'expected2' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b>"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}',
                        '{"ok":true,"response":{"params":{"var":"30040","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30040<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b>"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}'
                    ]
                ],
                'expected3' => [
                    'message' => '<b>test</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . PHP_EOL . 'Elaborazione dati <b>Volume</b> variabile <b>30030</b> dal <b>01/01/2019</b> al <b>02/01/2019</b> avvenuta con successo in <b>|</b>. File CSV <b>full</b> esportati: <b>|</b>' . PHP_EOL . 'Elaborazione dati <b>Volume</b> variabile <b>30040</b> dal <b>01/01/2019</b> al <b>02/01/2019</b> avvenuta con successo in <b>|</b>. File CSV <b>full</b> esportati: <b>|</b>' . PHP_EOL . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL,
                ],
                'expected4' => [
                    'echos' => [
                        '1) PID pippo: Elaborazione dati Volume variabile 30030 dal 01/01/2019 al 02/01/2019 avvenuta con successo in | sec. File CSV full esportati: |',
                        '2) PID pippo1: Elaborazione dati Volume variabile 30040 dal 01/01/2019 al 02/01/2019 avvenuta con successo in | sec. File CSV full esportati: |'
                    ]
                ]
            ],
            'sync postParams' => [
                'args' => [
                    'service' => 'telegram_REST',
                    'token' => 'sync',
                    'postParams' => [
                        [
                            'token' => 'sync',
                            'variabile' => 'ALL',
                            'tel' => 0
                        ]
                    ],
                    'config' => null
                ],
                'expected1' => [
                    'globalSend' => GLOBALMSG,
                    'serConfig' => [
                        'host' => 'remotehost',
                        'path' => 'telecontrollo/bot/telegram_REST.php',
                        'params' => [
                            'sync' => [
                                'token' => [
                                    'tosend' => true,
                                    'default' => 'sync'
                                ],
                                'variabile' => [
                                    'tosend' => true,
                                    'default' => 'ALL'
                                ],
                                'db' => [
                                    'tosend' => true,
                                    'default' => 1
                                ],
                                'tipodato' => [
                                    'tosend' => true,
                                    'default' => 1
                                ],
                                'delay' => [
                                    'tosend' => true,
                                    'default' => 168
                                ],
                                'tel' => [
                                    'tosend' => true,
                                    'default' => 1
                                ]                               
                            ],
                            'watchdog' => [
                                'token' => [
                                    'tosend' => true,
                                    'default' => 'watchdog'
                                ],
                                'tipologia' => [
                                    'tosend' => true,
                                    'default' => 'scarichi'
                                ],
                                'host' => [
                                    'tosend' => true,
                                    'default' => 1
                                ],
                                'move' => [
                                    'tosend' => true,
                                    'default' => 0
                                ],
                                'variabile' => [
                                    'tosend' => true,
                                    'default' => 'ALL'
                                ],
                                'metodo' => [
                                    'tosend' => true,
                                    'default' => 'array'
                                ],
                                'tel' => [
                                    'tosend' => true,
                                    'default' => 1
                                ] 
                            ]
                        ],
                        'method' => 'GET',
                        'key' => 'token',
                        'isJson' => false,
                        'isAsync' => false,
                        'printf' => 'formatResponse',
                        'withBody' => false,
                        'header' => [],
                        'footer' => []
                    ],
                    'service' => 'telegram_REST',
                    'token' => 'sync',
                    'url' => 'http://192.168.1.100/telecontrollo/bot/telegram_REST.php',
                    'serParams' => [
                        'token' => [
                            'tosend' => true,
                            'default' => 'sync'
                        ],
                        'variabile' => [
                            'tosend' => true,
                            'default' => 'ALL'
                        ],
                        'db' => [
                            'tosend' => true,
                            'default' => 1
                        ],
                        'tipodato' => [
                            'tosend' => true,
                            'default' => 1
                        ],
                        'delay' => [
                            'tosend' => true,
                            'default' => 168
                        ],
                        'tel' => [
                            'tosend' => true,
                            'default' => 1
                        ]                        
                    ],
                    'method' => 'GET',
                    'key' => 'token',
                    'isJson' => false,
                    'isAsync' => false,
                    'printFunction' => 'ServiceManager::formatResponse',
                    'withBody' => false,
                    'params' => [
                        [
                            'url' => 'http://192.168.1.100/telecontrollo/bot/telegram_REST.php?token=sync&variabile=ALL&tel=0&db=1&tipodato=1&delay=168',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'sync'
                        ]
                    ]
                ],
                'expected2' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"token":"sync","variabile":"ALL","delay":"168","tel":"0"},"header":["PROCEDURA SINCRONIZZAZIONE DB ENAS (DB1 => DB2)","DB1: 10.0.0.23\\\sql_server_spt ==> DB2: 10.0.10.43 | Delay (ore): 168","Elaborazione iniziata in data: 17\/03\/2021 13:18:48"],"body":{"SSCP_data.11.2":"Processata in 0,039 secondi | Records: 240 | Insert: 1 | Update: 0 | Presenti: 239 | Scartati: 0 | Cancellati: 0","SSCP_data.26.2":"Processata in 0,098 secondi | Records: 955 | Insert: 10 | Update: 0 | Presenti: 939 | Scartati: 6 | Cancellati: 0","SSCP_data.42.2":"Processata in 0,045 secondi | Records: 149 | Insert: 1 | Update: 0 | Presenti: 148 | Scartati: 0 | Cancellati: 0"},"footer":["Elaborazione finita in data: 17\/03\/2021 13:18:51","Tempo totale di elaborazione: 2,281 secondi","TOTALI: Records: 12.306 | Insert: 54 | Update: 0 | Presenti: 12.245 | Scartati: 7 | Cancellati: 0"]}}'
                    ]
                ],
                'expected3' => [
                    'message' => '<b>Procedura sincronizzazione banche dati</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL . '<b>Totali:</b> Records: 12.306 | Insert: 54 | Update: 0 | Presenti: 12.245 | Scartati: 7 | Cancellati: 0',
                ],
                'expected4' => [
                    'echos' => [
                        '1.0) PID sync.0: Processata in 0,039 secondi | Records: 240 | Insert: 1 | Update: 0 | Presenti: 239 | Scartati: 0 | Cancellati: 0' . PHP_EOL,
                        '1.1) PID sync.1: Processata in 0,098 secondi | Records: 955 | Insert: 10 | Update: 0 | Presenti: 939 | Scartati: 6 | Cancellati: 0' . PHP_EOL,
                        '1.2) PID sync.2: Processata in 0,045 secondi | Records: 149 | Insert: 1 | Update: 0 | Presenti: 148 | Scartati: 0 | Cancellati: 0' . PHP_EOL
                    ]
                ]
            ],
            'sync single var' => [
                'args' => [
                    'service' => 'telegram_REST',
                    'token' => 'sync',
                    'postParams' => [
                        [
                            'token' => 'sync',
                            'variabile' => '30030',
                            'tel' => 0
                        ]
                    ],
                    'config' => null
                ],
                'expected1' => [
                    'globalSend' => GLOBALMSG,
                    'serConfig' => [
                        'host' => 'remotehost',
                        'path' => 'telecontrollo/bot/telegram_REST.php',
                        'params' => [
                            'sync' => [
                                'token' => [
                                    'tosend' => true,
                                    'default' => 'sync'
                                ],
                                'variabile' => [
                                    'tosend' => true,
                                    'default' => 'ALL'
                                ],
                                'db' => [
                                    'tosend' => true,
                                    'default' => 1
                                ],
                                'tipodato' => [
                                    'tosend' => true,
                                    'default' => 1
                                ],
                                'delay' => [
                                    'tosend' => true,
                                    'default' => 168
                                ],
                                'tel' => [
                                    'tosend' => true,
                                    'default' => 1
                                ]                               
                            ],
                            'watchdog' => [
                                'token' => [
                                    'tosend' => true,
                                    'default' => 'watchdog'
                                ],
                                'tipologia' => [
                                    'tosend' => true,
                                    'default' => 'scarichi'
                                ],
                                'host' => [
                                    'tosend' => true,
                                    'default' => 1
                                ],
                                'move' => [
                                    'tosend' => true,
                                    'default' => 0
                                ],
                                'variabile' => [
                                    'tosend' => true,
                                    'default' => 'ALL'
                                ],
                                'metodo' => [
                                    'tosend' => true,
                                    'default' => 'array'
                                ],
                                'tel' => [
                                    'tosend' => true,
                                    'default' => 1
                                ] 
                            ]
                        ],
                        'method' => 'GET',
                        'key' => 'token',
                        'isJson' => false,
                        'isAsync' => false,
                        'printf' => 'formatResponse',
                        'withBody' => false,
                        'header' => [],
                        'footer' => []
                    ],
                    'service' => 'telegram_REST',
                    'token' => 'sync',
                    'url' => 'http://192.168.1.100/telecontrollo/bot/telegram_REST.php',
                    'serParams' => [
                        'token' => [
                            'tosend' => true,
                            'default' => 'sync'
                        ],
                        'variabile' => [
                            'tosend' => true,
                            'default' => 'ALL'
                        ],
                        'db' => [
                            'tosend' => true,
                            'default' => 1
                        ],
                        'tipodato' => [
                            'tosend' => true,
                            'default' => 1
                        ],
                        'delay' => [
                            'tosend' => true,
                            'default' => 168
                        ],
                        'tel' => [
                            'tosend' => true,
                            'default' => 1
                        ]                        
                    ],
                    'method' => 'GET',
                    'key' => 'token',
                    'isJson' => false,
                    'isAsync' => false,
                    'printFunction' => 'ServiceManager::formatResponse',
                    'withBody' => false,
                    'params' => [
                        [
                            'url' => 'http://192.168.1.100/telecontrollo/bot/telegram_REST.php?token=sync&variabile=30030&tel=0&db=1&tipodato=1&delay=168',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'sync'
                        ]
                    ]
                ],
                'expected2' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"token":"sync","variabile":"ALL","delay":"168","tel":"0"},"header":["PROCEDURA SINCRONIZZAZIONE DB ENAS (DB1 => DB2)","DB1: 10.0.0.23\\\sql_server_spt ==> DB2: 10.0.10.43 | Delay (ore): 168","Elaborazione iniziata in data: 17\/03\/2021 13:18:48"],"body":{"SSCP_data.11.2":"Processata in 0,039 secondi | Records: 240 | Insert: 1 | Update: 0 | Presenti: 239 | Scartati: 0 | Cancellati: 0","SSCP_data.26.2":"Processata in 0,098 secondi | Records: 955 | Insert: 10 | Update: 0 | Presenti: 939 | Scartati: 6 | Cancellati: 0"},"footer":["Elaborazione finita in data: 17\/03\/2021 13:18:51","Tempo totale di elaborazione: 2,281 secondi","TOTALI: Records: 1.195 | Insert: 11 | Update: 0 | Presenti: 1178 | Scartati: 6 | Cancellati: 0"]}}'
                    ]
                ],
                'expected3' => [
                    'message' => '<b>Procedura sincronizzazione banche dati</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL . '<b>Totali:</b> Records: 1.195 | Insert: 11 | Update: 0 | Presenti: 1.178 | Scartati: 6 | Cancellati: 0',
                ],
                'expected4' => [
                    'echos' => [
                        '1.0) PID sync.0: Processata in 0,039 secondi | Records: 240 | Insert: 1 | Update: 0 | Presenti: 239 | Scartati: 0 | Cancellati: 0' . PHP_EOL,
                        '1.1) PID sync.1: Processata in 0,098 secondi | Records: 955 | Insert: 10 | Update: 0 | Presenti: 939 | Scartati: 6 | Cancellati: 0' . PHP_EOL                        
                    ]
                ]
            ],
            'sync multi var' => [
                'args' => [
                    'service' => 'telegram_REST',
                    'token' => 'sync',
                    'postParams' => [
                        [
                            'token' => 'sync',
                            'variabile' => '30030',
                            'tel' => 0
                        ],
                        [
                            'token' => 'sync',
                            'variabile' => '30040',
                            'tel' => 0
                        ]
                    ],
                    'config' => null
                ],
                'expected1' => [
                    'globalSend' => GLOBALMSG,
                    'serConfig' => [
                        'host' => 'remotehost',
                        'path' => 'telecontrollo/bot/telegram_REST.php',
                        'params' => [
                            'sync' => [
                                'token' => [
                                    'tosend' => true,
                                    'default' => 'sync'
                                ],
                                'variabile' => [
                                    'tosend' => true,
                                    'default' => 'ALL'
                                ],
                                'db' => [
                                    'tosend' => true,
                                    'default' => 1
                                ],
                                'tipodato' => [
                                    'tosend' => true,
                                    'default' => 1
                                ],
                                'delay' => [
                                    'tosend' => true,
                                    'default' => 168
                                ],
                                'tel' => [
                                    'tosend' => true,
                                    'default' => 1
                                ]                               
                            ],
                            'watchdog' => [
                                'token' => [
                                    'tosend' => true,
                                    'default' => 'watchdog'
                                ],
                                'tipologia' => [
                                    'tosend' => true,
                                    'default' => 'scarichi'
                                ],
                                'host' => [
                                    'tosend' => true,
                                    'default' => 1
                                ],
                                'move' => [
                                    'tosend' => true,
                                    'default' => 0
                                ],
                                'variabile' => [
                                    'tosend' => true,
                                    'default' => 'ALL'
                                ],
                                'metodo' => [
                                    'tosend' => true,
                                    'default' => 'array'
                                ],
                                'tel' => [
                                    'tosend' => true,
                                    'default' => 1
                                ] 
                            ]
                        ],
                        'method' => 'GET',
                        'key' => 'token',
                        'isJson' => false,
                        'isAsync' => false,
                        'printf' => 'formatResponse',
                        'withBody' => false,
                        'header' => [],
                        'footer' => []
                    ],
                    'service' => 'telegram_REST',
                    'token' => 'sync',
                    'url' => 'http://192.168.1.100/telecontrollo/bot/telegram_REST.php',
                    'serParams' => [
                        'token' => [
                            'tosend' => true,
                            'default' => 'sync'
                        ],
                        'variabile' => [
                            'tosend' => true,
                            'default' => 'ALL'
                        ],
                        'db' => [
                            'tosend' => true,
                            'default' => 1
                        ],
                        'tipodato' => [
                            'tosend' => true,
                            'default' => 1
                        ],
                        'delay' => [
                            'tosend' => true,
                            'default' => 168
                        ],
                        'tel' => [
                            'tosend' => true,
                            'default' => 1
                        ]                        
                    ],
                    'method' => 'GET',
                    'key' => 'token',
                    'isJson' => false,
                    'isAsync' => false,
                    'printFunction' => 'ServiceManager::formatResponse',
                    'withBody' => false,
                    'params' => [
                        [
                            'url' => 'http://192.168.1.100/telecontrollo/bot/telegram_REST.php?token=sync&variabile=30030&tel=0&db=1&tipodato=1&delay=168',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'sync'
                        ],
                        [
                            'url' => 'http://192.168.1.100/telecontrollo/bot/telegram_REST.php?token=sync&variabile=30040&tel=0&db=1&tipodato=1&delay=168',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'sync1'
                        ]
                        
                    ]
                ],
                'expected2' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"token":"sync","variabile":"ALL","delay":"168","tel":"0"},"header":["PROCEDURA SINCRONIZZAZIONE DB ENAS (DB1 => DB2)","DB1: 10.0.0.23\\\sql_server_spt ==> DB2: 10.0.10.43 | Delay (ore): 168","Elaborazione iniziata in data: 17\/03\/2021 13:18:48"],"body":{"SSCP_data.11.2":"Processata in 0,039 secondi | Records: 240 | Insert: 1 | Update: 0 | Presenti: 239 | Scartati: 0 | Cancellati: 0","SSCP_data.26.2":"Processata in 0,098 secondi | Records: 955 | Insert: 10 | Update: 0 | Presenti: 939 | Scartati: 6 | Cancellati: 0"},"footer":["Elaborazione finita in data: 17\/03\/2021 13:18:51","Tempo totale di elaborazione: 2,281 secondi","TOTALI: Records: 1.195 | Insert: 11 | Update: 0 | Presenti: 1178 | Scartati: 6 | Cancellati: 0"]}}',
                        '{"ok":true,"response":{"params":{"token":"sync","variabile":"ALL","delay":"168","tel":"0"},"header":["PROCEDURA SINCRONIZZAZIONE DB ENAS (DB1 => DB2)","DB1: 10.0.0.23\\\sql_server_spt ==> DB2: 10.0.10.43 | Delay (ore): 168","Elaborazione iniziata in data: 17\/03\/2021 13:18:48"],"body":{"SSCP_data.11.2":"Processata in 0,039 secondi | Records: 240 | Insert: 1 | Update: 0 | Presenti: 239 | Scartati: 0 | Cancellati: 0","SSCP_data.26.2":"Processata in 0,098 secondi | Records: 955 | Insert: 10 | Update: 0 | Presenti: 939 | Scartati: 6 | Cancellati: 0"},"footer":["Elaborazione finita in data: 17\/03\/2021 13:18:51","Tempo totale di elaborazione: 2,281 secondi","TOTALI: Records: 1.195 | Insert: 11 | Update: 0 | Presenti: 1178 | Scartati: 6 | Cancellati: 0"]}}'
                    ]
                ],
                'expected3' => [
                    'message' => '<b>Procedura sincronizzazione banche dati</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL . '<b>Totali:</b> Records: 2.390 | Insert: 22 | Update: 0 | Presenti: 2.356 | Scartati: 12 | Cancellati: 0',
                ],
                'expected4' => [
                    'echos' => [
                        '1.0) PID sync.0: Processata in 0,039 secondi | Records: 240 | Insert: 1 | Update: 0 | Presenti: 239 | Scartati: 0 | Cancellati: 0' . PHP_EOL,
                        '1.1) PID sync.1: Processata in 0,098 secondi | Records: 955 | Insert: 10 | Update: 0 | Presenti: 939 | Scartati: 6 | Cancellati: 0' . PHP_EOL,
                        '2.0) PID sync1.0: Processata in 0,039 secondi | Records: 240 | Insert: 1 | Update: 0 | Presenti: 239 | Scartati: 0 | Cancellati: 0' . PHP_EOL,
                        '2.1) PID sync1.1: Processata in 0,098 secondi | Records: 955 | Insert: 10 | Update: 0 | Presenti: 939 | Scartati: 6 | Cancellati: 0' . PHP_EOL
                    ]
                ]
            ],
            'sync multi var body' => [
                'args' => [
                    'service' => 'telegram_REST',
                    'token' => 'sync',
                    'postParams' => [
                        [
                            'token' => 'sync',
                            'variabile' => '30030',
                            'tel' => 0
                        ],
                        [
                            'token' => 'sync',
                            'variabile' => '30040',
                            'tel' => 0
                        ]
                    ],
                    'config' => [
                        'telegram_REST' => [
                            'host' => '192.168.1.100',
                            'path' => 'telecontrollo/bot/telegram_REST.php',
                            'params' => [
                                'sync' => [
                                    'token' => [
                                        'tosend' => true,
                                        'default' => 'sync'
                                    ],
                                    'variabile' => [
                                        'tosend' => true,
                                        'default' => 'ALL'
                                    ],
                                    'db' => [
                                        'tosend' => true,
                                        'default' => 1
                                    ],
                                    'tipodato' => [
                                        'tosend' => true,
                                        'default' => 1
                                    ],
                                    'delay' => [
                                        'tosend' => true,
                                        'default' => 168
                                    ],
                                    'tel' => [
                                        'tosend' => true,
                                        'default' => 1
                                    ]                               
                                ]
                            ],
                            'method' => 'GET',
                            'key' => 'token',
                            'isJson' => false,
                            'isAsync' => false,
                            'printf' => 'formatResponse',
                            'withBody' => true,
                            'globalSend' => false,
                            'header' => [],
                            'footer' => []
                        ]
                    ]
                ],
                'expected1' => [
                    'globalSend' => false,
                    'serConfig' => [
                        'host' => '192.168.1.100',
                        'path' => 'telecontrollo/bot/telegram_REST.php',
                        'params' => [
                            'sync' => [
                                'token' => [
                                    'tosend' => true,
                                    'default' => 'sync'
                                ],
                                'variabile' => [
                                    'tosend' => true,
                                    'default' => 'ALL'
                                ],
                                'db' => [
                                    'tosend' => true,
                                    'default' => 1
                                ],
                                'tipodato' => [
                                    'tosend' => true,
                                    'default' => 1
                                ],
                                'delay' => [
                                    'tosend' => true,
                                    'default' => 168
                                ],
                                'tel' => [
                                    'tosend' => true,
                                    'default' => 1
                                ]                               
                            ]
                        ],
                        'method' => 'GET',
                        'key' => 'token',
                        'isJson' => false,
                        'isAsync' => false,
                        'printf' => 'formatResponse',
                        'withBody' => true,
                        'globalSend' => false,
                        'header' => [],
                        'footer' => []
                    ],
                    'service' => 'telegram_REST',
                    'token' => 'sync',
                    'url' => 'http://192.168.1.100/telecontrollo/bot/telegram_REST.php',
                    'serParams' => [
                        'token' => [
                            'tosend' => true,
                            'default' => 'sync'
                        ],
                        'variabile' => [
                            'tosend' => true,
                            'default' => 'ALL'
                        ],
                        'db' => [
                            'tosend' => true,
                            'default' => 1
                        ],
                        'tipodato' => [
                            'tosend' => true,
                            'default' => 1
                        ],
                        'delay' => [
                            'tosend' => true,
                            'default' => 168
                        ],
                        'tel' => [
                            'tosend' => true,
                            'default' => 1
                        ]                        
                    ],
                    'method' => 'GET',
                    'key' => 'token',
                    'isJson' => false,
                    'isAsync' => false,
                    'printFunction' => 'ServiceManager::formatResponse',
                    'withBody' => true,
                    'params' => [
                        [
                            'url' => 'http://192.168.1.100/telecontrollo/bot/telegram_REST.php?token=sync&variabile=30030&tel=0&db=1&tipodato=1&delay=168',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'sync'
                        ],
                        [
                            'url' => 'http://192.168.1.100/telecontrollo/bot/telegram_REST.php?token=sync&variabile=30040&tel=0&db=1&tipodato=1&delay=168',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'sync1'
                        ]
                        
                    ]
                ],
                'expected2' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"token":"sync","variabile":"ALL","delay":"168","tel":"0"},"header":["PROCEDURA SINCRONIZZAZIONE DB ENAS (DB1 => DB2)","DB1: 10.0.0.23\\\sql_server_spt ==> DB2: 10.0.10.43 | Delay (ore): 168","Elaborazione iniziata in data: 17\/03\/2021 13:18:48"],"body":{"SSCP_data.11.2":"Processata in 0,039 secondi | Records: 240 | Insert: 1 | Update: 0 | Presenti: 239 | Scartati: 0 | Cancellati: 0","SSCP_data.26.2":"Processata in 0,098 secondi | Records: 955 | Insert: 10 | Update: 0 | Presenti: 939 | Scartati: 6 | Cancellati: 0"},"footer":["Elaborazione finita in data: 17\/03\/2021 13:18:51","Tempo totale di elaborazione: 2,281 secondi","TOTALI: Records: 1.195 | Insert: 11 | Update: 0 | Presenti: 1178 | Scartati: 6 | Cancellati: 0"]}}',
                        '{"ok":true,"response":{"params":{"token":"sync","variabile":"ALL","delay":"168","tel":"0"},"header":["PROCEDURA SINCRONIZZAZIONE DB ENAS (DB1 => DB2)","DB1: 10.0.0.23\\\sql_server_spt ==> DB2: 10.0.10.43 | Delay (ore): 168","Elaborazione iniziata in data: 17\/03\/2021 13:18:48"],"body":{"SSCP_data.11.2":"Processata in 0,039 secondi | Records: 240 | Insert: 1 | Update: 0 | Presenti: 239 | Scartati: 0 | Cancellati: 0","SSCP_data.26.2":"Processata in 0,098 secondi | Records: 955 | Insert: 10 | Update: 0 | Presenti: 939 | Scartati: 6 | Cancellati: 0"},"footer":["Elaborazione finita in data: 17\/03\/2021 13:18:51","Tempo totale di elaborazione: 2,281 secondi","TOTALI: Records: 1.195 | Insert: 11 | Update: 0 | Presenti: 1178 | Scartati: 6 | Cancellati: 0"]}}'
                    ]
                ],
                'expected3' => [
                    'message' => '<b>Procedura sincronizzazione banche dati</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . PHP_EOL . '<b>SSCP_data.11.2:</b> Processata in 0,039 secondi | Records: 240 | Insert: 1 | Update: 0 | Presenti: 239 | Scartati: 0 | Cancellati: 0' . PHP_EOL . '<b>SSCP_data.26.2:</b> Processata in 0,098 secondi | Records: 955 | Insert: 10 | Update: 0 | Presenti: 939 | Scartati: 6 | Cancellati: 0' . PHP_EOL . '<b>SSCP_data.11.2:</b> Processata in 0,039 secondi | Records: 240 | Insert: 1 | Update: 0 | Presenti: 239 | Scartati: 0 | Cancellati: 0' . PHP_EOL . '<b>SSCP_data.26.2:</b> Processata in 0,098 secondi | Records: 955 | Insert: 10 | Update: 0 | Presenti: 939 | Scartati: 6 | Cancellati: 0' . PHP_EOL . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL . '<b>Totali:</b> Records: 2.390 | Insert: 22 | Update: 0 | Presenti: 2.356 | Scartati: 12 | Cancellati: 0',
                ],
                'expected4' => [
                    'echos' => [
                        '1.0) PID sync.0: Processata in 0,039 secondi | Records: 240 | Insert: 1 | Update: 0 | Presenti: 239 | Scartati: 0 | Cancellati: 0' . PHP_EOL,
                        '1.1) PID sync.1: Processata in 0,098 secondi | Records: 955 | Insert: 10 | Update: 0 | Presenti: 939 | Scartati: 6 | Cancellati: 0' . PHP_EOL,
                        '2.0) PID sync1.0: Processata in 0,039 secondi | Records: 240 | Insert: 1 | Update: 0 | Presenti: 239 | Scartati: 0 | Cancellati: 0' . PHP_EOL,
                        '2.1) PID sync1.1: Processata in 0,098 secondi | Records: 955 | Insert: 10 | Update: 0 | Presenti: 939 | Scartati: 6 | Cancellati: 0' . PHP_EOL
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
        
        $actual1['serConfig'] = Reflections::getProperty($this->serviceManager, 'serConfig');
        $actual1['service'] = Reflections::getProperty($this->serviceManager, 'service');
        $actual1['globalSend'] = Reflections::getProperty($this->serviceManager, 'globalSend');
        $actual1['token'] = Reflections::getProperty($this->serviceManager, 'token');
        $actual1['url'] = Reflections::getProperty($this->serviceManager, 'url');
        $actual1['serParams'] = Reflections::getProperty($this->serviceManager, 'serParams');
        $actual1['method'] = Reflections::getProperty($this->serviceManager, 'method');
        $actual1['key'] = Reflections::getProperty($this->serviceManager, 'key');
        $actual1['isJson'] = Reflections::getProperty($this->serviceManager, 'isJson');
        $actual1['isAsync'] = Reflections::getProperty($this->serviceManager, 'isAsync');
        $actual1['isAsync'] = Reflections::getProperty($this->serviceManager, 'isAsync');
        $actual1['printFunction'] = Reflections::getProperty($this->serviceManager, 'printFunction');
        $actual1['withBody'] = Reflections::getProperty($this->serviceManager, 'withBody');
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
                    'params' => null,
                    'config' => null
                ]    
            ],
            'wrong token' => [
                'args' => [
                    'service' => 'tocsv',
                    'token' => 'pippo',
                    'params' => null,
                    'config' => null
                ]    
            ],
            'wrong service config' => [
                'args' => [
                    'service' => 'tocsv',
                    'token' => null,
                    'params' => null,
                    'config' => [
                        'telegram_REST' => [
                            'host' => '192.168.1.100',
                            'path' => 'telecontrollo/bot/telegram_REST.php',
                            'params' => [
                                'tocsv' => []
                            ],
                            'method' => 'GET',
                            'key' => null,
                            'isJson' => false,
                            'isAsync' => false,
                            'printf' => null,
                            'withBody' => false
                        ]
                    ]
                ]    
            ],
            'wrong url config' => [
                'args' => [
                    'service' => 'tocsv',
                    'token' => null,
                    'params' => null,
                    'config' => [
                        'tocsv' => [
                            'params' => [
                                'tocsv' => []
                            ],
                            'method' => 'GET',
                            'key' => null,
                            'isJson' => false,
                            'isAsync' => false,
                            'printf' => null,
                            'withBody' => false
                        ]
                    ]
                ]    
            ],
            'wrong params config' => [
                'args' => [
                    'service' => 'tocsv',
                    'token' => null,
                    'params' => null,
                    'config' => [
                        'tocsv' => [
                            'host' => '192.168.1.100',
                            'path' => 'telecontrollo/bot/telegram_REST.php',                            
                            'method' => 'GET',
                            'key' => null,
                            'isJson' => false,
                            'isAsync' => false,
                            'printf' => null,
                            'withBody' => false
                        ]
                    ]
                ]    
            ],
            'wrong params token config' => [
                'args' => [
                    'service' => 'tocsv',
                    'token' => null,
                    'params' => null,
                    'config' => [
                        'tocsv' => [
                            'host' => '192.168.1.100',
                            'path' => 'telecontrollo/bot/telegram_REST.php',
                            'params' => [
                                'pippo' => []
                            ],
                            'method' => 'GET',
                            'key' => null,
                            'isJson' => false,
                            'isAsync' => false,
                            'printf' => null,
                            'withBody' => false
                        ]
                    ]
                ]    
            ],
            'wrong method config' => [
                'args' => [
                    'service' => 'tocsv',
                    'token' => null,
                    'params' => null,
                    'config' => [
                        'tocsv' => [
                            'host' => '192.168.1.100',
                            'path' => 'telecontrollo/bot/telegram_REST.php',
                            'params' => [
                                'tocsv' => []
                            ],
                            'key' => null,
                            'isJson' => false,
                            'isAsync' => false,
                            'printf' => null,
                            'withBody' => false
                        ]
                    ]
                ]    
            ],
            'wrong method val config' => [
                'args' => [
                    'service' => 'tocsv',
                    'token' => null,
                    'params' => null,
                    'config' => [
                        'tocsv' => [
                            'host' => '192.168.1.100',
                            'path' => 'telecontrollo/bot/telegram_REST.php',
                            'params' => [
                                'tocsv' => []
                            ],
                            'method' => 'PIPPO',
                            'key' => null,
                            'isJson' => false,
                            'isAsync' => false,
                            'printf' => null,
                            'withBody' => false
                        ]
                    ]
                ]    
            ],
            'wrong key config' => [
                'args' => [
                    'service' => 'tocsv',
                    'token' => null,
                    'params' => null,
                    'config' => [
                        'tocsv' => [
                            'host' => '192.168.1.100',
                            'path' => 'telecontrollo/bot/telegram_REST.php',
                            'params' => [
                                'tocsv' => []
                            ],
                            'method' => 'GET',
                            'isJson' => false,
                            'isAsync' => false,
                            'printf' => null,
                            'withBody' => false
                        ]
                    ]
                ]    
            ],
            'wrong isJson config' => [
                'args' => [
                    'service' => 'tocsv',
                    'token' => null,
                    'params' => null,
                    'config' => [
                        'tocsv' => [
                            'host' => '192.168.1.100',
                            'path' => 'telecontrollo/bot/telegram_REST.php',
                            'params' => [
                                'tocsv' => []
                            ],
                            'method' => 'GET',
                            'key' => null,
                            'isAsync' => false,
                            'printf' => null,
                            'withBody' => false
                        ]
                    ]
                ]    
            ],
            'wrong isAsync config' => [
                'args' => [
                    'service' => 'tocsv',
                    'token' => null,
                    'params' => null,
                    'config' => [
                        'tocsv' => [
                            'host' => '192.168.1.100',
                            'path' => 'telecontrollo/bot/telegram_REST.php',
                            'params' => [
                                'tocsv' => []
                            ],
                            'method' => 'GET',
                            'key' => null,
                            'isJson' => false,
                            'printf' => null,
                            'withBody' => false
                        ]
                    ]
                ]    
            ],
            'wrong printf config' => [
                'args' => [
                    'service' => 'tocsv',
                    'token' => null,
                    'params' => null,
                    'config' => [
                        'tocsv' => [
                            'host' => '192.168.1.100',
                            'path' => 'telecontrollo/bot/telegram_REST.php',
                            'params' => [
                                'tocsv' => []
                            ],
                            'method' => 'GET',
                            'key' => null,
                            'isJson' => false,
                            'isAsync' => false,
                            'withBody' => false
                        ]
                    ]
                ]    
            ],
            'wrong withBody config' => [
                'args' => [
                    'service' => 'tocsv',
                    'token' => null,
                    'params' => null,
                    'config' => [
                        'tocsv' => [
                            'host' => '192.168.1.100',
                            'path' => 'telecontrollo/bot/telegram_REST.php',
                            'params' => [
                                'tocsv' => []
                            ],
                            'method' => 'GET',
                            'key' => null,
                            'isJson' => false,
                            'isAsync' => false,
                            'printf' => null
                        ]
                    ]
                ]    
            ],
            'post with no params' => [
                'args' => [
                    'service' => 'tocsv',
                    'token' => null,
                    'params' => [],
                    'config' => [
                        'tocsv' => [
                            'host' => '192.168.1.100',
                            'path' => 'telecontrollo/bot/telegram_REST.php',
                            'params' => [
                                'tocsv' => []
                            ],
                            'method' => 'POST',
                            'key' => null,
                            'isJson' => false,
                            'isAsync' => false,
                            'printf' => null,
                            'withBody' => false
                        ]
                    ]
                ]    
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager
     * @dataProvider constructorExceptionProvider
     */
    public function testConstructorException($args)
    {
        $this->expectException(\Exception::class);
        
        Reflections::invokeConstructor($this->serviceManager, $args);
    }
    
}
