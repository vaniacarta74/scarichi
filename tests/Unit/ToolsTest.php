<?php

namespace vaniacarta74\scarichi\tests\Unit;

use PHPUnit\Framework\TestCase;


class ToolsTest extends TestCase {
    
    /**
     * @coversNothing
     */
    public function setFileProvider() : array
    {
        $data = [
            'base' => [
                'variabile' => '30030',
                'date' => [
                    'datefrom' => New \DateTime('2018-01-02 00:15:00'),
                    'dateto' => New \DateTime('2020-04-01 23:59:59')
                ],
                'filtered' => false,
                'field' => 'volume',
                'output' => CSV . '/Volume_30030_201801020015_202004012359_full.csv'
            ],
            'altro campo' => [
                'variabile' => '30030',
                'date' => [
                    'datefrom' => New \DateTime('2018-01-02 00:15:00'),
                    'dateto' => New \DateTime('2020-04-01 23:59:59')
                ],
                'filtered' => false,
                'field' => 'livello',
                'output' => CSV . '/Livello_30030_201801020015_202004012359_full.csv'
            ],
            'filtered' => [
                'variabile' => '30030',
                'date' => [
                    'datefrom' => New \DateTime('2018-01-02 00:15:00'),
                    'dateto' => New \DateTime('2020-04-01 23:59:59')
                ],
                'filtered' => true,
                'field' => 'volume',
                'output' => CSV . '/Volume_30030_201801020015_202004012359_no0.csv'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @coversNothing
     */
    public function getDataFromDbProvider() : array
    {        
        $data = [
            'scarichi' => [
                'dbase' => 'dbcore',
                'file' => 'query_scarichi',
                'parametri' => [
                    'variabile' => '30030'
                ],
                'expected' => [
                    '0' => [
                        'scarico' => 1,
                        'denominazione' => 'Diga Flumineddu - Sfioratore di superficie',
                        'db' => 'SSCP_data',
                        'variabile' => 30030,
                        'tipo' => 'sfioratore di superficie'
                    ]
                ]
            ],
            'variabili scarichi' => [
                'dbase' => 'dbcore',
                'file' => 'query_variabili_scarichi',
                'parametri' => [
                    'scarico' => '1'
                ],
                'expected' => [
                    '0' => [
                        'scarico' => 1,
                        'db' => 'SSCP_data',
                        'variabile' => 42,
                        'tipo_dato' => 2,
                        'data_attivazione' => New \DateTime('1970-01-01 00:00:00'),
                        'data_disattivazione' => New \DateTime('2017-12-09 00:00:00'),
                        'categoria' => 'livello'
                    ],
                    '1' => [
                        'scarico' => 1,
                        'db' => 'SPT',
                        'variabile' => 165071,
                        'tipo_dato' => 2,
                        'data_attivazione' => New \DateTime('2017-12-09 00:00:00'),
                        'data_disattivazione' => New \DateTime('2070-01-01 00:00:00'),
                        'categoria' => 'livello'
                    ],
                    '2' => [
                        'scarico' => 1,
                        'db' => 'SPT',
                        'variabile' => 165071,
                        'tipo_dato' => 2,
                        'data_attivazione' => New \DateTime('2017-12-09 00:00:00'),
                        'data_disattivazione' => New \DateTime('2070-01-01 00:00:00'),
                        'categoria' => 'livello valle'
                    ]
                ]
            ],
            'variabili' => [
                'dbase' => 'SSCP_data',
                'file' => 'query_variabili',
                'parametri' => [
                    'variabile' => '30030'
                ],
                'expected' => [
                    '0' => [
                        'id_variabile' => 30030,
                        'impianto' => 75,
                        'unita_misura' => 'mc'
                    ]
                ]
            ],
            'dati acquisiti' => [
                'dbase' => 'SPT',
                'file' => 'query_dati_acquisiti',
                'parametri' => [
                    'variabile' => '165071',
                    'tipo dato' => '2',
                    'data iniziale' => New \DateTime('2018-01-02 00:00:00'),
                    'data finale' => New \DateTime('2018-01-02 00:15:0'),
                    'data attivazione' => New \DateTime('2017-12-09 00:00:00'),
                    'data disattivazione' => New \DateTime('2070-01-01 00:00:00'),
                ],
                'expected' => [
                    '0' => [
                        'variabile' => 165071,
                        'valore' => 266.206,
                        'data_e_ora' => New \DateTime('2018-01-02 00:00:00'),
                        'tipo_dato' => 2
                    ]
                ]
            ],            
            'sfiori' => [
                'dbase' => 'dbcore',
                'file' => 'query_sfiori',
                'parametri' => [
                    'scarico' => '1'
                ],
                'expected' => [
                    '0' => [
                        'scarico' => 1,
                        'mi' => 0.47,
                        'larghezza' => 40.5,
                        'quota' => 276.5,
                        'limite' => 942.67
                    ]
                ]
            ]
        ];
                
        return $data;
    }
    
    /**
     * @coversNothing
     */
    public function rowFetchedProvider() : array
    {
        $data = [
            'scarichi' => [
                'rows' => [
                    'row' => [
                        'scarico' => 1,
                        'denominazione' => 'nome',
                        'db' => 'SSCP_data',
                        'variabile' => 30030,
                        'tipo' => 'tipo'            
                    ]
                ]
            ],
            'variabili scarichi' => [
                'rows' => [
                    'row' => [
                        'scarico' => 1,
                        'db' => 'SSCP_data',
                        'variabile' => 42,
                        'tipo dato' => 2,
                        'data attivazione' => New \DateTime(),
                        'data disattivazione' => New \DateTime(),
                        'categoria' => 'livello'
                    ]
                ]
            ],
            'variabili' => [
                'rows' => [
                    'row' => [
                        'id variabile' => 30030,
                        'impianto' => 75,
                        'unita misura' => 'mc'
                    ]
                ]
            ],
            'dati acquisiti' => [
                'rows' => [
                    'row' => [
                        'variabile' => 165071,
                        'valore' => 273.089,
                        'data e ora' => New \DateTime(),
                        'tipo dato' => 2
                    ]
                ]
            ],            
            'sfiori' => [
                'rows' => [
                    'row' => [
                        'scarico' => 1,
                        'mi' => 0.47,
                        'larghezza' => 40.5,
                        'tipo dato' => 2,
                        'quota' => 276.5,
                        'limite' => 942.67
                    ]
                ]
            ]
        ];
        
        return $data;
    }

    /**
     * @coversNothing
     */
    public function setToLocalProvider() : array
    {
        $fetched = $this->rowFetchedProvider();
        
        $toAdd = [
            'scarichi' => [
                'dbase' => 'dbcore',
                'rowsOut' => [
                    'row' => [
                        'scarico' => 1,
                        'denominazione' => 'nome',
                        'db' => 'SSCP_data',
                        'variabile' => 30030,
                        'tipo' => 'tipo'
                    ]
                ]
            ],
            'variabili scarichi' => [
                'dbase' => 'dbcore',
                'rowsOut' => [
                    'row' => [
                        'scarico' => 1,
                        'db' => 'SSCP_data',
                        'variabile' => 42,
                        'tipo dato' => 2,
                        'data attivazione' => New \DateTime(),
                        'data disattivazione' => New \DateTime(),
                        'categoria' => 'livello'
                    ]
                ]
            ],
            'variabili' => [
                'dbase' => 'SSCP_data',
                'rowsOut' => [
                    'row' => [
                        'id variabile' => 30030,
                        'impianto' => 75,
                        'unita misura' => 'mc'
                    ]
                ]
            ],
            'dati acquisiti' => [
                'dbase' => 'SPT',
                'rowsOut' => [
                    'row' => [
                        'variabile' => 165071,
                        'valore' => 273.089,
                        'data e ora' => New \DateTime(),
                        'tipo dato' => 2
                    ]
                ]
            ],            
            'sfiori' => [
                'dbase' => 'dbcore',
                'rowsOut' => [
                    'row' => [
                        'scarico' => 1,
                        'mi' => 0.47,
                        'larghezza' => 40.5,
                        'tipo dato' => 2,
                        'quota' => 276.5,
                        'limite' => 942.67
                    ]
                ]
            ]
        ];
        
        $data = array_merge_recursive($fetched, $toAdd);
        
        return $data;
    }
    
    /**
     * @coversNothing
     */
    public function checkDatesProvider() : array
    {
        $data = [
            'scarichi l->s' => [
                'db' => 'dbcore',
                'parametri' => [
                    'variabile' => '30030'
                ],
                'localToGMT-1' => true,
                'output' => [
                    'variabile' => '30030'
                ]
            ],
            'scarichi s->l' => [
                'db' => 'dbcore',
                'parametri' => [
                    'variabile' => '30030'
                ],
                'localToGMT-1' => false,
                'output' => [
                    'variabile' => '30030'
                ]
            ],
            'variabili scarichi l->s' => [
                'db' => 'dbcore',
                'parametri' => [
                    'scarico' => '1'
                ],
                'localToGMT-1' => true,
                'output' => [
                    'scarico' => '1'
                ]
            ],
            'variabili scarichi s->l' => [
                'db' => 'dbcore',
                'parametri' => [
                    'scarico' => '1'
                ],
                'localToGMT-1' => false,
                'output' => [
                    'scarico' => '1'
                ]
            ],
            'variabili l->s' => [
                'db' => 'SPT',
                'parametri' => [
                    'variabile' => '165071'
                ],
                'localToGMT-1' => true,
                'output' => [
                    'variabile' => '165071'
                ]
            ],
            'variabili s->l' => [
                'db' => 'SPT',
                'parametri' => [
                    'variabile' => '165071'
                ],
                'localToGMT-1' => false,
                'output' => [
                    'variabile' => '165071'
                ]
            ],
            'dati acquisiti SPT l->s' => [
                'db' => 'SPT',
                'parametri' => [
                    'variabile' => '165071',
                    'tipo dato' => '2',
                    'data iniziale' => New \DateTime(),
                    'data finale' => New \DateTime(),
                    'data attivazione' => New \DateTime(),
                    'data disattivazione' => New \DateTime()
                ],
                'localToGMT-1' => true,
                'output' => [
                    'variabile' => '165071',
                    'tipo dato' => '2',
                    'data iniziale' => New \DateTime(),
                    'data finale' => New \DateTime(),
                    'data attivazione' => New \DateTime(),
                    'data disattivazione' => New \DateTime()
                ]
            ],
            'dati acquisiti SPT s->l' => [
                'db' => 'SPT',
                'parametri' => [
                    'variabile' => '165071',
                    'tipo dato' => '2',
                    'data iniziale' => New \DateTime(),
                    'data finale' => New \DateTime(),
                    'data attivazione' => New \DateTime(),
                    'data disattivazione' => New \DateTime()
                ],
                'localToGMT-1' => false,
                'output' => [
                    'variabile' => '165071',
                    'tipo dato' => '2',
                    'data iniziale' => New \DateTime(),
                    'data finale' => New \DateTime(),
                    'data attivazione' => New \DateTime(),
                    'data disattivazione' => New \DateTime()
                ]
            ],
            'dati acquisiti SSCP l->s' => [
                'db' => 'SSCP_data',
                'parametri' => [
                    'variabile' => '42',
                    'tipo dato' => '2',
                    'data iniziale' => New \DateTime(),
                    'data finale' => New \DateTime(),
                    'data attivazione' => New \DateTime(),
                    'data disattivazione' => New \DateTime()
                ],
                'localToGMT-1' => true,
                'output' => [
                    'variabile' => '42',
                    'tipo dato' => '2',
                    'data iniziale' => New \DateTime(),
                    'data finale' => New \DateTime(),
                    'data attivazione' => New \DateTime(),
                    'data disattivazione' => New \DateTime()
                ]
            ],
            'dati acquisiti SSCP s->l' => [
                'db' => 'SSCP_data',
                'parametri' => [
                    'variabile' => '42',
                    'tipo dato' => '2',
                    'data iniziale' => New \DateTime(),
                    'data finale' => New \DateTime(),
                    'data attivazione' => New \DateTime(),
                    'data disattivazione' => New \DateTime()
                ],
                'localToGMT-1' => false,
                'output' => [
                    'variabile' => '42',
                    'tipo dato' => '2',
                    'data iniziale' => New \DateTime(),
                    'data finale' => New \DateTime(),
                    'data attivazione' => New \DateTime(),
                    'data disattivazione' => New \DateTime()
                ]
            ],
            'sfiori l->s' => [
                'db' => 'dbcore',
                'parametri' => [
                    'scarico' => '1'
                ],
                'localToGMT-1' => true,
                'output' => [
                    'scarico' => 1
                ]
            ],
            'sfiori s->l' => [
                'db' => 'dbcore',
                'parametri' => [
                    'scarico' => '1'
                ],
                'localToGMT-1' => false,
                'output' => [
                    'scarico' => 1
                ]
            ]
        ];
        
        return $data;
    }
    
    
    /**
     * @coversNothing
     */
    public function utcProvider() : array   
    {
        $data = [
            'marzo: solare(GMT-1)-> solare(local)' => [
                'input' => '2020-03-29 01:59:59',
                'locToGMT-1' => false,
                'd/m/Y' => true,
                'output' => '29/03/2020 01:59:59'
            ],
            'marzo: solare(GMT-1)-> legale(local)' => [
                'input' => '2020-03-29 02:00:00',
                'locToGMT-1' => false,
                'd/m/Y' => true,
                'output' => '29/03/2020 03:00:00'
            ],
            'ottobre: solare(GMT-1)-> legale(local)' => [
                'input' => '2020-10-25 01:59:59',
                'locToGMT-1' => false,
                'd/m/Y' => true,
                'output' => '25/10/2020 02:59:59'
            ],
            'ottobre: solare(GMT-1)-> solare(local)' => [
                'input' => '2020-10-25 02:00:00',
                'locToGMT-1' => false,
                'd/m/Y' => true,
                'output' => '25/10/2020 02:00:00'
            ],
            'marzo: solare(local)-> solare(GMT-1)' => [
                'input' => '2020-03-29 01:59:59',
                'locToGMT-1' => true,
                'd/m/Y' => true,
                'output' => '29/03/2020 01:59:59'
            ],
            'marzo: legale(local)-> solare(GMT-1)' => [
                'input' => '2020-03-29 03:00:00',
                'locToGMT-1' => true,
                'd/m/Y' => true,
                'output' => '29/03/2020 02:00:00'
            ],
            'ottobre: legale(local)-> solare(GMT-1)' => [
                'input' => '2020-10-25 01:59:59',
                'locToGMT-1' => true,
                'd/m/Y' => true,
                'output' => '25/10/2020 00:59:59'
            ],
            'ottobre: solare(local)-> solare(GMT-1)' => [
                'input' => '2020-10-25 02:00:00',
                'locToGMT-1' => true,
                'd/m/Y' => true,
                'output' => '25/10/2020 02:00:00'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @coversNothing
     */
    public function queryProvider() : array   
    {
        $data = [
            'scarichi' => [
                'dbcore',
                'query_scarichi',
                [
                    '30030'
                ]                
            ],
            'variabili_scarichi' => [
                'dbcore',
                'query_variabili_scarichi',
                [
                    '1'
                ]                
            ],
            'variabili_SSCP' => [
                'SSCP_data',
                'query_variabili',
                [
                    '42'
                ]                
            ],
            'variabili_SPT' => [
                'SPT',
                'query_variabili',
                [
                    '165071'
                ]                
            ],
            'dati_acquisiti' => [
                'SSCP_data',
                'query_dati_acquisiti',
                [
                    '42',
                    '2',
                    '01/01/2020 00:00:00',
                    '01/02/2020 00:00:00',
                    '01/01/2020 00:00:00',
                    '01/02/2020 00:00:00'
                ]                
            ],
            'sfiori' => [
                'dbcore',
                'query_sfiori',
                [
                    '1'
                ]                
            ]
        ];
    
        return $data;
    }
    
    /**
     * @coversNothing
     */
    public function dbNameProvider() : array   
    {
        $data = [
            'dbcore' => [
                'dbcore'
            ],
            'spt' => [
                'SPT'
            ],
            'sscp_data' => [
                'SSCP_data'
            ],
            'dbutz' => [
                'dbutz'
            ],
            'dbumd' => [
                'dbumd'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @coversNothing
     */
    public function nullProvider() : array   
    {
        $data = [
            'null' => [
                null
            ],
            'void array' => [
                []
            ],
            'altre chiavi' => [
                [
                    'full' => '0'
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @coversNothing
     */
    public function datesProvider() : array   
    {
        $data = [
            'from' => [
                [
                    'datefrom' => '01/01/2020'
                ]
            ],
            'to' => [
                [
                    'dateto' => '01/04/2020'
                ]
            ],
            'from and to' => [
                [
                    'datefrom' => '01/01/2020',
                    'dateto' => '01/04/2020'
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @coversNothing
     */
    public function resultsProvider() : array    
    {
        $data1 = $this->nullProvider();
        $data2 = $this->datesProvider();
        
        $data = array_merge_recursive($data1, $data2);
        
        $today = date('d/m/Y');
        $engToday = date('Y-m-d');
        $tomorrow = date('d/m/Y', strtotime($engToday . ' +1 day'));
        
        $res = [
            'null' => [
                $today,
                $tomorrow
            ],
            'void array' => [
                $today,
                $tomorrow
            ],
            'altre chiavi' => [
                $today,
                $tomorrow
            ],
            'from' => [
                '01/01/2020',
                $today
            ],
            'to' => [
                '01/04/2020',
                '02/04/2020'
            ],
            'from and to' => [
                '01/01/2020',
                '01/04/2020'
            ]
        ];
        $response = array_merge_recursive($data, $res);
                
        return $response;
    }
    
    /**
     * @coversNothing
     */
    public function dateTimesProvider() : array   
    {
        $data = [
            'times en format' => [
                [
                    'datefrom' => '2020-30-11 21:30:00',
                    'dateto' => '2020-31-12 21:30:00'
                ]
            ],
            'date error' => [
                [
                    'datefrom' => '31/11/2020 21:30:00',
                    'dateto' => '31/12/2020 21:30:00'
                ]
            ],
            'time error' => [
                [
                    'datefrom' => '30/11/2020 21:63:00',
                    'dateto' => '31/12/2020 21:30:00'
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @coversNothing
     */
    public function badUrlProvider() : array
    {
        $data = [
            'no var' => [
                [
                    'datefrom' => '01/01/2020',
                    'dateto' => '01/02/2020',
                    'full' => '1'
                ]
            ],
            'complete bad var' => [
                [
                    'var' => '40030',
                    'datefrom' => '01/01/2020',
                    'dateto' => '01/02/2020',
                    'full' => '1'
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @coversNothing
     */
    public function goodUrlProvider() : array
    {
        $data = [
            'complete' => [
                [
                    'var' => '30030',
                    'datefrom' => '01/01/2020',
                    'dateto' => '01/02/2020',
                    'full' => '1',
                    'field' => 'livello'
                ]
            ],
            'only var' => [
                [
                    'var' => '30030'
                ]
            ],
            'alias var' => [
                [
                    'variabile' => '30030'
                ]
            ],
            'field' => [
                [
                    'var' => '30030',
                    'field' => 'livello' 
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @coversNothing     * 
     */
    public function responseUrlProvider() : array
    {
        $data = $this->goodUrlProvider();
        
        $today = date('d/m/Y');
        $engToday = date('Y-m-d');
        $tomorrow = date('d/m/Y', strtotime($engToday . ' +1 day'));
        
        $dataRes = [
            'complete' => [
                [
                    'var' => '30030',
                    'datefrom' => '01/01/2020',
                    'dateto' => '01/02/2020',
                    'full' => '1',
                    'field' => 'livello'
                ]
            ],
            'only var' => [
                [
                    'var' => '30030',
                    'datefrom' => $today,
                    'dateto' => $tomorrow,
                    'full' => '1',
                    'field' => 'volume'
                ]
            ],
            'alias var' => [
                [
                    'var' => '30030',
                    'datefrom' => $today,
                    'dateto' => $tomorrow,
                    'full' => '1',
                    'field' => 'volume'
                ]
            ],
            'field' => [
                [
                    'var' => '30030',
                    'datefrom' => $today,
                    'dateto' => $tomorrow,
                    'full' => '1',
                    'field' => 'livello' 
                ]
            ]
        ];
        
        $response = array_merge_recursive($data, $dataRes);
        
        return $response;
    }
    
    /**
     * @coversNothing
     */
    public function fieldsProvider() : array    
    {
        $data = [
            'null' => [
                null,
                'volume'
            ],
            'void array' => [
                [],
                'volume'
            ],
            'altre chiavi' => [
                [
                    'full' => '0'
                ],
                'volume'
            ],
            'livello' => [
                [
                    'field' => 'livello'
                ],
                'livello'
            ],
            'portata' => [
                [
                    'field' => 'portata'
                ],
                'portata'
            ],
            'media' => [
                [
                    'field' => 'media'
                ],
                'media'
            ],
            'delta' => [
                [
                    'field' => 'delta'
                ],
                'delta'
            ],
            'altezza' => [
                [
                    'field' => 'altezza'
                ],
                'altezza'
            ],
            'volume' => [
                [
                    'field' => 'volume'
                ],
                'volume'
            ],
            'not present' => [
                [
                    'field' => 'pippo'
                ],
                'volume'
            ]
            
        ];
        
        return $data;
    }
    
    /**
     * @group tools
     * @covers checkFilter()
     */
    public function testCheckFilterTrue() : void
    {
        $request = null;
        
        $this->assertTrue(
            checkFilter($request)
        );
    }
    
    /**
     * @group tools
     * @covers checkFilter()
     */
    public function testCheckFilterTrue1() : void
    {
        $request = array();
        
        $this->assertTrue(
            checkFilter($request)
        );
    }
    
    /**
     * @group tools
     * @covers checkFilter()
     */
    public function testCheckFilterTrue2() : void
    {
        $request = array('var' => '30030');
        
        $this->assertTrue(
            checkFilter($request)
        );
    }
    
    /**
     * @group tools
     * @covers checkFilter()
     */
    public function testCheckFilterTrue3() : void
    {
        $request = array('full' => 'any string');
        
        $this->assertTrue(
            checkFilter($request)
        );
    }     
    
    /**
     * @group tools
     * @covers checkFilter()
     */
    public function testCheckFilterNotTrue() : void
    {
        $request = array('full' => '0');
        
        $this->assertNotTrue(
            checkFilter($request)
        );
    }    
    
    /**
     * @group tools
     * @covers checkVariable()
     */
    public function testCheckVariableException() : void
    {
        $request = null;
        
        $this->expectException(\Exception::class);
        
        checkVariable($request);
    }
    
    /**
     * @group tools
     * @covers checkVariable()
     */
    public function testCheckVariableException2() : void
    {
        $request = array();
        
        $this->expectException(\Exception::class);
        
        checkVariable($request);
    }
    
    /**
     * @group tools
     * @covers checkVariable()
     */
    public function testCheckVariableException3() : void
    {
        $request = array('full' => '0');
        
        $this->expectException(\Exception::class);
        
        checkVariable($request);
    }
    
    /**
     * @group tools
     * @covers checkVariable()
     */
    public function testCheckVariableException4() : void
    {
        $input = array('var', 'variable', 'variabile');
        
        $rand_keys = array_rand($input);
        
        $request = array($input[$rand_keys] => '');
        
        $this->expectException(\Exception::class);
        
        checkVariable($request);
    }
    
    /**
     * @group tools
     * @covers checkVariable()
     */
    public function testCheckVariableException5() : void
    {
        $keys = array('var', 'variable', 'variabile');
        
        $rand_keys = array_rand($keys);
        
        $valueLow = strval(rand(0, 29999));
        
        $valueHigh = strval(rand(40000, 999999));
        
        $values = array($valueLow, $valueHigh);
        
        $rand_values = array_rand($values);
        
        $request = array($keys[$rand_keys] => $values[$rand_values]);
        
        $this->expectException(\Exception::class);
        
        checkVariable($request);
    }
    
    /**
     * @group tools
     * @covers checkVariable()
     */
    public function testCheckVariableIsString() : void
    {
        $input = array('var', 'variable', 'variabile');
        
        $rand_keys = array_rand($input);
        
        $value = strval(rand(30000, 39999));
                
        $request = array($input[$rand_keys] => $value);
        
        $this->assertIsString(            
            checkVariable($request)
        );        
    }
    
    /**
     * @group tools
     * @covers checkVariable()
     */
    public function testCheckVariableEquals() : void
    {                        
        $request = array('var' => '30030');
        
        $this->assertEquals(
            '30030',
            checkVariable($request)
        );        
    }
    
    /**
     * @group tools
     * @covers formatDate()
     */
    public function testFormatDateException() : void
    {
        $date = '32/12/2020';
        
        $this->expectException(\Exception::class);
        
        formatDate($date);
    }
    
    /**
     * @group tools
     * @covers formatDate()
     */
    public function testFormatDateException1() : void
    {
        $date = '31-12-2020';
        
        $this->expectException(\Throwable::class);
        
        formatDate($date);
    }
    
    /**
     * @group tools
     * @covers formatDate()
     */
    public function testFormatDateException2() : void
    {
        $date = '2020/31/12';
        
        $this->expectException(\Throwable::class);
        
        formatDate($date);
    }
    
    /**
     * @group tools
     * @covers formatDate()
     */
    public function testFormatDateException3() : void
    {
        $date = '31 dicembre 2020';
        
        $this->expectException(\Throwable::class);
        
        formatDate($date);
    }
    
    /**
     * @group tools
     * @covers formatDate()
     */
    public function testFormatDateIsString() : void
    {
        $date = '31/12/2020';
        
        $this->assertIsString(            
            formatDate($date)
        );        
    }
    
    /**
     * @group tools
     * @covers formatDate()
     */
    public function testFormatDateEquals() : void
    {
        $date = '31/12/2020';
        
        $this->assertEquals(
            '2020-12-31',
            formatDate($date)
        );        
    }
    
    /**
     * @group tools
     * @covers formatDateTime()
     */
    public function testFormatDateTimeException() : void
    {
        $dateTime = '32/12/2020 21:30:00';
        
        $this->expectException(\Exception::class);
        
        formatDateTime($dateTime);
    }
    
    /**
     * @group tools
     * @covers formatDateTime()
     */
    public function testFormatDateTimeException1() : void
    {
        $dateTime = '31/12/2020 21:63:00';
        
        $this->expectException(\Exception::class);
        
        formatDateTime($dateTime);
    }
    
    /**
     * @group tools
     * @covers formatDateTime()
     */
    public function testFormatDateTimeException2() : void
    {
        $dateTime = '31/12/2020T21:30:00';
        
        $this->expectException(\Exception::class);
        
        formatDateTime($dateTime);
    }
    
    /**
     * @group tools
     * @covers formatDateTime()
     */
    public function testFormatDateTimeException3() : void
    {
        $dateTime = '2020-31-12T21:30:00';
        
        $this->expectException(\Exception::class);
        
        formatDateTime($dateTime);
    }
    
    /**
     * @group tools
     * @covers formatDateTime()
     */
    public function testFormatDateTimeIsString() : void
    {
        $dateTime = '31/12/2020 21:30:00';
        
        $this->assertIsString(            
            formatDateTime($dateTime)
        );        
    }
    
    /**
     * @group tools
     * @covers formatDateTime()
     */
    public function testFormatDateTimeEquals() : void
    {
        $dateTime = '31/12/2020 21:30:00';
        
        $this->assertEquals(
            '2020-12-31 21:30:00',
            formatDateTime($dateTime)
        );        
    }
    
    
    
    /**
     * @group tools 
     * @covers checkInterval()
     * @dataProvider nullProvider
     * @dataProvider datesProvider
     */    
    public function testCheckIntervalIsArray(?array $data) : void
    {
        $result = checkInterval($data);
        
        $this->assertIsArray(
            $result
        );
    }
    
    /**
     * @group tools 
     * @covers checkInterval()
     * @dataProvider nullProvider
     * @dataProvider datesProvider
     */ 
    public function testCheckIntervalArrayHasKey(?array $data) : void
    {
        $result = checkInterval($data);

        $this->assertArrayHasKey(
            'datefrom',
            $result
        );

        $this->assertArrayHasKey(
            'dateto',
            $result
        );
    }

    /**
     * @group tools 
     * @covers checkInterval()
     * @dataProvider nullProvider
     * @dataProvider datesProvider
     */  
    public function testCheckIntervalIsObject(?array $data) : void
    {
        $result = checkInterval($data);

        $this->assertIsObject(
            $result['datefrom']
        );

        $this->assertIsObject(
            $result['dateto']
        );
    }

    /**
     * @group tools 
     * @covers checkInterval()
     * @dataProvider nullProvider
     * @dataProvider datesProvider
     */  
    public function testCheckIntervalInstanceOf(?array $data) : void
    {
        $result = checkInterval($data);

        $this->assertInstanceOf(
            \DateTime::class,
            $result['datefrom']
        );

        $this->assertInstanceOf(
            \DateTime::class,
            $result['dateto']
        );
    }
    
    /**
     * @group tools 
     * @covers checkInterval()
     * @dataProvider resultsProvider
     */  
    public function testCheckIntervalEquals(?array $input, string $datafrom, string $datato) : void
    {
        $result = checkInterval($input);
                        
        $this->assertEquals(
            $datafrom,
            $result['datefrom']->format('d/m/Y')
        );

        $this->assertEquals(
            $datato,
            $result['dateto']->format('d/m/Y')
        ); 
    }
    
    
    
    /**
     * @group tools
     * @covers setDateTimes()
     * @dataProvider nullProvider
     * @dataProvider datesProvider
     * @dataProvider dateTimesProvider
     */
    public function testSetDatesTimesException(?array $data) : void
    {
        $this->expectException(\Throwable::class);
        
        setDateTimes($data);
    }
    
    /**
     * @group tools 
     * @covers setDatesTimes()
     */  
    public function testSetDatesTimesEquals() : void
    {
        $input = array('datefrom' => '01/01/2020 21:30:00', 'dateto' => '01/04/2020 21:30:00');
        $datafrom = '01/01/2020 21:30:00';
        $datato = '01/04/2020 21:30:00';
        
        $result = setDateTimes($input);
                        
        $this->assertEquals(
            $datafrom,
            $result['datefrom']->format('d/m/Y H:i:s')
        );

        $this->assertEquals(
            $datato,
            $result['dateto']->format('d/m/Y H:i:s')
        ); 
    }
       
    /**
     * @group index
     * @covers checkRequest()
     * @dataProvider nullProvider
     * @dataProvider datesProvider
     * @dataProvider dateTimesProvider
     * @dataProvider badUrlProvider
     */
    public function testCheckRequestException(?array $data) : void
    {
        $this->expectException(\Exception::class);
        
        checkRequest($data);
    }
    
    /**
     * @group index
     * @covers checkRequest()
     * @dataProvider responseUrlProvider
     */
    public function testCheckRequestEquals(array $data, array $expected) : void
    {
        $actual = checkRequest($data);
                
        $this->assertEquals(
            $expected['var'],
            $actual['var']
        );        
    }
    
    /**
     * @group index
     * @covers checkRequest()
     * @dataProvider responseUrlProvider
     */
    public function testCheckRequestEquals1(array $data, array $expected) : void
    {
        $actual = checkRequest($data);
                
        $this->assertEquals(
            $expected['full'],
            $actual['full']
        );        
    }
    
    /**
     * @group index
     * @covers checkRequest()
     * @dataProvider responseUrlProvider
     */
    public function testCheckRequestEquals2(array $data, array $expected) : void
    {
        $actual = checkRequest($data);
                
        $this->assertEquals(
            $expected['field'],
            $actual['field']
        );        
    }
    
    /**
     * @group index
     * @covers checkRequest()
     * @dataProvider responseUrlProvider
     */
    public function testCheckRequestEquals3(array $data, array $expected) : void
    {
        $actual = checkRequest($data);
                
        $this->assertEquals(
            $expected['datefrom'],
            $actual['datefrom']->format('d/m/Y')
        ); 
        
        $this->assertEquals(
            $expected['dateto'],
            $actual['dateto']->format('d/m/Y')
        ); 
    }
    
    /**
     * @group tools
     * @covers checkField()
     * @dataProvider fieldsProvider
     */
    public function testCheckFieldEquals(?array $data, string $expected) : void
    {
        $actual = checkField($data);
                
        $this->assertEquals($expected, $actual);        
    }
    
    /**
     * @group tools
     * @covers connect()
     * @dataProvider dbNameProvider
     */
    public function testConnectIsResource(string $data) : void
    {
        $actual = connect($data);
                
        $this->assertIsResource($actual);        
    }
    
    /**
     * @group tools
     * @covers connect()
     */
    public function testConnectIsResource1() //: resource
    {
        $actual = connect('dbcore');
                
        $this->assertIsResource($actual);

        return $actual;
    }    
    
    /**
     * @group tools
     * @covers connect()
     */
    public function testConnectException() : void
    {
        $dbName = 'pippo';
        
        $this->expectException(\Exception::class);
        
        connect($dbName);
    }
    
    /**
     * @group tools
     * @covers query()
     * @depends testConnectIsResource1
     */
    public function testQueryIsResource($conn) //: resource
    {
        $fileName = 'query_scarichi';
        $paramValues = array('30030');
        
        $actual = query($conn, $fileName, $paramValues);
                
        $this->assertIsResource($actual);
        
        return $actual;
    }
    
    /**
     * @group tools
     * @covers query()
     * @depends testConnectIsResource1
     */
    public function testQueryIsResource1($conn)  //: resource
    {
        $fileName = 'query_scarichi';
        $paramValues = array('1');
        
        $actual = query($conn, $fileName, $paramValues);
                
        $this->assertIsResource($actual);
        
        return $actual;
    }

    /**
     * @group tools
     * @covers query()
     * @dataProvider queryProvider
     */
    public function testQueryIsResource2(string $dbName, string $fileName, array $paramValues) : void
    {
        $conn = connect($dbName);
        
        $actual = query($conn, $fileName, $paramValues);
                
        $this->assertIsResource($actual);
    }
    
    /**
     * @group tools
     * @covers query()
     * @depends testConnectIsResource1
     */
    public function testQueryException($conn) : void
    {
        $fileName = 'query_scarichi';
        $paramValues = array('');
        
        $this->expectException(\Exception::class);
        
        query($conn, $fileName, $paramValues);
    }
    
    /**
     * @group tools
     * @covers fetch()
     * @depends testQueryIsResource
     */
    public function testFetchSame($stmt) : void
    {        
        $expected = [
            0 => [
                'scarico' => 1,
                'denominazione' => 'Diga Flumineddu - Sfioratore di superficie',
                'db' => 'SSCP_data',
                'variabile' => 30030,
                'tipo' => 'sfioratore di superficie'
            ]
        ];
        
        $actual = fetch($stmt);
                
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group tools
     * @covers fetch()
     * @depends testQueryIsResource1
     */
    public function testFetchSame1($stmt) : void
    {        
        $expected = [];        
        $actual = fetch($stmt);
                
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group tools
     * @covers changeTimeZone()
     * @dataProvider utcProvider
     */
    public function testChangeTimeZoneEquals(string $dateIn, bool $isLocalToUTC, bool $format, string $expected) : void
    {        
        $actual = changeTimeZone($dateIn, $isLocalToUTC, $format);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group tools
     * @covers checkDates()
     * @dataProvider checkDatesProvider
     */
    public function testCheckDatesEqualsWithDelta(string $db, array $params, bool $isLocalToUTC, array $expected) : void
    {      
        $actual = checkDates($db, $params, $isLocalToUTC);
        
        foreach ($expected as $key => $value) {
            $this->assertEqualsWithDelta($value, $actual[$key], 3600);
        }
        
        foreach ($actual as $key => $value) {
            $this->assertEqualsWithDelta($expected[$key], $value, 3600);
        }
    }
    
    /**
     * @group tools
     * @covers datesToString()
     */    
    public function testDatesToStringIsString() : void
    {
        $dates = [
            'data' => New \DateTime()
        ];
        
        $actual = datesToString($dates, true);
        
        $this->assertIsString($actual['data']);
        
        $actual = datesToString($dates, false);
        
        $this->assertIsString($actual['data']);
                
    }
    
    /**
     * @group tools
     * @covers datesToString()
     */    
    public function testDatesToStringIsNotString() : void
    {
        $dates = [
            'notData' => 123456
        ];
        
        $actual = datesToString($dates, true);
        
        $this->assertIsNotString($actual['notData']);
        
        $actual = datesToString($dates, false);
        
        $this->assertIsNotString($actual['notData']);
                
    }
    
    /**
     * @group tools
     * @covers setToLocal()
     * @dataProvider setToLocalProvider
     */
    public function testSetToLocalEqualsWithDelta(array $dati, string $db, array $expected) : void
    {
        $actual = setToLocal($db, $dati);
        
        foreach ($expected as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEqualsWithDelta($value, $actual[$row][$key], 3600);
            }
        }
        
        foreach ($actual as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEqualsWithDelta($expected[$row][$key], $value, 3600);
            }
        }
    }
    
    /**
     * @group tools
     * @covers setToLocal()    
     */
    public function testSetToLocalEmpty() : void
    {
        $dati = [];
        $db = 'dbname';
        
        $actual = setToLocal($db, $dati);
        
        $this->assertEmpty($actual);      
    }
    
    /**
     * @group index
     * @covers getDataFromDB()
     * @dataProvider getDataFromDBProvider
     */
    public function testGetDataFromDBEquals(string $db, string $queryFileName, array $parametri, array $expected) : void
    {        
        $actual = getDataFromDB($db, $queryFileName, $parametri);
        
        foreach ($expected as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEquals($value, $actual[$row][$key]);
            }
        }
        
        foreach ($actual as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEquals($expected[$row][$key], $value);
            }
        }
    }
    
    /**
     * @group depends
     * @covers initVolumi()
     */
    public function testInitVolumiEquals() : array
    {        
        $variabili = [
            'id_variabile' => 30030,
            'impianto' => 75,
            'unita_misura' => 'mc'
        ];
        
        $dati = [
            '0' => [
                'variabile' => 165071,
                'valore' => 266.206,
                'data_e_ora' => New \DateTime('2018-01-02 00:00:00'),
                'tipo_dato' => 2
            ],
            '1' => [
                'variabile' => 165071,
                'valore' => 266.140,
                'data_e_ora' => New \DateTime('2018-01-02 00:15:00'),
                'tipo_dato' => 2
            ]
        ];
        
        $expected = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => New \DateTime('2018-01-02 00:00:00'),
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => New \DateTime('2018-01-02 00:15:00'),
                'tipo_dato' => 1
            ]
        ];
        
        $actual = initVolumi($variabili, $dati);
        
        foreach ($actual as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEquals($expected[$row][$key], $value);
            }
        }
        
        foreach ($expected as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEquals($value, $actual[$row][$key]);
            }
        }
        return $actual;        
    }
    
    /**
     * @group depends
     * @covers addLivello()
     * @depends testInitVolumiEquals     
     */
    public function testAddLivelloEquals(array $volumi) : array
    {            
        $dati = [
            '0' => [
                'variabile' => 165071,
                'valore' => 266.206,
                'data_e_ora' => New \DateTime('2018-01-02 00:00:00'),
                'tipo_dato' => 2
            ],
            '1' => [
                'variabile' => 165071,
                'valore' => 266.140,
                'data_e_ora' => New \DateTime('2018-01-02 00:15:00'),
                'tipo_dato' => 2
            ]
        ];
        
        $expected = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => New \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => New \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'tipo_dato' => 1
            ]
        ];
        
        $actual = addLivello($volumi, $dati);
        
        foreach ($actual as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEquals($expected[$row][$key], $value);
            }
        }
        
        foreach ($expected as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEquals($value, $actual[$row][$key]);
            }
        }
        return $actual;
    }
    
    /**
     * @group depends
     * @covers addLivello()
     * @depends testInitVolumiEquals
     */
    public function testAddLivelloException(array $volumi) : array
    {            
        $dati = [
            '0' => [
                'variabile' => 165071,
                'valore' => 266.206,
                'data_e_ora' => New \DateTime('2018-01-02 00:00:00'),
                'tipo_dato' => 2
            ],
            '1' => [
                'variabile' => 165071,
                'valore' => 266.140,
                'data_e_ora' => New \DateTime('2018-01-02 00:15:00'),
                'tipo_dato' => 2
            ],
            '2' => [
                'variabile' => 165071,
                'valore' => 266.127,
                'data_e_ora' => New \DateTime('2018-01-02 00:30:00'),
                'tipo_dato' => 2
            ]
        ];
        
        $this->expectException(\Exception::class);
        
        $actual = addLivello($volumi, $dati);       
    }
    
    /**
     * @group depends
     * @covers addLivello()
     * @depends testInitVolumiEquals
     */
    public function testAddLivelloException1(array $volumi) : void
    {            
        $dati = [
            '0' => [
                'variabile' => 165071,
                'valore' => 266.206,
                'data_e_ora' => New \DateTime('2018-01-02 00:00:00'),
                'tipo_dato' => 2
            ],
            '1' => [
                'variabile' => 165071,
                'valore' => 266.140,
                'data_e_ora' => New \DateTime('2018-01-02 00:30:00'),
                'tipo_dato' => 2
            ]
        ];
        
        $this->expectException(\Exception::class);
        
        $actual = addLivello($volumi, $dati);       
    }
    
    /**
     * @group depends
     * @covers addMedia()
     * @depends testAddLivelloEquals
     */
    public function testAddMediaEquals(array $volumi) : array
    {         
        $campo = 'livello';
        
        $expected = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => New \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media' => 266.206,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => New \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media' => 266.173,                
                'tipo_dato' => 1
            ]
        ];
        
        $actual = addMedia($volumi, $campo);
        
        foreach ($actual as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEquals($expected[$row][$key], $value);
            }
        }
        
        foreach ($expected as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEquals($value, $actual[$row][$key]);
            }
        }
        return $actual;
    }
    
    /**
     * @group depends
     * @covers addMedia()
     * @depends testAddLivelloEquals
     */
    public function testAddMediaException(array $volumi) : void
    {         
        $campo = 'pippo';
        
        $this->expectException(\Exception::class);
        
        $actual = addMedia($volumi, $campo);
        
        
    }
    
    /**
     * @group depends
     * @covers addAltezza()
     * @depends testAddMediaEquals
     */
    public function testAddAltezzaEquals(array $volumi) : array
    {         
        $quota = 266.180;
        
        $campo = 'media';
        
        $expected = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => New \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media' => 266.206,
                'altezza' => 0.026,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => New \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media' => 266.173,
                'altezza' => -0.007,
                'tipo_dato' => 1
            ]
        ];
        
        $actual = addAltezza($volumi, $quota, $campo);
        
        foreach ($actual as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEquals($expected[$row][$key], $value);
            }
        }
        
        foreach ($expected as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEquals($value, $actual[$row][$key]);
            }
        }
        return $actual;
    }
    
    /**
     * @group depends
     * @covers addAltezza()
     * @depends testAddMediaEquals
     */
    public function testAddAltezzaException(array $volumi) : array
    {         
        $quota = 266.180;
        
        $campo = 'pippo';
        
        $this->expectException(\Exception::class);
        
        $actual = addAltezza($volumi, $quota, $campo);
        
        
    }
    
    /**
     * @group depends
     * @covers addPortata()
     * @depends testAddAltezzaEquals
     */
    public function testAddPortataEqualsWithDelta(array $volumi) : array
    {         
        $specifiche = [            
            'scarico' => 1,
            'mi' => 0.47,
            'larghezza' => 40.5,
            'quota' => 276.5,
            'limite' => 942.67            
        ];
        
        $expected = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => New \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media' => 266.206,
                'altezza' => 0.026,
                'portata' => 0.353,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => New \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media' => 266.173,
                'altezza' => -0.007,
                'portata' => 0,
                'tipo_dato' => 1
            ]
        ];
        
        $actual = addPortata($volumi, $specifiche);
        
        foreach ($actual as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEqualsWithDelta($expected[$row][$key], $value, 0.001);
            }
        }
        
        foreach ($expected as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEqualsWithDelta($value, $actual[$row][$key], 0.001);
            }
        }
        return $actual;
    }
    
    /**
     * @group depends
     * @covers addPortata()
     * @depends testAddAltezzaEquals
     */
    public function testAddPortataEqualsWithDelta1(array $volumi) : void
    {         
        $specifiche = [            
            'scarico' => 1,
            'mi' => 0.47,
            'larghezza' => 40.5,
            'quota' => 276.5,
            'limite' => 0.35            
        ];
        
        $expected = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => New \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media' => 266.206,
                'altezza' => 0.026,
                'portata' => 0,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => New \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media' => 266.173,
                'altezza' => -0.007,
                'portata' => 0,
                'tipo_dato' => 1
            ]
        ];
        
        $actual = addPortata($volumi, $specifiche);
        
        foreach ($actual as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEqualsWithDelta($expected[$row][$key], $value, 0.001);
            }
        }
        
        foreach ($expected as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEqualsWithDelta($value, $actual[$row][$key], 0.001);
            }
        }
    }
    
    /**
     * @group depends
     * @covers addDelta()
     * @depends testAddPortataEqualsWithDelta
     */
    public function testAddDeltaEqualsWithDelta(array $volumi) : array
    {         
        $campo = 'data_e_ora';
        
        $expected = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => New \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media' => 266.206,
                'altezza' => 0.026,
                'portata' => 0.353,
                'delta' => 0,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => New \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media' => 266.173,
                'altezza' => -0.007,
                'portata' => 0,
                'delta' => 900,
                'tipo_dato' => 1
            ]
        ];
        
        $actual = addDelta($volumi, $campo);
        
        foreach ($actual as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEqualsWithDelta($expected[$row][$key], $value, 0.001);
            }
        }
        
        foreach ($expected as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEqualsWithDelta($value, $actual[$row][$key], 0.001);
            }
        }
        return $actual;
    }
    
