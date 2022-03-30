<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi\tests\Unit;

use PHPUnit\Framework\TestCase;
use vaniacarta74\Scarichi\ServiceManager;
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
                        'host' => 'LOCALHOST',
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
                    'url' => TOCSVURL,
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
                            'url' => TOCSVURL . '?full=1&field=volume',
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
                        'host' => 'LOCALHOST',
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
                    'url' => TOCSVURL,
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
                            'url' => TOCSVURL . '?var=30030&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019&full=1&field=volume',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => null
                        ]
                    ]
                ],
                'expected2' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}'
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
                        'host' => 'LOCALHOST',
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
                    'url' => TOCSVURL,
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
                            'url' => TOCSVURL . '?var=30030&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019&full=1&field=volume',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => '0'
                        ]
                    ]
                ],
                'expected2' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}'
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
                        'host' => 'LOCALHOST',
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
                    'url' => TOCSVURL,
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
                            'url' => TOCSVURL . '?var=30040&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019&full=1&field=volume',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => '0'
                        ],
                        [
                            'url' => TOCSVURL . '?var=30030&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019&full=1&field=volume',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => '1'
                        ]
                        
                    ]
                ],
                'expected2' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"var":"30040","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}',
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30040<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}'
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
                        'host' => 'LOCALHOST',
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
                    'url' => TOCSVURL,
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
                            'url' => TOCSVURL . '?full=1&field=volume',
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
                    'service' => TOCSVURL . '?var=30030&datefrom=01/01/2019&dateto=02/01/2019&full=1&field=volume',
                    'token' => null,
                    'postParams' => null,
                    'config' => null
                ],
                'expected1' => [
                    'globalSend' => GLOBALMSG,
                    'serConfig' => null,
                    'service' => TOCSVURL . '?var=30030&datefrom=01/01/2019&dateto=02/01/2019&full=1&field=volume',
                    'token' => TOCSVURL,
                    'url' => TOCSVURL . '?var=30030&datefrom=01/01/2019&dateto=02/01/2019&full=1&field=volume',
                    'serParams' => [],
                    'method' => 'GET',
                    'key' => null,
                    'isJson' => false,
                    'isAsync' => false,
                    'printFunction' => null,
                    'withBody' => false,
                    'params' => [
                        [
                            'url' => TOCSVURL . '?var=30030&datefrom=01/01/2019&dateto=02/01/2019&full=1&field=volume',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => null
                        ]
                    ]
                ],
                'expected2' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}'
                    ]
                ],
                'expected3' => [
                    'message' => '<b>http://' . LOCALHOST . '/scarichi/tocsv.php</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL,
                ],
                'expected4' => [
                    'echos' => []
                ]
            ],
            'url service with token' => [
                'args' => [
                    'service' => TOCSVURL . '?var=30030&datefrom=01/01/2019&dateto=02/01/2019&full=1&field=volume',
                    'token' => 'Esportazione volumi su csv',
                    'postParams' => null,
                    'config' => null
                ],
                'expected1' => [
                    'globalSend' => GLOBALMSG,
                    'serConfig' => null,
                    'service' => TOCSVURL . '?var=30030&datefrom=01/01/2019&dateto=02/01/2019&full=1&field=volume',
                    'token' => 'Esportazione volumi su csv',
                    'url' => TOCSVURL . '?var=30030&datefrom=01/01/2019&dateto=02/01/2019&full=1&field=volume',
                    'serParams' => [],
                    'method' => 'GET',
                    'key' => null,
                    'isJson' => false,
                    'isAsync' => false,
                    'printFunction' => null,
                    'withBody' => false,
                    'params' => [
                        [
                            'url' => TOCSVURL . '?var=30030&datefrom=01/01/2019&dateto=02/01/2019&full=1&field=volume',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => null
                        ]
                    ]
                ],
                'expected2' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}'
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
                    'service' => TOCSVURL,
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
                    'service' => TOCSVURL,
                    'token' => 'Esportazione volumi su csv',
                    'url' => TOCSVURL,
                    'serParams' => [],
                    'method' => 'GET',
                    'key' => null,
                    'isJson' => false,
                    'isAsync' => false,
                    'printFunction' => null,
                    'withBody' => false,
                    'params' => [
                        [
                            'url' => TOCSVURL . '?var=30030&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019&full=1&field=volume',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => null
                        ]
                    ]
                ],
                'expected2' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}'
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
                            'host' => 'LOCALHOST',
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
                        'host' => 'LOCALHOST',
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
                    'url' => TOCSVURL,
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
                            'url' => TOCSVURL,
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
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}'
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
                            'host' => 'LOCALHOST',
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
                        'host' => 'LOCALHOST',
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
                    'url' => TOCSVURL,
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
                            'url' => TOCSVURL . '?token=withToken&var=30030&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'withToken'
                        ]
                    ]
                ],
                'expected2' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}'
                    ]
                ],
                'expected3' => [
                    'message' => '<b>withToken</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . PHP_EOL . 'Elaborazione dati <b>Volume</b> variabile <b>30030</b> dal <b>01/01/2019</b> al <b>02/01/2019</b> avvenuta con successo in <b>|</b>. File CSV <b>full</b> esportati: <b>|</b> (tocsv@' . REALHOST . ')' . PHP_EOL . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL,
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
                            'host' => 'LOCALHOST',
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
                        'host' => 'LOCALHOST',
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
                    'url' => TOCSVURL,
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
                            'url' => TOCSVURL . '?token=withToken&var=30030&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019',                            
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'token0'
                        ],
                        [
                            'url' => TOCSVURL . '?var=30040&token=withToken&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'token1'
                        ]
                    ]
                ],
                'expected2' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}',
                        '{"ok":true,"response":{"params":{"var":"30040","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30040<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}'
                    ]
                ],
                'expected3' => [
                    'message' => '<b>withToken</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . PHP_EOL . 'Elaborazione dati <b>Volume</b> variabile <b>30030</b> dal <b>01/01/2019</b> al <b>02/01/2019</b> avvenuta con successo in <b>|</b>. File CSV <b>full</b> esportati: <b>|</b> (tocsv@' . REALHOST . ')' . PHP_EOL . 'Elaborazione dati <b>Volume</b> variabile <b>30040</b> dal <b>01/01/2019</b> al <b>02/01/2019</b> avvenuta con successo in <b>|</b>. File CSV <b>full</b> esportati: <b>|</b> (tocsv@' . REALHOST . ')' . PHP_EOL . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL,
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
                            'host' => 'LOCALHOST',
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
                        'host' => 'LOCALHOST',
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
                    'url' => TOCSVURL,
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
                            'url' => TOCSVURL . '?token=withToken&var=30030&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019',                            
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'withToken'
                        ],
                        [
                            'url' => TOCSVURL . '?var=30040&token=withToken&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'withToken.1'
                        ]
                    ]
                ],
                'expected2' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}',
                        '{"ok":true,"response":{"params":{"var":"30040","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30040<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}'
                    ]
                ],
                'expected3' => [
                    'message' => '<b>withToken</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . PHP_EOL . 'Elaborazione dati <b>Volume</b> variabile <b>30030</b> dal <b>01/01/2019</b> al <b>02/01/2019</b> avvenuta con successo in <b>|</b>. File CSV <b>full</b> esportati: <b>|</b> (tocsv@' . REALHOST . ')' . PHP_EOL . 'Elaborazione dati <b>Volume</b> variabile <b>30040</b> dal <b>01/01/2019</b> al <b>02/01/2019</b> avvenuta con successo in <b>|</b>. File CSV <b>full</b> esportati: <b>|</b> (tocsv@' . REALHOST . ')' . PHP_EOL . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL,
                ],
                'expected4' => [
                    'echos' => [
                        '1) PID withToken: Elaborazione dati Volume variabile 30030 dal 01/01/2019 al 02/01/2019 avvenuta con successo in | sec. File CSV full esportati: |',
                        '2) PID withToken.1: Elaborazione dati Volume variabile 30040 dal 01/01/2019 al 02/01/2019 avvenuta con successo in | sec. File CSV full esportati: |'
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
                            'host' => 'LOCALHOST',
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
                        'host' => 'LOCALHOST',
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
                    'url' => TOCSVURL,
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
                            'url' => TOCSVURL . '?var=30030&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019',                            
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'pippo'
                        ],
                        [
                            'url' => TOCSVURL . '?var=30040&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'pippo.1'
                        ]
                    ]
                ],
                'expected2' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}',
                        '{"ok":true,"response":{"params":{"var":"30040","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30040<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}'
                    ]
                ],
                'expected3' => [
                    'message' => '<b>test</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . PHP_EOL . 'Elaborazione dati <b>Volume</b> variabile <b>30030</b> dal <b>01/01/2019</b> al <b>02/01/2019</b> avvenuta con successo in <b>|</b>. File CSV <b>full</b> esportati: <b>|</b> (tocsv@' . REALHOST . ')' . PHP_EOL . 'Elaborazione dati <b>Volume</b> variabile <b>30040</b> dal <b>01/01/2019</b> al <b>02/01/2019</b> avvenuta con successo in <b>|</b>. File CSV <b>full</b> esportati: <b>|</b> (tocsv@' . REALHOST . ')' . PHP_EOL . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL,
                ],
                'expected4' => [
                    'echos' => [
                        '1) PID pippo: Elaborazione dati Volume variabile 30030 dal 01/01/2019 al 02/01/2019 avvenuta con successo in | sec. File CSV full esportati: |',
                        '2) PID pippo.1: Elaborazione dati Volume variabile 30040 dal 01/01/2019 al 02/01/2019 avvenuta con successo in | sec. File CSV full esportati: |'
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
                        'host' => 'REMOTE_HOST',
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
                    'url' => TELRESTURL,
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
                            'url' => TELRESTURL . '?token=sync&variabile=ALL&tel=0&db=1&tipodato=1&delay=168',
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
                        'host' => 'REMOTE_HOST',
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
                    'url' => TELRESTURL,
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
                            'url' => TELRESTURL . '?token=sync&variabile=30030&tel=0&db=1&tipodato=1&delay=168',
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
                        'host' => 'REMOTE_HOST',
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
                    'url' => TELRESTURL,
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
                            'url' => TELRESTURL . '?token=sync&variabile=30030&tel=0&db=1&tipodato=1&delay=168',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'sync'
                        ],
                        [
                            'url' => TELRESTURL . '?token=sync&variabile=30040&tel=0&db=1&tipodato=1&delay=168',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'sync.1'
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
                        '2.0) PID sync.1.0: Processata in 0,039 secondi | Records: 240 | Insert: 1 | Update: 0 | Presenti: 239 | Scartati: 0 | Cancellati: 0' . PHP_EOL,
                        '2.1) PID sync.1.1: Processata in 0,098 secondi | Records: 955 | Insert: 10 | Update: 0 | Presenti: 939 | Scartati: 6 | Cancellati: 0' . PHP_EOL
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
                            'host' => 'REMOTE_HOST',
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
                        'host' => 'REMOTE_HOST',
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
                    'url' => TELRESTURL,
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
                            'url' => TELRESTURL . '?token=sync&variabile=30030&tel=0&db=1&tipodato=1&delay=168',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'sync'
                        ],
                        [
                            'url' => TELRESTURL . '?token=sync&variabile=30040&tel=0&db=1&tipodato=1&delay=168',
                            'method' => 'GET',
                            'params' => [],
                            'isJson' => false,
                            'key' => 'sync.1'
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
                        '2.0) PID sync.1.0: Processata in 0,039 secondi | Records: 240 | Insert: 1 | Update: 0 | Presenti: 239 | Scartati: 0 | Cancellati: 0' . PHP_EOL,
                        '2.1) PID sync.1.1: Processata in 0,098 secondi | Records: 955 | Insert: 10 | Update: 0 | Presenti: 939 | Scartati: 6 | Cancellati: 0' . PHP_EOL
                    ]
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::__construct
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
        
        $this->assertMatchesRegularExpression('/^[0-9]{4}[-][0-9]{2}[-][0-9]{2}[\s][0-9]{2}[:][0-9]{2}[:][0-9]{2}[\.][0-9]{6}$/', $start);
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
                            'host' => 'REMOTE_HOST',
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
                            'host' => 'REMOTE_HOST',
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
                            'host' => 'REMOTE_HOST',
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
                            'host' => 'REMOTE_HOST',
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
                            'host' => 'REMOTE_HOST',
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
                            'host' => 'REMOTE_HOST',
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
                            'host' => 'REMOTE_HOST',
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
                            'host' => 'REMOTE_HOST',
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
                            'host' => 'REMOTE_HOST',
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
                            'host' => 'REMOTE_HOST',
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
                            'host' => 'REMOTE_HOST',
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
    
    /**
     * @coversNothing
     */
    public function checkServiceResponseProvider() : array
    {
        $data = [
            'no response level 1' => [
                'args' => [
                    'response' => '',
                    'debug' => 1
                ],
                'expected' => [
                    'Elaborazione fallita. Verificare il log degli errori (' . realpath(LOG_PATH) . '/' . ERROR_LOG . ').'
                ]
            ],
            'no response level 2' => [
                'args' => [
                    'response' => '',
                    'debug' => 2
                ],
                'expected' => [
                    'Elaborazione fallita.'
                ]
            ],
            'response not ok' => [
                'args' => [
                    'response' => '{"ok":false,"codice errore":400,"descrizione errore":"Hai sbagliato"}',
                    'debug' => 2
                ],
                'expected' => [
                    'Elaborazione fallita.'
                ]
            ],
            'response ok' => [
                'args' => [
                    'response' => '{"ok":true,"response":{"body":["Risposta esatta"]}}',
                    'debug' => 1
                ],
                'expected' => [
                    'Risposta esatta'
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::checkServiceResponse
     * @dataProvider checkServiceResponseProvider
     */
    public function testCheckServiceResponseEquals(array $args, array $expected) : void
    {
        $actual = Reflections::invokeStaticMethod($this->serviceManager, 'checkServiceResponse', $args);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function formatResponseProvider() : array
    {
        $data = [
            'no response' => [
                'response' => '',
                'i' => 1,
                'key' => 'Topolino',
                'expected' => '1) PID Topolino: Elaborazione fallita.' . PHP_EOL
            ],
            'response not ok' => [
                'response' => '{"ok":false,"codice errore":400,"descrizione errore":"Hai sbagliato"}',
                'i' => 1,
                'key' => 'Topolino',
                'expected' => '1) PID Topolino: Elaborazione fallita.' . PHP_EOL
            ],
            'one response' => [
                'response' => '{"ok":true,"response":{"body":["Risposta esatta"]}}',
                'i' => 1,
                'key' => 'L\'amico di Topolino',
                'expected' => '1) PID L\'amico di Topolino: Risposta esatta' . PHP_EOL
            ],
            'more response' => [
                'response' => '{"ok":true,"response":{"body":["Prima risposta","Seconda risposta"]}}',
                'i' => 1,
                'key' => 'L\'amico di Topolino',
                'expected' => '1.0) PID L\'amico di Topolino.0: Prima risposta' . PHP_EOL . '1.1) PID L\'amico di Topolino.1: Seconda risposta'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::formatResponse
     * @dataProvider formatResponseProvider
     */
    public function testFormatResponseEquals(string $response, int $i, string $key, string $expected) : void
    {
        $actual = $this->serviceManager::formatResponse($response, $i, $key);
        
        $this->assertStringContainsString($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function callServiceProvider() : array
    {
        $data = [
            'single' => [
                'params' => [
                    [
                        'url' => TOCSVURL . '?var=30030&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019&full=1&field=volume',
                        'method' => 'GET',
                        'params' => [],
                        'isJson' => false,
                        'key' => null
                    ]
                ],
                'isAsync' => false,
                'printFunction' => 'ServiceManager::formatResponse',
                'expected' => [
                    '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}'
                ]
            ],
            'multi' => [
                'params' => [
                    [
                        'url' => TOCSVURL . '?var=30040&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019&full=1&field=volume',
                        'method' => 'GET',
                        'params' => [],
                        'isJson' => false,
                        'key' => '0'
                    ],
                    [
                        'url' => TOCSVURL . '?var=30030&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019&full=1&field=volume',
                        'method' => 'GET',
                        'params' => [],
                        'isJson' => false,
                        'key' => '1'
                    ]

                ],
                'isAsync' => true,
                'printFunction' => 'ServiceManager::formatResponse',
                'expected' => [
                    '{"ok":true,"response":{"params":{"var":"30040","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}',
                    '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30040<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: |"]}}'
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::callService
     * @dataProvider callServiceProvider
     */
    public function testCallServiceStringContainsString(array $setParams, bool $async, string $printFunction, array $responses) : void
    {
        Reflections::setProperty($this->serviceManager, 'params', $setParams);
        Reflections::setProperty($this->serviceManager, 'isAsync', $async);
        Reflections::setProperty($this->serviceManager, 'printFunction', $printFunction);
        
        Reflections::invokeMethod($this->serviceManager, 'callService');
        
        $actuals = Reflections::getProperty($this->serviceManager, 'responses');
        
        $actual = implode('', $actuals);
        
        foreach ($responses as $response) {
            $expecteds = explode('|', $response);
            foreach ($expecteds as $expected) {
                $this->assertStringContainsString($expected, $actual);                
            }            
        }
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::callService
     */
    public function testCallServiceEqual() : void
    {
        $expected = [];
        
        Reflections::setProperty($this->serviceManager, 'params', []);
        Reflections::setProperty($this->serviceManager, 'isAsync', false);
        Reflections::setProperty($this->serviceManager, 'printFunction', 'ServiceManager::formatResponse');
        
        Reflections::invokeMethod($this->serviceManager, 'callService');
        
        $actual = Reflections::getProperty($this->serviceManager, 'responses');
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setStart
     */
    public function testSetStartRegExp() : void
    {
        Reflections::invokeMethod($this->serviceManager, 'setStart');
        
        $actual = Reflections::getProperty($this->serviceManager, 'start');
        
        $this->assertMatchesRegularExpression('/^[0-9]{4}[-][0-9]{2}[-][0-9]{2}[\s][0-9]{2}[:][0-9]{2}[:][0-9]{2}[\.][0-9]{6}$/', $actual);
    }
    
    /**
     * @coversNothing
     */
    public function setGlobalSendProvider() : array
    {
        $data = [
            'global' => [
                'serConfig' => [
                    'globalSend' => false
                ],
                'expected' => false
            ],
            'not global' => [
                'serConfig' => null,
                'expected' => GLOBALMSG
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setGlobalSend
     * @dataProvider setGlobalSendProvider
     */
    public function testSetGlobalSendStringContainsString(?array $serConfig, bool $expected) : void
    {
        Reflections::setProperty($this->serviceManager, 'serConfig', $serConfig);
        
        Reflections::invokeMethod($this->serviceManager, 'setGlobalSend');
        
        $actual = Reflections::getProperty($this->serviceManager, 'globalSend');
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function checkServiceProvider() : array
    {
        $data = [
            'custom' => [
                'args' => [
                    'rawService' => 'tocsv',
                    'rawConfig' => [
                        'tocsv' => [
                            'pippo'
                        ]
                    ]                    
                ],                
                'expected' => [
                    'service' => 'tocsv',
                    'serConfig' => [
                        'pippo'
                    ]
                ]
            ],
            'standard' => [
                'args' => [
                    'rawService' => 'tocsv',
                    'rawConfig' => null       
                ],                
                'expected' => [
                    'service' => 'tocsv',
                    'serConfig' => [
                        'host' => 'LOCALHOST',
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
                    ]
                ]
            ],
            'url' => [
                'args' => [
                    'rawService' => 'http://www.pippo.com',
                    'rawConfig' => null        
                ],                
                'expected' => [
                    'service' => 'http://www.pippo.com',
                    'serConfig' => null
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::checkService
     * @dataProvider checkServiceProvider
     */
    public function testCheckServiceEquals(array $args, array $expected) : void
    {
        Reflections::invokeMethod($this->serviceManager, 'checkService', $args);
        
        $actual = Reflections::getProperty($this->serviceManager, 'service');
        
        $this->assertEquals($expected['service'], $actual);
        
        $actual = Reflections::getProperty($this->serviceManager, 'serConfig');
        
        $this->assertEquals($expected['serConfig'], $actual);
    }
    
    /**
     * @coversNothing
     */
    public function checkServiceExceptionProvider() : array
    {
        $data = [
            'custom' => [
                'args' => [
                    'rawService' => 'pippo',
                    'rawConfig' => [
                        'tocsv' => [
                            'pippo'
                        ]
                    ]                    
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::checkService
     * @dataProvider checkServiceExceptionProvider
     */
    public function testCheckServiceExceptionEquals(array $args) : void
    {
        $this->expectException(\Exception::class);
        
        Reflections::invokeMethod($this->serviceManager, 'checkService', $args);        
    }
    
    /**
     * @coversNothing
     */
    public function setUrlProvider() : array
    {
        $data = [
            'localhost' => [
                'service' => 'pippo',
                'serConfig' => [
                    'host' => 'LOCALHOST',
                    'path' => 'scarichi/tocsv.php'           
                ],                
                'expected' => TOCSVURL
            ],
            'remote' => [
                'service' => 'pippo',
                'serConfig' => [
                    'host' => 'REMOTE_HOST',
                    'path' => 'scarichi/tocsv.php'           
                ],                
                'expected' => 'http://' . REMOTE_HOST . '/scarichi/tocsv.php'
            ],
            'custom' => [
                'service' => 'pippo',
                'serConfig' => [
                    'host' => '192.168.1.101',
                    'path' => 'scarichi/tocsv.php'           
                ],                
                'expected' => 'http://192.168.1.101/scarichi/tocsv.php'
            ],
            'service' => [
                'service' => 'http://192.168.1.101/scarichi/tocsv.php',
                'serConfig' => null,                
                'expected' => 'http://192.168.1.101/scarichi/tocsv.php'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setUrl
     * @dataProvider setUrlProvider
     */
    public function testSetUrlEquals(string $service, ?array $serConfig, string $expected) : void
    {
        Reflections::setProperty($this->serviceManager, 'service', $service);
        Reflections::setProperty($this->serviceManager, 'serConfig', $serConfig);
        
        Reflections::invokeMethod($this->serviceManager, 'setUrl');
        
        $actual = Reflections::getProperty($this->serviceManager, 'url');
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function setUrlExceptionProvider() : array
    {
        $data = [
            'wrong config' => [
                'service' => 'pippo',
                'serConfig' => [
                    'pippo' => 'remote',
                    'pluto' => '/scarichi/tocsv.php'           
                ]               
            ],
            'wrong url' => [
                'service' => 'pippo',
                'serConfig' => [
                    'host' => 'remo%tes',
                    'path' => '/scarichi/tocsv.php'           
                ]               
            ],
            'wrong service' => [
                'service' => 'http%://192.168.1.101/scar%ichi/tocsv.php',
                'serConfig' => null  
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setUrl
     * @dataProvider setUrlExceptionProvider
     */
    public function testSetUrlExceptionEquals(string $service, ?array $serConfig) : void
    {
        Reflections::setProperty($this->serviceManager, 'service', $service);
        Reflections::setProperty($this->serviceManager, 'serConfig', $serConfig);
        
        $this->expectException(\Exception::class);
        
        Reflections::invokeMethod($this->serviceManager, 'setUrl');        
    }
    
    /**
     * @coversNothing
     */
    public function setTokenProvider() : array
    {
        $data = [
            'only service' => [
                'args' => [
                    'token' => null
                ],
                'service' => 'tocsv',
                'serConfig' => null,                
                'expected' => 'tocsv'
            ],
            'url service' => [
                'args' => [
                    'token' => null
                ],
                'service' => 'http://192.168.1.101/scarichi/tocsv.php?var=30030',
                'serConfig' => null,                
                'expected' => 'http://192.168.1.101/scarichi/tocsv.php'
            ],
            'token' => [
                'args' => [
                    'token' => 'pippo'
                ],
                'service' => 'tocsv',
                'serConfig' => null,                
                'expected' => 'pippo'
            ],
            'standard' => [
                'args' => [
                    'token' => null
                ],
                'service' => 'tocsv',
                'serConfig' => [
                    'host' => 'LOCALHOST',
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
                'expected' => 'tocsv'
            ],
            'token standard' => [
                'args' => [
                    'token' => 'pippo'
                ],
                'service' => 'tocsv',
                'serConfig' => [
                    'host' => 'LOCALHOST',
                    'path' => 'scarichi/tocsv.php',
                    'params' => [
                        'pippo' => [
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
                'expected' => 'pippo'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setToken
     * @dataProvider setTokenProvider
     */
    public function testSetTokenEquals(array $args, string $service, ?array $serConfig, string $expected) : void
    {
        Reflections::setProperty($this->serviceManager, 'service', $service);
        Reflections::setProperty($this->serviceManager, 'serConfig', $serConfig);
        
        Reflections::invokeMethod($this->serviceManager, 'setToken', $args);
        
        $actual = Reflections::getProperty($this->serviceManager, 'token');
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function setTokenExceptionProvider() : array
    {
        $data = [
            'token standard' => [
                'args' => [
                    'token' => 'pippo'
                ],
                'service' => 'tocsv',
                'serConfig' => [
                    'host' => 'LOCALHOST',
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
                ]                
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setToken
     * @dataProvider setTokenExceptionProvider
     */
    public function testSetTokenExceptionEquals(array $args, string $service, ?array $serConfig) : void
    {
        Reflections::setProperty($this->serviceManager, 'service', $service);
        Reflections::setProperty($this->serviceManager, 'serConfig', $serConfig);
        
        $this->expectException(\Exception::class);
        
        Reflections::invokeMethod($this->serviceManager, 'setToken', $args);        
    }
    
    /**
     * @coversNothing
     */
    public function setSerParamsProvider() : array
    {
        $data = [
            'config not set' => [
                'token' => 'pippo',
                'serConfig' => null,                
                'expected' => []
            ],
            'standard' => [
                'token' => 'tocsv',
                'serConfig' => [
                    'host' => 'LOCALHOST',
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
                'expected' => [
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
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setSerParams
     * @dataProvider setSerParamsProvider
     */
    public function testSetSerParamsEquals(string $token, ?array $serConfig, array $expected) : void
    {
        Reflections::setProperty($this->serviceManager, 'token', $token);
        Reflections::setProperty($this->serviceManager, 'serConfig', $serConfig);
        
        Reflections::invokeMethod($this->serviceManager, 'setSerParams');
        
        $actual = Reflections::getProperty($this->serviceManager, 'serParams');
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function setSerParamsExceptionProvider() : array
    {
        $data = [
            'token not match' => [
                'token' => 'pippo',
                'serConfig' => [
                    'host' => 'LOCALHOST',
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
                ]                
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setSerParams
     * @dataProvider setSerParamsExceptionProvider
     */
    public function testSetSerParamsExceptionEquals(string $token, ?array $serConfig) : void
    {
        Reflections::setProperty($this->serviceManager, 'token', $token);
        Reflections::setProperty($this->serviceManager, 'serConfig', $serConfig);
        
        $this->expectException(\Exception::class);
        
        Reflections::invokeMethod($this->serviceManager, 'setSerParams');        
    }
    
    /**
     * @coversNothing
     */
    public function setMethodProvider() : array
    {
        $data = [
            'no config' => [
                'serConfig' => null,
                'expected' => 'GET'
            ],
            'standard' => [
                'serConfig' => [
                    'host' => 'LOCALHOST',
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
                    'method' => 'DELETE',
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
                'expected' => 'DELETE'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setMethod
     * @dataProvider setMethodProvider
     */
    public function testSetMethodEquals(?array $serConfig, string $expected) : void
    {
        Reflections::setProperty($this->serviceManager, 'serConfig', $serConfig);
        
        Reflections::invokeMethod($this->serviceManager, 'setMethod');
        
        $actual = Reflections::getProperty($this->serviceManager, 'method');
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function setMethodExceptionProvider() : array
    {
        $data = [
            'method no HTTP' => [
                'serConfig' => [
                    'host' => 'LOCALHOST',
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
                    'method' => 'PIPPO',
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
                ]                
            ],
            'method no in config' => [
                'serConfig' => [
                    'host' => 'LOCALHOST',
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
                ]                
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setMethod
     * @dataProvider setMethodExceptionProvider
     */
    public function testSetMethodExceptionEquals(?array $serConfig) : void
    {
        Reflections::setProperty($this->serviceManager, 'serConfig', $serConfig);
        
        $this->expectException(\Exception::class);
        
        Reflections::invokeMethod($this->serviceManager, 'setMethod');        
    }
    
    /**
     * @coversNothing
     */
    public function setKeyProvider() : array
    {
        $data = [
            'no config' => [
                'serConfig' => null,
                'expected' => null
            ],
            'standard' => [
                'serConfig' => [
                    'host' => 'LOCALHOST',
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
                'expected' => 'id'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setKey
     * @dataProvider setKeyProvider
     */
    public function testSetKeyEquals(?array $serConfig, ?string $expected) : void
    {
        Reflections::setProperty($this->serviceManager, 'serConfig', $serConfig);
        
        Reflections::invokeMethod($this->serviceManager, 'setKey');
        
        $actual = Reflections::getProperty($this->serviceManager, 'key');
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function setKeyExceptionProvider() : array
    {
        $data = [            
            'key no in config' => [
                'serConfig' => [
                    'host' => 'LOCALHOST',
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
                ]                
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setKey
     * @dataProvider setKeyExceptionProvider
     */
    public function testSetKeyExceptionEquals(?array $serConfig) : void
    {
        Reflections::setProperty($this->serviceManager, 'serConfig', $serConfig);
        
        $this->expectException(\Exception::class);
        
        Reflections::invokeMethod($this->serviceManager, 'setKey');        
    }
    
    /**
     * @coversNothing
     */
    public function setIsJsonProvider() : array
    {
        $data = [
            'no config' => [
                'serConfig' => null,
                'expected' => false
            ],
            'standard' => [
                'serConfig' => [
                    'host' => 'LOCALHOST',
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
                    'isJson' => true,
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
                'expected' => true
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setIsJson
     * @dataProvider setIsJsonProvider
     */
    public function testSetIsJsonEquals(?array $serConfig, bool $expected) : void
    {
        Reflections::setProperty($this->serviceManager, 'serConfig', $serConfig);
        
        Reflections::invokeMethod($this->serviceManager, 'setIsJson');
        
        $actual = Reflections::getProperty($this->serviceManager, 'isJson');
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function setIsJsonExceptionProvider() : array
    {
        $data = [            
            'isJson no in config' => [
                'serConfig' => [
                    'host' => 'LOCALHOST',
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
                ]                
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setIsJson
     * @dataProvider setIsJsonExceptionProvider
     */
    public function testSetIsJsonExceptionEquals(?array $serConfig) : void
    {
        Reflections::setProperty($this->serviceManager, 'serConfig', $serConfig);
        
        $this->expectException(\Exception::class);
        
        Reflections::invokeMethod($this->serviceManager, 'setIsJson');        
    }
    
    /**
     * @coversNothing
     */
    public function setIsAsyncProvider() : array
    {
        $data = [
            'no config' => [
                'serConfig' => null,
                'expected' => false
            ],
            'standard' => [
                'serConfig' => [
                    'host' => 'LOCALHOST',
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
                    'isJson' => true,
                    'isAsync' => false,
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
                'expected' => false
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setIsAsync
     * @dataProvider setIsAsyncProvider
     */
    public function testSetIsAsyncEquals(?array $serConfig, bool $expected) : void
    {
        Reflections::setProperty($this->serviceManager, 'serConfig', $serConfig);
        
        Reflections::invokeMethod($this->serviceManager, 'setIsAsync');
        
        $actual = Reflections::getProperty($this->serviceManager, 'isAsync');
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function setIsAsyncExceptionProvider() : array
    {
        $data = [            
            'isAsync no in config' => [
                'serConfig' => [
                    'host' => 'LOCALHOST',
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
                    'isJson' => true,
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
                ]                
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setIsAsync
     * @dataProvider setIsAsyncExceptionProvider
     */
    public function testSetIsAsyncExceptionEquals(?array $serConfig) : void
    {
        Reflections::setProperty($this->serviceManager, 'serConfig', $serConfig);
        
        $this->expectException(\Exception::class);
        
        Reflections::invokeMethod($this->serviceManager, 'setIsAsync');        
    }
    
    /**
     * @coversNothing
     */
    public function setPrintFunctionProvider() : array
    {
        $data = [
            'no config' => [
                'serConfig' => null,
                'expected' => null
            ],
            'no def' => [
                'serConfig' => [
                    'printf' => null
                ],
                'expected' => null
            ],
            'not exist' => [
                'serConfig' => [
                    'printf' => 'pippo'
                ],
                'expected' => null
            ],
            'standard' => [
                'serConfig' => [
                    'host' => 'LOCALHOST',
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
                    'isJson' => true,
                    'isAsync' => false,
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
                'expected' => 'ServiceManager::formatResponse'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setPrintFunction
     * @dataProvider setPrintFunctionProvider
     */
    public function testSetPrintFunctionEquals(?array $serConfig, ?string $expected) : void
    {
        Reflections::setProperty($this->serviceManager, 'serConfig', $serConfig);
        
        Reflections::invokeMethod($this->serviceManager, 'setPrintFunction');
        
        $actual = Reflections::getProperty($this->serviceManager, 'printFunction');
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function setPrintFunctionExceptionProvider() : array
    {
        $data = [            
            'printf no in config' => [
                'serConfig' => [
                    'host' => 'LOCALHOST',
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
                    'isJson' => true,
                    'isAsync' => true,
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
                ]                
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setPrintFunction
     * @dataProvider setPrintFunctionExceptionProvider
     */
    public function testSetPrintFunctionExceptionEquals(?array $serConfig) : void
    {
        Reflections::setProperty($this->serviceManager, 'serConfig', $serConfig);
        
        $this->expectException(\Exception::class);
        
        Reflections::invokeMethod($this->serviceManager, 'setPrintFunction');        
    }
    
    /**
     * @coversNothing
     */
    public function setWithBodyProvider() : array
    {
        $data = [
            'no config' => [
                'serConfig' => null,
                'expected' => false
            ],
            'standard' => [
                'serConfig' => [
                    'host' => 'LOCALHOST',
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
                    'isJson' => true,
                    'isAsync' => false,
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
                'expected' => true
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setWithBody
     * @dataProvider setWithBodyProvider
     */
    public function testSetWithBodyEquals(?array $serConfig, bool $expected) : void
    {
        Reflections::setProperty($this->serviceManager, 'serConfig', $serConfig);
        
        Reflections::invokeMethod($this->serviceManager, 'setWithBody');
        
        $actual = Reflections::getProperty($this->serviceManager, 'withBody');
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function setWithBodyExceptionProvider() : array
    {
        $data = [            
            'no in config' => [
                'serConfig' => [
                    'host' => 'LOCALHOST',
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
                    'isJson' => true,
                    'isAsync' => true,
                    'printf' => 'formatResponse',
                    'header' => [
                        'title' => 'Procedura esportazione CSV dati movimentazioni dighe',
                        'start' => 'Elaborazione iniziata in data:'
                    ],
                    'footer' => [
                        'stop' => 'Elaborazione terminata in data:',
                        'time' => 'Tempo di elaborazione:',
                        'report' => 'Numero totale file csv esportati:'
                    ]
                ]                
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setWithBody
     * @dataProvider setWithBodyExceptionProvider
     */
    public function testSetWithBodyExceptionEquals(?array $serConfig) : void
    {
        Reflections::setProperty($this->serviceManager, 'serConfig', $serConfig);
        
        $this->expectException(\Exception::class);
        
        Reflections::invokeMethod($this->serviceManager, 'setWithBody');        
    }
    
    /**
     * @coversNothing
     */
    public function buildParamsProvider() : array
    {
        $data = [
            'no params' => [
                'args' => [
                    'postParams' => null
                ],
                'serParams' => [],
                'expected' => []
            ],
            'standard' => [
                'args' => [
                    'postParams' => [
                        [
                            'db' => 1,
                            'var' => '30030',
                            'datefrom' => '01/01/2019',
                            'dateto' => '02/01/2019'
                        ]
                    ]
                ],
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
                'expected' => [
                    [
                        'id' => null,
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => 1,
                        'field' => 'volume'
                    ]
                ]
            ],
            'void' => [
                'args' => [
                    'postParams' => [
                        []
                    ]
                ],
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
                'expected' => [
                    [
                        'id' => null,
                        'var' => null,
                        'datefrom' => null,
                        'dateto' => null,
                        'full' => 1,
                        'field' => 'volume'
                    ]
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::buildParams
     * @dataProvider buildParamsProvider
     */
    public function testBuildParamsEquals(array $args, array $serParams, array $expected) : void
    {
        Reflections::setProperty($this->serviceManager, 'serParams', $serParams);
        
        $actual = Reflections::invokeMethod($this->serviceManager, 'buildParams', $args);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function getDefaultsProvider() : array
    {
        $data = [
            'no params' => [
                'serParams' => [],
                'expected' => []
            ],            
            'void' => [
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
                'expected' => [
                    'id' => null,
                    'var' => null,
                    'datefrom' => null,
                    'dateto' => null,
                    'full' => 1,
                    'field' => 'volume'
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::getDefaults
     * @dataProvider getDefaultsProvider
     */
    public function testGetDefaultsEquals(array $serParams, array $expected) : void
    {
        Reflections::setProperty($this->serviceManager, 'serParams', $serParams);
        
        $actual = Reflections::invokeMethod($this->serviceManager, 'getDefaults');
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function purgeParamsProvider() : array
    {
        $data = [
            'no params' => [
                'args' => [
                    'postParams' => null
                ],
                'serParams' => [],
                'expected' => []
            ],
            'standard' => [
                'args' => [
                    'postParams' => [
                        [
                            'db' => 1,
                            'var' => '30030',
                            'datefrom' => '01/01/2019',
                            'dateto' => '02/01/2019'
                        ]
                    ]
                ],
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
                'expected' => [
                    [
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019'
                    ]
                ]
            ],
            'void' => [
                'args' => [
                    'postParams' => [
                        []
                    ]
                ],
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
                'expected' => [
                    []
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::purgeParams
     * @dataProvider purgeParamsProvider
     */
    public function testPurgeParamsEquals(array $args, array $serParams, array $expected) : void
    {
        Reflections::setProperty($this->serviceManager, 'serParams', $serParams);
        
        $actual = Reflections::invokeMethod($this->serviceManager, 'purgeParams', $args);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function fillParamsProvider() : array
    {
        $data = [
            'standard' => [
                'args' => [
                    'purged' => [
                        [
                            'var' => '30030',
                            'datefrom' => '01/01/2019',
                            'dateto' => '02/01/2019'
                        ]
                    ],
                    'default' => [
                        'id' => null,
                        'var' => null,
                        'datefrom' => null,
                        'dateto' => null,
                        'full' => 1,
                        'field' => 'volume'
                    ]
                ],
                'expected' => [
                    [
                        'id' => null,
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => 1,
                        'field' => 'volume'
                    ]
                ]
            ],
            'null purged' => [
                'args' => [
                    'purged' => null,
                    'default' => [
                        'id' => null,
                        'var' => null,
                        'datefrom' => null,
                        'dateto' => null,
                        'full' => 1,
                        'field' => 'volume'
                    ]
                ],
                'expected' => []
            ],
            'no purged' => [
                'args' => [
                    'purged' => [
                        []
                    ],
                    'default' => [
                        'id' => null,
                        'var' => null,
                        'datefrom' => null,
                        'dateto' => null,
                        'full' => 1,
                        'field' => 'volume'
                    ]
                ],
                'expected' => [
                    [
                        'id' => null,
                        'var' => null,
                        'datefrom' => null,
                        'dateto' => null,
                        'full' => 1,
                        'field' => 'volume'
                    ]
                ]
            ],
            'no default' => [
                'args' => [
                    'purged' => [
                        [
                            'var' => '30030',
                            'datefrom' => '01/01/2019',
                            'dateto' => '02/01/2019'
                        ]
                    ],
                    'default' => null
                ],
                'expected' => [
                    [
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                    ]
                ]
            ],
            'no purge no default' => [
                'args' => [
                    'purged' => [
                        []
                    ],
                    'default' => null
                ],
                'expected' => [
                    []
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::fillParams
     * @dataProvider fillParamsProvider
     */
    public function testFillParamsEquals(array $args, array $expected) : void
    {
        $actual = Reflections::invokeMethod($this->serviceManager, 'fillParams', $args);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function setParamsProvider() : array
    {
        $data = [
            'standard post' => [
                'args' => [
                    'params' => [
                        [
                            'db' => 1,
                            'var' => '30030',
                            'datefrom' => '01/01/2019',
                            'dateto' => '02/01/2019'
                        ]
                    ]
                ],
                'url' => TOCSVURL,
                'method' => 'POST',
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
                'isJson' => false,
                'key' => 'var',
                'expected' => [
                    [
                        'url' => TOCSVURL,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30030',
                            'datefrom' => '01/01/2019',
                            'dateto' => '02/01/2019',
                            'full' => 1,
                            'field' => 'volume'
                        ],
                        'isJson' => false,
                        'key' => '30030'
                    ]
                ]
            ],
            'standard get' => [
                'args' => [
                    'params' => [
                        [
                            'db' => 1,
                            'var' => '30030',
                            'datefrom' => '01/01/2019',
                            'dateto' => '02/01/2019'
                        ]
                    ]
                ],
                'url' => TOCSVURL,
                'method' => 'GET',
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
                'isJson' => false,
                'key' => 'var',
                'expected' => [
                    [
                        'url' => TOCSVURL . '?var=30030&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019&full=1&field=volume',
                        'method' => 'GET',
                        'params' => [],
                        'isJson' => false,
                        'key' => '30030'
                    ]
                ]
            ],
            'default' => [
                'args' => [
                    'params' => null
                ],
                'url' => TOCSVURL,
                'method' => 'GET',
                'serParams' => [],
                'isJson' => false,
                'key' => 'var',
                'expected' => [
                    [
                        'url' => TOCSVURL,
                        'method' => 'GET',
                        'params' => [],
                        'isJson' => null,
                        'key' => null
                    ]
                ]
            ],
            'no param' => [
                'args' => [
                    'params' => [
                        []
                    ]
                ],
                'url' => TOCSVURL,
                'method' => 'GET',
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
                'isJson' => false,
                'key' => 'var',
                'expected' => [
                    [
                        'url' => TOCSVURL . '?full=1&field=volume',
                        'method' => 'GET',
                        'params' => [],
                        'isJson' => null,
                        'key' => null
                    ]
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setParams
     * @dataProvider setParamsProvider
     */
    public function testSetParamsEquals(array $args, string $url, string $method, array $serParams, bool $isJson, ?string $key, array $expected) : void
    {
        Reflections::setProperty($this->serviceManager, 'url', $url);
        Reflections::setProperty($this->serviceManager, 'method', $method);
        Reflections::setProperty($this->serviceManager, 'serParams', $serParams);
        Reflections::setProperty($this->serviceManager, 'isJson', $isJson);
        Reflections::setProperty($this->serviceManager, 'key', $key);
        
        Reflections::invokeMethod($this->serviceManager, 'setParams', $args);
        
        $actual = Reflections::getProperty($this->serviceManager, 'params');
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function setParamsExceptionProvider() : array
    {
        $data = [            
            'default' => [
                'args' => [
                    'params' => null
                ],
                'url' => TOCSVURL,
                'method' => 'POST',
                'serParams' => [],
                'isJson' => false,
                'key' => null
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setParams
     * @dataProvider setParamsExceptionProvider
     */
    public function testSetParamsExceptionEquals(array $args, string $url, string $method, array $serParams, bool $isJson, ?string $key) : void
    {
        Reflections::setProperty($this->serviceManager, 'url', $url);
        Reflections::setProperty($this->serviceManager, 'method', $method);
        Reflections::setProperty($this->serviceManager, 'serParams', $serParams);
        Reflections::setProperty($this->serviceManager, 'isJson', $isJson);
        Reflections::setProperty($this->serviceManager, 'key', $key);
        
        $this->expectException(\Exception::class);
        
        Reflections::invokeMethod($this->serviceManager, 'setParams', $args);
    }
    
    /**
     * @coversNothing
     */
    public function setCallParamsProvider() : array
    {
        $data = [
            'standard post' => [
                'args' => [
                    'params' => [
                        [
                            'id' => 0,
                            'var' => '30030',
                            'datefrom' => '01/01/2019',
                            'dateto' => '02/01/2019',
                            'full' => 1,
                            'field' => 'volume'
                        ]
                    ]
                ],
                'url' => TOCSVURL,
                'method' => 'POST',
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
                'isJson' => false,
                'key' => 'id',
                'expected' => [
                    [
                        'url' => TOCSVURL,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30030',
                            'datefrom' => '01/01/2019',
                            'dateto' => '02/01/2019',
                            'full' => 1,
                            'field' => 'volume'
                        ],
                        'isJson' => false,
                        'key' => '0'
                    ]
                ]
            ],
            'standard get' => [
                'args' => [
                    'params' => [
                        [
                            'id' => 0,
                            'var' => '30030',
                            'datefrom' => '01/01/2019',
                            'dateto' => '02/01/2019',
                            'full' => 1,
                            'field' => 'volume'
                        ]
                    ]
                ],
                'url' => TOCSVURL,
                'method' => 'GET',
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
                'isJson' => false,
                'key' => 'id',
                'expected' => [
                    [
                        'url' => TOCSVURL . '?var=30030&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019&full=1&field=volume',
                        'method' => 'GET',
                        'params' => [],
                        'isJson' => false,
                        'key' => '0'
                    ]
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setCallParams
     * @dataProvider setCallParamsProvider
     */
    public function testSetCallParamsEquals(array $args, string $url, string $method, array $serParams, bool $isJson, string $key, array $expected) : void
    {
        Reflections::setProperty($this->serviceManager, 'url', $url);
        Reflections::setProperty($this->serviceManager, 'method', $method);
        Reflections::setProperty($this->serviceManager, 'serParams', $serParams);
        Reflections::setProperty($this->serviceManager, 'isJson', $isJson);
        Reflections::setProperty($this->serviceManager, 'key', $key);
        
        $actual = Reflections::invokeMethod($this->serviceManager, 'setCallParams', $args);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function setQueryParamsProvider() : array
    {
        $data = [
            'standard' => [
                'args' => [
                    'params' => [
                        'id' => 0,
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => 1,
                        'field' => 'volume'
                    ]
                ],
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
                'expected' => [
                    'var' => '30030',
                    'datefrom' => '01/01/2019',
                    'dateto' => '02/01/2019',
                    'full' => 1,
                    'field' => 'volume'
                ]
            ],
            'no service params' => [
                'args' => [
                    'params' => [
                        'id' => 0,
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => 1,
                        'field' => 'volume'
                    ]
                ],
                'serParams' => [],  
                'expected' => [
                    'id' => 0,
                    'var' => '30030',
                    'datefrom' => '01/01/2019',
                    'dateto' => '02/01/2019',
                    'full' => 1,
                    'field' => 'volume'
                ]
            ],
            'no params' => [
                'args' => [
                    'params' => []
                ],
                'serParams' => [],  
                'expected' => []
            ],
            'no post' => [
                'args' => [
                    'params' => []
                ],
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
                'expected' => []
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setQueryParams
     * @dataProvider setQueryParamsProvider
     */
    public function testSetQueryParamsEquals(array $args, ?array $serParams, ?array $expected) : void
    {
        Reflections::setProperty($this->serviceManager, 'serParams', $serParams);
        
        $actual = Reflections::invokeMethod($this->serviceManager, 'setQueryParams', $args);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function setUrlAndParamsProvider() : array
    {
        $data = [
            'standard post' => [
                'args' => [
                    'queryParams' => [
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => 1,
                        'field' => 'volume'
                    ]
                ],
                'url' => TOCSVURL,
                'method' => 'POST',
                'expected' => [
                    'url' => TOCSVURL, 
                    'params' => [
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => 1,
                        'field' => 'volume'
                    ]
                ]
            ],
            'standard get' => [
                'args' => [
                    'queryParams' => [
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => 1,
                        'field' => 'volume'
                    ]
                ],
                'url' => TOCSVURL,
                'method' => 'GET',
                'expected' => [
                    'url' => TOCSVURL . '?var=30030&datefrom=01%2F01%2F2019&dateto=02%2F01%2F2019&full=1&field=volume',
                    'params' => []
                ]
            ],
            'params void' => [
                'args' => [
                    'queryParams' => []
                ],
                'url' => TOCSVURL,
                'method' => 'GET',
                'expected' => [
                    'url' => TOCSVURL,
                    'params' => []
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setUrlAndParams
     * @dataProvider setUrlAndParamsProvider
     */
    public function testSetUrlAndParamsEquals(array $args, string $url, string $method, array $expected) : void
    {
        Reflections::setProperty($this->serviceManager, 'url', $url);
        Reflections::setProperty($this->serviceManager, 'method', $method);
        
        $actual = Reflections::invokeMethod($this->serviceManager, 'setUrlAndParams', $args);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function setUrlAndParamsExceptionProvider() : array
    {
        $data = [            
            'params void' => [
                'args' => [
                    'queryParams' => []
                ],
                'url' => TOCSVURL,
                'method' => 'POST'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setUrlAndParams
     * @dataProvider setUrlAndParamsExceptionProvider
     */
    public function testSetUrlAndParamsExceptionEquals(array $args, string $url, string $method) : void
    {
        Reflections::setProperty($this->serviceManager, 'url', $url);
        Reflections::setProperty($this->serviceManager, 'method', $method);
        
        $this->expectException(\Exception::class);
        
        Reflections::invokeMethod($this->serviceManager, 'setUrlAndParams', $args);
    }
    
    /**
     * @coversNothing
     */
    public function setCallKeyProvider() : array
    {
        $data = [            
            'key const eq' => [
                'args' => [
                    'i' => '1',
                    'params' => [
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => 1,
                        'field' => 'volume'
                    ],
                    'prevKey' => 'pippo'
                    
                ],
                'key' => 'pippo',
                'expected' => 'pippo.1'
            ],
            'prevKey void' => [
                'args' => [
                    'i' => '1',
                    'params' => [
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => 1,
                        'field' => 'volume'
                    ],
                    'prevKey' => ''
                    
                ],
                'key' => 'pippo',
                'expected' => 'pippo'
            ],
            'prevKey void *' => [
                'args' => [
                    'i' => '1',
                    'params' => [
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => 1,
                        'field' => 'volume'
                    ],
                    'prevKey' => ''
                    
                ],
                'key' => 'pippo*',
                'expected' => 'pippo1'
            ],
            'key null' => [
                'args' => [
                    'i' => '1',
                    'params' => [
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => 1,
                        'field' => 'volume'
                    ],
                    'prevKey' => '30040'
                    
                ],
                'key' => null,
                'expected' => null
            ],
            'key *' => [
                'args' => [
                    'i' => '1',
                    'params' => [
                        'var' => '30030',
                        'datefrom' => '01/01/2019',
                        'dateto' => '02/01/2019',
                        'full' => 1,
                        'field' => 'volume'
                    ],
                    'prevKey' => 'pluto'
                    
                ],
                'key' => 'pluto*',
                'expected' => 'pluto1'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setCallKey
     * @dataProvider setCallKeyProvider
     */
    public function testSetCallKeyEquals(array $args, ?string $key, ?string $expected) : void
    {
        Reflections::setProperty($this->serviceManager, 'key', $key);
        
        $actual = Reflections::invokeMethod($this->serviceManager, 'setCallKey', $args);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function setHeaderProvider() : array
    {
        $data = [
            'standard' => [
                'start' => '2019-01-01 08:54:23.123456',
                'token' => 'tocsv',
                'expected' => [
                    'title' => '<b>Procedura calcolo ed esportazione dati</b>',
                    'start' => 'Elaborazione iniziata in data: <b>01/01/2019 08:54:23</b>'
                ]
            ],
            'no token' => [
                'start' => '2019-01-01 08:54:23.123456',
                'token' => 'pippo',
                'expected' => [
                    'title' => '<b>pippo</b>',
                    'start' => 'Elaborazione iniziata in data: <b>01/01/2019 08:54:23</b>'
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setHeader
     * @dataProvider setHeaderProvider
     */
    public function testSetHeaderEquals(string $start, string $token, array $expected) : void
    {
        Reflections::setProperty($this->serviceManager, 'start', $start);
        Reflections::setProperty($this->serviceManager, 'token', $token);
        
        $actual = Reflections::invokeMethod($this->serviceManager, 'setHeader');
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function setFooterProvider() : array
    {
        $data = [
            'standard' => [
                'args' => [
                    'repString' => 'pippo'
                ],
                'token' => 'tocsv',
                'expected' => [
                    'stop' => 'Elaborazione terminata in data: <b>|</b>',
                    'time' => 'Tempo di elaborazione: <b>| sec</b>',
                    'totals' => '<b>Totali:</b> pippo'
                ]
            ],
            'no token' => [
                'args' => [
                    'repString' => 'pippo'
                ],
                'token' => null,
                'expected' => [
                    'stop' => 'Elaborazione terminata in data: <b>|</b>',
                    'time' => 'Tempo di elaborazione: <b>| sec</b>'
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setFooter
     * @dataProvider setFooterProvider
     */
    public function testSetFooterEquals(array $args, ?string $token, array $responses) : void
    {
        Reflections::setProperty($this->serviceManager, 'token', $token);
        
        $actual = Reflections::invokeMethod($this->serviceManager, 'setFooter', $args);
        
        foreach ($responses as $key => $response) {
            $expecteds = explode('|', $response);
            foreach ($expecteds as $expected) {
                $this->assertStringContainsString($expected, $actual[$key]);
            }            
        }        
    }
    
    /**
     * @coversNothing
     */
    public function setTotalsProvider() : array
    {
        $data = [
            'standard' => [
                'totals' => [
                    'Records' => '10',
                    'Inseriti' => '7',
                    'Aggiornati' => '3'
                ],
                'resFooter' => 'Records:2|Inseriti:1|Aggiornati:1',
                'expected' => [
                    'Records' => '12',
                    'Inseriti' => '8',
                    'Aggiornati' => '4'
                ]
            ],
            'decimal' => [
                'totals' => [
                    'Records' => '10000',
                    'Inseriti' => '7000',
                    'Aggiornati' => '3000'
                ],
                'resFooter' => 'Records:2.000|Inseriti:1.000|Aggiornati:1.000',
                'expected' => [
                    'Records' => '12000',
                    'Inseriti' => '8000',
                    'Aggiornati' => '4000'
                ]
            ],
            'no var arg' => [
                'totals' => [
                    'Records' => '10',
                    'Inseriti' => '7',
                    'Aggiornati' => '3'
                ],
                'resFooter' => 'Records:2|Inseriti:1|Aggiornati',
                'expected' => [
                    'Records' => '12',
                    'Inseriti' => '8',
                    'Aggiornati' => '3'
                ]
            ],
            'no totals' => [
                'totals' => [],
                'resFooter' => 'Records:2|Inseriti:1|Aggiornati:1',
                'expected' => [
                    'Records' => '2',
                    'Inseriti' => '1',
                    'Aggiornati' => '1'
                ]
            ],
            'no res' => [
                'totals' => [
                    'Records' => '10',
                    'Inseriti' => '7',
                    'Aggiornati' => '3'
                ],
                'resFooter' => '',
                'expected' => [
                    'Records' => '10',
                    'Inseriti' => '7',
                    'Aggiornati' => '3'
                ]
            ],
            'no totals no var' => [
                'totals' => [],
                'resFooter' => 'Totali non disponibili',
                'expected' => []
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setTotals
     * @dataProvider setTotalsProvider
     */
    public function testSetTotalsEquals(array $totals, string $resFooter, array $expected) : void
    {
        Reflections::invokeMethod($this->serviceManager, 'setTotals', array(&$totals, $resFooter));
        
        $this->assertEquals($expected, $totals);       
    }
    
    /**
     * @coversNothing
     */
    public function formatTotalsProvider() : array
    {
        $data = [
            'standard' => [
                'args' => [
                    'totals' => [
                        'Records' => '10',
                        'Inseriti' => '7',
                        'Aggiornati' => '3'
                    ]
                ],
                'expected' => 'Records: 10 | Inseriti: 7 | Aggiornati: 3'
            ],
            'decimal' => [
                'args' => [
                    'totals' => [
                        'Records' => '10000',
                        'Inseriti' => '7000',
                        'Aggiornati' => '3000'
                    ]
                ],
                'expected' => 'Records: 10.000 | Inseriti: 7.000 | Aggiornati: 3.000'
            ],
            'no totals' => [
                'args' => [
                    'totals' => []
                ],
                'expected' => 'n.d.'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::formatTotals
     * @dataProvider formatTotalsProvider
     */
    public function testFormatTotalsEquals(array $args, string $expected) : void
    {
        $actual = Reflections::invokeMethod($this->serviceManager, 'formatTotals', $args);
        
        $this->assertEquals($expected, $actual);       
    }
    
    /**
     * @coversNothing
     */
    public function setReportProvider() : array
    {
        $data = [
            'standard' => [
                'args' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"var":"30040","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: 4"]}}',
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30040<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: 6"]}}'
                    ]
                ],
                'token' => 'tocsv',
                'expected' => 'Esportati: 10'
            ],
            'no token' => [
                'args' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"var":"30040","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: 4"]}}',
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30040<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: 6"]}}'
                    ]
                ],
                'token' => null,
                'expected' => ''
            ],
            'token diff' => [
                'args' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"var":"30040","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: 4"]}}',
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30040<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>|<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b> (tocsv@' . REALHOST . ')"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: 6"]}}'
                    ]
                ],
                'token' => 'pippo',
                'expected' => ''
            ],
            'no response' => [
                'args' => [
                    'responses' => []
                ],
                'token' => 'tocsv',
                'expected' => 'n.d.'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setReport
     * @dataProvider setReportProvider
     */
    public function testSetReportEquals(array $args, ?string $token, string $expected) : void
    {
        Reflections::setProperty($this->serviceManager, 'token', $token);
        
        $actual = Reflections::invokeMethod($this->serviceManager, 'setReport', $args);
        
        $this->assertEquals($expected, $actual);       
    }
    
    /**
     * @coversNothing
     */
    public function setBodyMsgProvider() : array
    {
        $data = [
            'assoc' => [
                'message' => 'Paperopoli: ',
                'bodies' => [
                    'pippo' => 'messaggio numero 1',
                    'pluto' => 'messaggio numero 2',
                    'paperino' => 'messaggio numero 3'
                ],
                'expected' => 'Paperopoli: <b>pippo:</b> messaggio numero 1' . PHP_EOL . '<b>pluto:</b> messaggio numero 2' . PHP_EOL . '<b>paperino:</b> messaggio numero 3' . PHP_EOL
            ],
            'numeric' => [
                'message' => 'Paperopoli: ',
                'bodies' => [
                    'messaggio numero 1',
                    'messaggio numero 2',
                    'messaggio numero 3'
                ],
                'expected' => 'Paperopoli: messaggio numero 1' . PHP_EOL . 'messaggio numero 2' . PHP_EOL . 'messaggio numero 3' . PHP_EOL
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setBodyMsg
     * @dataProvider setBodyMsgProvider
     */
    public function testSetBodyMsgEquals(string $message, array $bodies, string $expected) : void
    {
        Reflections::invokeMethod($this->serviceManager, 'setBodyMsg', array(&$message, $bodies));
        
        $this->assertEquals($expected, $message);       
    }
    
    /**
     * @coversNothing
     */
    public function setBodyProvider() : array
    {
        $data = [
            'with body' => [
                'args' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"var":"30040","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>0,345 sec<\/b>. File CSV <b>full<\/b> esportati: <b>4<\/b>"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: 4"]}}',
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30040<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>0,752 sec<\/b>. File CSV <b>full<\/b> esportati: <b>6<\/b>"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: 6"]}}'
                    ]
                ],
                'globalSend' => false,
                'withBody' => true,
                'expected' => PHP_EOL . 'Elaborazione dati <b>Volume</b> variabile <b>30030</b> dal <b>01/01/2019</b> al <b>02/01/2019</b> avvenuta con successo in <b>0,345 sec</b>. File CSV <b>full</b> esportati: <b>4</b>' . PHP_EOL . 'Elaborazione dati <b>Volume</b> variabile <b>30040</b> dal <b>01/01/2019</b> al <b>02/01/2019</b> avvenuta con successo in <b>0,752 sec</b>. File CSV <b>full</b> esportati: <b>6</b>' . PHP_EOL . PHP_EOL
            ],
            'with out body' => [
                'args' => [
                    'responses' => [
                        '{"ok":true,"response":{"params":{"var":"30040","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>0,345 sec<\/b>. File CSV <b>full<\/b> esportati: <b>4<\/b>"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: 4"]}}',
                        '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30040<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>0,752 sec<\/b>. File CSV <b>full<\/b> esportati: <b>6<\/b>"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: 6"]}}'
                    ]
                ],
                'globalSend' => false,
                'withBody' => false,
                'expected' => '' 
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setBody
     * @dataProvider setBodyProvider
     */
    public function testSetBodyEquals(array $args, bool $globalSend, bool $withBody, string $expected) : void
    {
        Reflections::setProperty($this->serviceManager, 'globalSend', $globalSend);
        Reflections::setProperty($this->serviceManager, 'withBody', $withBody);
        
        $actual = Reflections::invokeMethod($this->serviceManager, 'setBody', $args);
        
        $this->assertEquals($expected, $actual);       
    }
    
    /**
     * @coversNothing
     */
    public function setMessageProvider() : array
    {
        $data = [
            'standard no body' => [
                'token' => 'tocsv',
                'responses' => [
                    '{"ok":true,"response":{"params":{"var":"30040","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>0,345 sec<\/b>. File CSV <b>full<\/b> esportati: <b>4<\/b>"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: 4"]}}',
                    '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30040<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>0,752 sec<\/b>. File CSV <b>full<\/b> esportati: <b>6<\/b>"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: 6"]}}'
                ],
                'globalSend' => true,
                'withBody' => true,
                'expected' => '<b>Procedura calcolo ed esportazione dati</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL . '<b>Totali:</b> Esportati: 10'
            ],
            'standard with body' => [
                'token' => 'tocsv',
                'responses' => [
                    '{"ok":true,"response":{"params":{"var":"30040","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>0,345 sec<\/b>. File CSV <b>full<\/b> esportati: <b>4<\/b>"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: 4"]}}',
                    '{"ok":true,"response":{"params":{"var":"30030","datefrom":"01\/01\/2019 00:00:00","dateto":"02\/01\/2019 00:00:00","full":true,"field":"volume"},"header":["Procedura esportazione CSV dati movimentazioni dighe","Elaborazione iniziata in data: |"],"body":["Elaborazione dati <b>Volume<\/b> variabile <b>30040<\/b> dal <b>01\/01\/2019<\/b> al <b>02\/01\/2019<\/b> avvenuta con successo in <b>0,752 sec<\/b>. File CSV <b>full<\/b> esportati: <b>6<\/b>"],"footer":["Elaborazione terminata in data: |","Tempo di elaborazione: | sec","Numero totale file csv esportati: 6"]}}'
                ],
                'globalSend' => false,
                'withBody' => true,
                'expected' => '<b>Procedura calcolo ed esportazione dati</b>' . PHP_EOL . 'Elaborazione iniziata in data: <b>|</b>' . PHP_EOL . PHP_EOL . 'Elaborazione dati <b>Volume</b> variabile <b>30030</b> dal <b>01/01/2019</b> al <b>02/01/2019</b> avvenuta con successo in <b>0,345 sec</b>. File CSV <b>full</b> esportati: <b>4</b>' . PHP_EOL . 'Elaborazione dati <b>Volume</b> variabile <b>30040</b> dal <b>01/01/2019</b> al <b>02/01/2019</b> avvenuta con successo in <b>0,752 sec</b>. File CSV <b>full</b> esportati: <b>6</b>' . PHP_EOL . PHP_EOL . 'Elaborazione terminata in data: <b>|</b>' . PHP_EOL . 'Tempo di elaborazione: <b>|</b>' . PHP_EOL . '<b>Totali:</b> Esportati: 10'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group service
     * @covers \vaniacarta74\Scarichi\ServiceManager::setMessage
     * @dataProvider setMessageProvider
     */
    public function testSetMessageEquals(string $token, array $responses, bool $globalSend, bool $withBody, string $message) : void
    {
        Reflections::setProperty($this->serviceManager, 'token', $token);
        Reflections::setProperty($this->serviceManager, 'responses', $responses);
        Reflections::setProperty($this->serviceManager, 'globalSend', $globalSend);
        Reflections::setProperty($this->serviceManager, 'withBody', $withBody);
        
        Reflections::invokeMethod($this->serviceManager, 'setMessage');
        
        $actual = Reflections::getProperty($this->serviceManager, 'message');
        
        $expecteds = explode('|', $message);    
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);
        }
    }
}