    /**
     * @group depends
     * @covers addDelta()
     * @depends testAddPortataEqualsWithDelta
     */
    public function testAddDeltaException(array $volumi) : void
    {         
        $campo = 'pippo';
        
        $this->expectException(\Exception::class);
        
        $actual = addDelta($volumi, $campo);    
    }
    
    /**
     * @group depends
     * @covers addDelta()
     * @depends testAddPortataEqualsWithDelta
     */
    public function testAddDeltaException1(array $volumi) : void
    {         
        $campo = 'livello';
        
        $this->expectException(\Exception::class);
        
        $actual = addDelta($volumi, $campo);    
    }
    
    /**
     * @group depends
     * @covers addVolume()
     * @depends testAddDeltaEqualsWithDelta
     */
    public function testAddVolumeEqualsWithDelta(array $volumi) : array
    {
        $expected = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => New \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media' => 266.206,
                'altezza' => 0.026,
                'portata' => 0.353,
                'delta' => 0,
                'volume' => 0,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => New \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media' => 266.173,
                'altezza' => -0.007,
                'portata' => 0,
                'delta' => 900,
                'volume' => 0,
                'tipo_dato' => 1
            ]
        ];
        
        $actual = addVolume($volumi);
        
        foreach ($actual as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEqualsWithDelta($expected[$row][$key], $value, 0.001);
            }
        }
        
        foreach ($expected as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEqualsWithDelta($value, $actual[$row][$key], 0.001);
            }
        }
        return $actual;
    }
    
    /**
     * @group depends
     * @covers format()
     * @depends testAddVolumeEqualsWithDelta
     */
    public function testFormatEquals(array $volumi) : array
    {
        $campo = 'volume';
        
        $expected = [
            '0' => [
                'variabile' => '30030',
                'valore' => '0',
                'data_e_ora' => '02/01/2018 00:00:00',     
                'tipo_dato' => '1'
            ],
            '1' => [
                'variabile' => '30030',                
                'valore' => '0',
                'data_e_ora' => '02/01/2018 00:15:00',                
                'tipo_dato' => '1'
            ]
        ];
        
        $actual = format($volumi, $campo);
        
        foreach ($actual as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEquals($expected[$row][$key], $value);
            }
        }
        
        foreach ($expected as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEquals($value, $actual[$row][$key]);
            }
        }
        return $actual;
    }
    
    /**
     * @group depends
     * @covers format()
     * @depends testAddVolumeEqualsWithDelta    
     */
    public function testFormatEquals1(array $volumi) : void
    {        
        $campo = 'altezza';
        
        $expected = [
            '0' => [
                'variabile' => '30030',
                'valore' => '0,026',
                'data_e_ora' => '02/01/2018 00:00:00',     
                'tipo_dato' => '1'
            ],
            '1' => [
                'variabile' => '30030',                
                'valore' => '-0,007',
                'data_e_ora' => '02/01/2018 00:15:00',                
                'tipo_dato' => '1'
            ]
        ];
        
        $actual = format($volumi, $campo);
        
        foreach ($actual as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEquals($expected[$row][$key], $value);
            }
        }
        
        foreach ($expected as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEquals($value, $actual[$row][$key]);
            }
        }
    }
    
    /**
     * @group depends
     * @covers format()
     * @depends testAddVolumeEqualsWithDelta    
     */
    public function testFormatEquals2(array $volumi) : array
    {        
        $campo = 'delta';
        
        $expected = [
            '0' => [
                'variabile' => '30030',
                'valore' => '0',
                'data_e_ora' => '02/01/2018 00:00:00',     
                'tipo_dato' => '1'
            ],
            '1' => [
                'variabile' => '30030',                
                'valore' => '900',
                'data_e_ora' => '02/01/2018 00:15:00',                
                'tipo_dato' => '1'
            ]
        ];
        
        $actual = format($volumi, $campo);
        
        foreach ($actual as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEquals($expected[$row][$key], $value);
            }
        }
        
        foreach ($expected as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEquals($value, $actual[$row][$key]);
            }
        }
        return $actual;
    }
    
    /**
     * @group depends
     * @covers format()
     * @depends testAddVolumeEqualsWithDelta    
     */
    public function testFormatException(array $volumi) : void
    {        
        $campo = 'pippo';
        
        $this->expectException(\Exception::class);
        
        $actual = format($volumi, $campo);       
    }
    
    /**
     * @group depends
     * @covers filter()
     * @depends testFormatEquals
     */
    public function testFilterEquals(array $volumi) : array
    {
        $full = true;
        
        $expected = [
            '0' => [
                'variabile' => '30030',
                'valore' => '0',
                'data_e_ora' => '02/01/2018 00:00:00',     
                'tipo_dato' => '1'
            ],
            '1' => [
                'variabile' => '30030',                
                'valore' => '0',
                'data_e_ora' => '02/01/2018 00:15:00',                
                'tipo_dato' => '1'
            ]
        ];
        
        $actual = filter($volumi, $full);
        
        foreach ($actual as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEquals($expected[$row][$key], $value);
            }
        }
        
        foreach ($expected as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEquals($value, $actual[$row][$key]);
            }
        }
        return $actual;
    }
    
    /**
     * @group depends
     * @covers filter()
     * @depends testFormatEquals2
     */
    public function testFilterEquals1(array $volumi) : void
    {
        $full = false;
        
        $expected = [
            '1' => [
                'variabile' => '30030',                
                'valore' => '900',
                'data_e_ora' => '02/01/2018 00:15:00',                
                'tipo_dato' => '1'
            ]
        ];
        
        $actual = filter($volumi, $full);
        
        foreach ($actual as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEquals($expected[$row][$key], $value);
            }
        }
        
        foreach ($expected as $row => $fields) {
            foreach ($fields as $key => $value) {
                $this->assertEquals($value, $actual[$row][$key]);
            }
        }
    }
    
    /**
     * @group depends
     * @covers filter()
     * @depends testFormatEquals
     */
    public function testFilterEmpty(array $volumi) : void
    {
        $full = false;
        
        $actual = filter($volumi, $full);
        
        $this->assertEmpty($actual);
    }
    
    /**
     * @group depends
     * @covers setFile()
     * @dataProvider setFileProvider
     */
    public function testSetFileEquals(string $variabile, array $dates, bool $filtered, string $field, string $expected) : void
    {
        $actual = setFile($variabile, $dates, $filtered, $field);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group csv
     * @covers printToCSV()    
     */
    public function testPrintToCsvFileExists() : void
    {
        $fileName = __DIR__ . '/../providers/test.csv';
        
        $dati = [
            '0' => [
                'variabile' => '30030',
                'data_e_ora' => '25/03/2018 20:30:00',
                'tipo_dato' => '1',
                'valore' => '2221,930'
            ],
            '1' => [
                'variabile' => '30030',
                'data_e_ora' => '25/03/2018 20:45:00',
                'tipo_dato' => '1',
                'valore' => '2250,234'
            ],
            '2' => [
                'variabile' => '30030',
                'data_e_ora' => '25/03/2018 21:00:00',
                'tipo_dato' => '1',
                'valore' => '2278,234'
            ]    
        ];       
        
        printToCSV($dati, $fileName);
        
        $this->assertFileExists($fileName);
    }
    
    /**
     * @group csv
     * @coversNothing
     */
    public function printToCsvProvider() : \vaniacarta74\scarichi\tests\classes\CsvFileIterator
    {
        $fileName = __DIR__ . '/../providers/test.csv';
        
        $iterator = new \vaniacarta74\scarichi\tests\classes\CsvFileIterator($fileName);
        
        return $iterator;
    }
    
    /**
     * @group csv
     * @covers printToCSV()    
     * @dataProvider printToCsvProvider
     */
    public function testPrintToCsvEquals() : void
    {
        $expecteds = [
            '0' => ['variabile','data_e_ora','tipo_dato','valore'],
            '1' => ['30030','25/03/2018 20:30:00','1','2221,930'],
            '2' => ['30030','25/03/2018 20:45:00','1','2250,234'],
            '3' => ['30030','25/03/2018 21:00:00','1','2278,234']
        ];
        
        $actual = func_get_args();
        
        foreach ($expecteds as $row => $expected) {
            if ($expected === $actual) {
                break;
            }
        }
        
        $this->assertEquals($expecteds[$row], $actual);
    }
    
    /**
     * @group csv
     * @covers printPart()
     */
    public function testPrintPart() : void
    {
        $i = 2;
        $filtered = false;
        $field = 'livello';
        
        $dati = [
            '0' => [
                'variabile' => '30030',
                'data_e_ora' => '25/03/2018 20:30:00',
                'tipo_dato' => '1',
                'valore' => '2221,930'
            ],
            '1' => [
                'variabile' => '30030',
                'data_e_ora' => '25/03/2018 20:45:00',
                'tipo_dato' => '1',
                'valore' => '2250,234'
            ],
            '2' => [
                'variabile' => '30030',
                'data_e_ora' => '25/03/2018 21:00:00',
                'tipo_dato' => '1',
                'valore' => '2278,234'
            ]    
        ];
        
        $expected = CSV . '/Livello_30030_201803252030_201803252045_full.csv';
        
        printPart($dati, $i, $filtered, $field);
        
        $this->assertFileExists($expected);
    }
    
    /**
     * @group depends
     * @covers divideAndPrint()
     * @depends testFilterEquals
     */
    public function testDivideAndPrintFileExists(array $volumi) : void
    {        
        $full = true;
        $field = 'delta';
        
        $expecteds = [
            CSV . '/Delta_30030_201801020000_201801020000_full.csv',
            CSV . '/Delta_30030_201801020015_201801020015_full.csv',
        ];
        
        divideAndPrint($volumi, $full, $field, 1);
        
        foreach ($expecteds as $row => $expected) {
            $this->assertFileExists($expected);
        }       
    }
    
    /**
     * @group depends
     * @covers divideAndPrint()
     * @depends testFilterEquals
     */
    public function testDivideAndPrintFileExists1(array $volumi) : void
    {        
        $full = true;
        $field = 'delta';
        
        $expecteds = [
            CSV . '/Delta_30030_201801020000_201801020015_full.csv'
        ];
        
        divideAndPrint($volumi, $full, $field);
        
        foreach ($expecteds as $row => $expected) {
            $this->assertFileExists($expected);
        }       
    }
}
