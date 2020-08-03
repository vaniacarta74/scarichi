<?php
namespace vaniacarta74\scarichi\tests\Unit;

use PHPUnit\Framework\TestCase;

use function vaniacarta74\scarichi\src\checkFilter as checkFilter;
use function vaniacarta74\scarichi\src\checkVariable as checkVariable;
use function vaniacarta74\scarichi\src\formatDate as formatDate;
use function vaniacarta74\scarichi\src\formatDateTime as formatDateTime;
use function vaniacarta74\scarichi\src\checkInterval as checkInterval;
use function vaniacarta74\scarichi\src\setDateTimes as setDateTimes;
use function vaniacarta74\scarichi\src\checkRequest as checkRequest;
use function vaniacarta74\scarichi\src\checkField as checkField;
use function vaniacarta74\scarichi\src\connect as connect;
use function vaniacarta74\scarichi\src\query as query;
use function vaniacarta74\scarichi\src\fetch as fetch;
use function vaniacarta74\scarichi\src\changeTimeZone as changeTimeZone;
use function vaniacarta74\scarichi\src\checkDates as checkDates;
use function vaniacarta74\scarichi\src\datesToString as datesToString;
use function vaniacarta74\scarichi\src\setToLocal as setToLocal;
use function vaniacarta74\scarichi\src\getDataFromDb as getDataFromDb;
use function vaniacarta74\scarichi\src\initVolumi as initVolumi;
use function vaniacarta74\scarichi\src\addCategoria as addCategoria;
use function vaniacarta74\scarichi\src\addMedia as addMedia;
use function vaniacarta74\scarichi\src\addAltezza as addAltezza;
use function vaniacarta74\scarichi\src\addPortata as addPortata;
use function vaniacarta74\scarichi\src\addDelta as addDelta;
use function vaniacarta74\scarichi\src\addVolume as addVolume;
use function vaniacarta74\scarichi\src\format as format;
use function vaniacarta74\scarichi\src\setFile as setFile;
use function vaniacarta74\scarichi\src\printToCSV as printToCSV;
use function vaniacarta74\scarichi\src\printPart as printPart;
use function vaniacarta74\scarichi\src\divideAndPrint as divideAndPrint;
use function vaniacarta74\scarichi\src\checkNull as checkNull;
use function vaniacarta74\scarichi\src\response as response;
use function vaniacarta74\scarichi\src\errorHandler as errorHandler;
use function vaniacarta74\scarichi\src\close as close;
use function vaniacarta74\scarichi\src\calcolaPortata as calcolaPortata;
use function vaniacarta74\scarichi\src\integraDate as integraDate;
use function vaniacarta74\scarichi\src\uniformaCategorie as uniformaCategorie;
use function vaniacarta74\scarichi\src\completaDati as completaDati;
use function vaniacarta74\scarichi\src\trovaCapi as trovaCapi;
use function vaniacarta74\scarichi\src\riempiCode as riempiCode;
use function vaniacarta74\scarichi\src\riempiNull as riempiNull;
use function vaniacarta74\scarichi\src\interpolaNull as interpolaNull;
use function vaniacarta74\scarichi\src\interpola as interpola;
use function vaniacarta74\scarichi\src\convertiUnita as convertiUnita;
use function vaniacarta74\scarichi\src\eraseDoubleDate as eraseDoubleDate;
use function vaniacarta74\scarichi\src\changeDate as changeDate;
use function vaniacarta74\scarichi\src\filter as filter;
use function vaniacarta74\scarichi\src\debugOnCSV as debugOnCSV;


class ToolsTest extends TestCase
{
    
    /**
     * coversNothing
     */
    public function responseProvider() : array
    {
        $data = [
            'standard' => [
                'request' => [
                    'var' => '30030',
                    'datefrom' => new \DateTime('2017-01-01 00:00:00'),
                    'dateto' => new \DateTime('2017-01-02 00:00:00'),
                    'full' => '1',
                    'field' => 'volume'
                ],
                'printed' => true,
                'expected' => 'Elaborazione dati <b>Volume</b> variabile <b>30030</b> dal <b>01/01/2017</b> al <b>02/01/2017</b> avvenuta con successo. File CSV <b>full</b> esportati.'
            ],
            'full no print' => [
                'request' => [
                    'var' => '30030',
                    'datefrom' => new \DateTime('2017-01-01 00:00:00'),
                    'dateto' => new \DateTime('2017-01-02 00:00:00'),
                    'full' => '1',
                    'field' => 'volume'
                ],
                'printed' => false,
                'expected' => 'Elaborazione dati <b>Volume</b> variabile <b>30030</b> dal <b>01/01/2017</b> al <b>02/01/2017</b> avvenuta con successo. Nessun file CSV <b>full</b> esportato per mancanza di dati.'
            ],
            'only datefrom full print' => [
                'request' => [
                    'var' => '30030',
                    'datefrom' => new \DateTime('2020-05-01 00:00:00'),
                    'dateto' => new \DateTime(),
                    'full' => '1',
                    'field' => 'volume'
                ],
                'printed' => true,
                'expected' => 'Elaborazione dati <b>Volume</b> variabile <b>30030</b> dal <b>01/05/2020</b> al <b>' . date('d/m/Y') . '</b> avvenuta con successo. File CSV <b>full</b> esportati.'
            ],
            'field other full print' => [
                'request' => [
                    'var' => '30030',
                    'datefrom' => new \DateTime('2017-01-01 00:00:00'),
                    'dateto' => new \DateTime('2017-01-02 00:00:00'),
                    'full' => '1',
                    'field' => 'livello'
                ],
                'printed' => true,
                'expected' => 'Elaborazione dati <b>Livello</b> variabile <b>30030</b> dal <b>01/01/2017</b> al <b>02/01/2017</b> avvenuta con successo. File CSV <b>full</b> esportati.'
            ],
            'field other no 0 no print' => [
                'request' => [
                    'var' => '30030',
                    'datefrom' => new \DateTime('2017-01-01 00:00:00'),
                    'dateto' => new \DateTime('2017-01-02 00:00:00'),
                    'full' => '0',
                    'field' => 'livello'
                ],
                'printed' => false,
                'expected' => 'Elaborazione dati <b>Livello</b> variabile <b>30030</b> dal <b>01/01/2017</b> al <b>02/01/2017</b> avvenuta con successo. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.'
            ]
        ];
        
        return $data;
    }
    
    /**
     * coversNothing
     */
    public function changeTimeZoneExceptionProvider() : array
    {
        $data = [
            'no time' => ['2018-01-02'],
            'no second' => ['2018-01-02 23:30'],
            'Y-d-m'  => ['2018-28-02 23:30:00'],
            'd/m/Y'  => ['23/12/2020 23:30:00']
        ];
        
        return $data;
    }
    
    /**
     * coversNothing
     */
    public function setFileProvider() : array
    {
        $data = [
            'base' => [
                'variabile' => '30030',
                'date' => [
                    'datefrom' => new \DateTime('2018-01-02 00:15:00'),
                    'dateto' => new \DateTime('2020-04-01 23:59:59')
                ],
                'filtered' => false,
                'field' => 'volume',
                'path' => CSV,
                'output' => CSV . '/Volume_30030_201801020015_202004012359_full.csv'
            ],
            'altro campo' => [
                'variabile' => '30030',
                'date' => [
                    'datefrom' => new \DateTime('2018-01-02 00:15:00'),
                    'dateto' => new \DateTime('2020-04-01 23:59:59')
                ],
                'filtered' => false,
                'field' => 'livello',
                'path' => CSV,
                'output' => CSV . '/Livello_30030_201801020015_202004012359_full.csv'
            ],
            'filtered' => [
                'variabile' => '30030',
                'date' => [
                    'datefrom' => new \DateTime('2018-01-02 00:15:00'),
                    'dateto' => new \DateTime('2020-04-01 23:59:59')
                ],
                'filtered' => true,
                'field' => 'volume',
                'path' => CSV,
                'output' => CSV . '/Volume_30030_201801020015_202004012359_no0.csv'
            ]
        ];
        
        return $data;
    }
    
    /**
     * coversNothing
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
                        'data_attivazione' => new \DateTime('1970-01-01 00:00:00', new \DateTimeZone('Europe/Rome')),
                        'data_disattivazione' => new \DateTime('2017-12-09 00:00:00', new \DateTimeZone('Europe/Rome')),
                        'categoria' => 'livello'
                    ],
                    '1' => [
                        'scarico' => 1,
                        'db' => 'SPT',
                        'variabile' => 165071,
                        'tipo_dato' => 2,
                        'data_attivazione' => new \DateTime('2017-12-09 00:00:00'),
                        'data_disattivazione' => new \DateTime('2070-01-01 00:00:00'),
                        'categoria' => 'livello'
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
                    'data iniziale' => new \DateTime('2018-01-02 00:00:00'),
                    'data finale' => new \DateTime('2018-01-02 00:15:0'),
                    'data attivazione' => new \DateTime('2017-12-09 00:00:00'),
                    'data disattivazione' => new \DateTime('2070-01-01 00:00:00'),
                ],
                'expected' => [
                    '0' => [
                        'variabile' => 165071,
                        'valore' => 266.206,
                        'unita_misura' => 'm',
                        'data_e_ora' => new \DateTime('2018-01-02T00:00:00.000000+0100'),
                        'tipo_dato' => 2
                    ]
                ]
            ],
            'formule' => [
                'dbase' => 'dbcore',
                'file' => 'query_formule',
                'parametri' => [
                    'scarico' => '1'
                ],
                'expected' => [
                    '0' => [
                        'tipo_formula' => 'portata sfiorante',
                        'scarico' => 1,
                        'mi' => 0.47,
                        'scabrosita' => null,
                        'lunghezza' => null,
                        'larghezza' => 40.5,
                        'altezza' => null,
                        'angolo' => null,
                        'raggio' => null,
                        'quota' => 276.5,
                        'velocita' => null,
                        'limite' => 942.67
                    ]
                ]
            ]
        ];
                
        return $data;
    }
    
    /**
     * coversNothing
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
                        'data attivazione' => new \DateTime('now', new \DateTimeZone('Europe/Rome')),
                        'data disattivazione' => new \DateTime('now', new \DateTimeZone('Europe/Rome')),
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
                        'data e ora' => new \DateTime(),
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
     * coversNothing
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
                        'data attivazione' => new \DateTime('now', new \DateTimeZone('Europe/Rome')),
                        'data disattivazione' => new \DateTime('now', new \DateTimeZone('Europe/Rome')),
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
                        'data e ora' => new \DateTime(),
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
     * coversNothing
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
                    'data iniziale' => new \DateTime(),
                    'data finale' => new \DateTime(),
                    'data attivazione' => new \DateTime(),
                    'data disattivazione' => new \DateTime()
                ],
                'localToGMT-1' => true,
                'output' => [
                    'variabile' => '165071',
                    'tipo dato' => '2',
                    'data iniziale' => new \DateTime(),
                    'data finale' => new \DateTime(),
                    'data attivazione' => new \DateTime(),
                    'data disattivazione' => new \DateTime()
                ]
            ],
            'dati acquisiti SPT s->l' => [
                'db' => 'SPT',
                'parametri' => [
                    'variabile' => '165071',
                    'tipo dato' => '2',
                    'data iniziale' => new \DateTime(),
                    'data finale' => new \DateTime(),
                    'data attivazione' => new \DateTime(),
                    'data disattivazione' => new \DateTime()
                ],
                'localToGMT-1' => false,
                'output' => [
                    'variabile' => '165071',
                    'tipo dato' => '2',
                    'data iniziale' => new \DateTime(),
                    'data finale' => new \DateTime(),
                    'data attivazione' => new \DateTime(),
                    'data disattivazione' => new \DateTime()
                ]
            ],
            'dati acquisiti SSCP l->s' => [
                'db' => 'SSCP_data',
                'parametri' => [
                    'variabile' => '42',
                    'tipo dato' => '2',
                    'data iniziale' => new \DateTime(),
                    'data finale' => new \DateTime(),
                    'data attivazione' => new \DateTime(),
                    'data disattivazione' => new \DateTime()
                ],
                'localToGMT-1' => true,
                'output' => [
                    'variabile' => '42',
                    'tipo dato' => '2',
                    'data iniziale' => new \DateTime(),
                    'data finale' => new \DateTime(),
                    'data attivazione' => new \DateTime(),
                    'data disattivazione' => new \DateTime()
                ]
            ],
            'dati acquisiti SSCP s->l' => [
                'db' => 'SSCP_data',
                'parametri' => [
                    'variabile' => '42',
                    'tipo dato' => '2',
                    'data iniziale' => new \DateTime(),
                    'data finale' => new \DateTime(),
                    'data attivazione' => new \DateTime(),
                    'data disattivazione' => new \DateTime()
                ],
                'localToGMT-1' => false,
                'output' => [
                    'variabile' => '42',
                    'tipo dato' => '2',
                    'data iniziale' => new \DateTime(),
                    'data finale' => new \DateTime(),
                    'data attivazione' => new \DateTime(),
                    'data disattivazione' => new \DateTime()
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
     * coversNothing
     */
    public function utcProvider() : array
    {
        $data = [
            'marzo: solare(GMT-1)-> solare(local)' => [
                'input' => '2020-03-29 01:59:59',
                'locToGMT-1' => false,
                'd/m/Y' => true,
                'set' => true,
                'output' => '29/03/2020 01:59:59'
            ],
            'marzo: solare(GMT-1)-> legale(local)' => [
                'input' => '2020-03-29 02:00:00',
                'locToGMT-1' => false,
                'd/m/Y' => true,
                'set' => true,
                'output' => '29/03/2020 03:00:00'
            ],
            'ottobre: solare(GMT-1)-> legale(local)' => [
                'input' => '2020-10-25 01:59:59',
                'locToGMT-1' => false,
                'd/m/Y' => true,
                'set' => true,
                'output' => '25/10/2020 02:59:59'
            ],
            'ottobre: solare(GMT-1)-> solare(local)' => [
                'input' => '2020-10-25 02:00:00',
                'locToGMT-1' => false,
                'd/m/Y' => true,
                'set' => true,
                'output' => '25/10/2020 02:00:00'
            ],
            'marzo: solare(local)-> solare(GMT-1)' => [
                'input' => '2020-03-29 01:59:59',
                'locToGMT-1' => true,
                'd/m/Y' => true,
                'set' => true,
                'output' => '29/03/2020 01:59:59'
            ],
            'marzo: legale(local)-> solare(GMT-1)' => [
                'input' => '2020-03-29 03:00:00',
                'locToGMT-1' => true,
                'd/m/Y' => true,
                'set' => true,
                'output' => '29/03/2020 02:00:00'
            ],
            'ottobre: legale(local)-> solare(GMT-1)' => [
                'input' => '2020-10-25 01:59:59',
                'locToGMT-1' => true,
                'd/m/Y' => true,
                'set' => true,
                'output' => '25/10/2020 00:59:59'
            ],
            'ottobre: solare(local)-> solare(GMT-1)' => [
                'input' => '2020-10-25 02:00:00',
                'locToGMT-1' => true,
                'd/m/Y' => true,
                'set' => true,
                'output' => '25/10/2020 02:00:00'
            ]
        ];
        
        return $data;
    }
    
    /**
     * coversNothing
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
            'formule' => [
                'dbcore',
                'query_formule',
                [
                    '1'
                ]
            ]
        ];
    
        return $data;
    }
    
    /**
     * coversNothing
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
     * coversNothing
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
     * coversNothing
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
     * coversNothing
     */
    public function resultsProvider() : array
    {
        $data = $this->datesProvider();
        
        $res = [
            'from' => [
                '01/01/2020',
                date('d/m/Y')
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
     * coversNothing
     */
    public function dateTimesProvider() : array
    {
        $data = [
            'times and format' => [
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
     * coversNothing
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
     * coversNothing
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
            'only var and dates' => [
                [
                    'var' => '30030',
                    'datefrom' => '01/01/2020',
                    'dateto' => '01/02/2020'
                ]
            ],
            'alias var' => [
                [
                    'variabile' => '30030',
                    'datefrom' => '01/01/2020',
                    'dateto' => '01/02/2020'
                ]
            ],
            'field' => [
                [
                    'var' => '30030',
                    'datefrom' => '01/01/2020',
                    'dateto' => '01/02/2020',
                    'field' => 'livello'
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * coversNothing     *
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
            'only var and dates' => [
                [
                    'var' => '30030',
                    'datefrom' => '01/01/2020',
                    'dateto' => '01/02/2020',
                    'full' => '1',
                    'field' => 'volume'
                ]
            ],
            'alias var' => [
                [
                    'var' => '30030',
                    'datefrom' => '01/01/2020',
                    'dateto' => '01/02/2020',
                    'full' => '1',
                    'field' => 'volume'
                ]
            ],
            'field' => [
                [
                    'var' => '30030',
                    'datefrom' => '01/01/2020',
                    'dateto' => '01/02/2020',
                    'full' => '1',
                    'field' => 'livello'
                ]
            ]
        ];
        
        $response = array_merge_recursive($data, $dataRes);
        
        return $response;
    }
    
    /**
     * coversNothing
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
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group tools
     * covers checkFilter()
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
     * covers checkFilter()
     */
    public function testCheckFilterTrue1() : void
    {
        $request = [];
        
        $this->assertTrue(
            checkFilter($request)
        );
    }
    
    /**
     * @group tools
     * covers checkFilter()
     */
    public function testCheckFilterTrue2() : void
    {
        $request = ['var' => '30030'];
        
        $this->assertTrue(
            checkFilter($request)
        );
    }
    
    /**
     * @group tools
     * covers checkFilter()
     */
    public function testCheckFilterTrue3() : void
    {
        $request = ['full' => '1'];
        
        $this->assertTrue(
            checkFilter($request)
        );
    }
    
    /**
     * @group tools
     * covers checkFilter()
     */
    public function testCheckFilterNotTrue() : void
    {
        $request = ['full' => '0'];
        
        $this->assertNotTrue(
            checkFilter($request)
        );
    }

    /**
     * @group tools
     * covers checkFilter()
     */
    public function testCheckFilterException() : void
    {
        $request = ['full' => 'true'];
        
        $this->expectException(\Exception::class);
        
        checkFilter($request);
    }
    
    /**
     * @group tools
     * covers checkVariable()
     */
    public function testCheckVariableException() : void
    {
        $request = null;
        
        $this->expectException(\Exception::class);
        
        checkVariable($request);
    }
    
    /**
     * @group tools
     * covers checkVariable()
     */
    public function testCheckVariableException2() : void
    {
        $request = [];
        
        $this->expectException(\Exception::class);
        
        checkVariable($request);
    }
    
    /**
     * @group tools
     * covers checkVariable()
     */
    public function testCheckVariableException3() : void
    {
        $request = ['full' => '0'];
        
        $this->expectException(\Exception::class);
        
        checkVariable($request);
    }
    
    /**
     * @group tools
     * covers checkVariable()
     */
    public function testCheckVariableException4() : void
    {
        $input = ['var', 'variable', 'variabile'];
        
        $rand_keys = array_rand($input);
        
        $request = [$input[$rand_keys] => ''];
        
        $this->expectException(\Exception::class);
        
        checkVariable($request);
    }
    
    /**
     * @group tools
     * covers checkVariable()
     */
    public function testCheckVariableException5() : void
    {
        $keys = ['var', 'variable', 'variabile'];
        
        $rand_keys = array_rand($keys);
        
        $valueLow = strval(rand(0, 29999));
        
        $valueHigh = strval(rand(40000, 999999));
        
        $values = [$valueLow, $valueHigh];
        
        $rand_values = array_rand($values);
        
        $request = [$keys[$rand_keys] => $values[$rand_values]];
        
        $this->expectException(\Exception::class);
        
        checkVariable($request);
    }
    
    /**
     * @group tools
     * covers checkVariable()
     */
    public function testCheckVariableIsString() : void
    {
        $input = ['var', 'variable', 'variabile'];
        
        $rand_keys = array_rand($input);
        
        $value = strval(rand(30000, 39999));
                
        $request = [$input[$rand_keys] => $value];
        
        $this->assertIsString(
            checkVariable($request)
        );
    }
    
    /**
     * @group tools
     * covers checkVariable()
     */
    public function testCheckVariableEquals() : void
    {
        $request = ['var' => '30030'];
        
        $this->assertEquals(
            '30030',
            checkVariable($request)
        );
    }
    
    /**
     * @group tools
     * covers formatDate()
     */
    public function testFormatDateException() : void
    {
        $date = '32/12/2020';
        
        $this->expectException(\Exception::class);
        
        formatDate($date);
    }
    
    /**
     * @group tools
     * covers formatDate()
     */
    public function testFormatDateException1() : void
    {
        $date = '31-12-2020';
        
        $this->expectException(\Throwable::class);
        
        formatDate($date);
    }
    
    /**
     * @group tools
     * covers formatDate()
     */
    public function testFormatDateException2() : void
    {
        $date = '2020/31/12';
        
        $this->expectException(\Throwable::class);
        
        formatDate($date);
    }
    
    /**
     * @group tools
     * covers formatDate()
     */
    public function testFormatDateException3() : void
    {
        $date = '31 dicembre 2020';
        
        $this->expectException(\Throwable::class);
        
        formatDate($date);
    }
    
    /**
     * @group tools
     * covers formatDate()
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
     * covers formatDate()
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
     * covers formatDateTime()
     */
    public function testFormatDateTimeException() : void
    {
        $dateTime = '32/12/2020 21:30:00';
        
        $this->expectException(\Exception::class);
        
        formatDateTime($dateTime);
    }
    
    /**
     * @group tools
     * covers formatDateTime()
     */
    public function testFormatDateTimeException1() : void
    {
        $dateTime = '31/12/2020 21:63:00';
        
        $this->expectException(\Exception::class);
        
        formatDateTime($dateTime);
    }
    
    /**
     * @group tools
     * covers formatDateTime()
     */
    public function testFormatDateTimeException2() : void
    {
        $dateTime = '31/12/2020T21:30:00';
        
        $this->expectException(\Exception::class);
        
        formatDateTime($dateTime);
    }
    
    /**
     * @group tools
     * covers formatDateTime()
     */
    public function testFormatDateTimeException3() : void
    {
        $dateTime = '2020-31-12T21:30:00';
        
        $this->expectException(\Exception::class);
        
        formatDateTime($dateTime);
    }
    
    /**
     * @group tools
     * covers formatDateTime()
     */
    public function testFormatDateTimeException4() : void
    {
        $dateTime = '31/12/2020 21:30';
        
        $this->expectException(\Exception::class);
        
        formatDateTime($dateTime);
    }
    
    /**
     * @group tools
     * covers formatDateTime()
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
     * covers formatDateTime()
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
     * covers checkInterval()
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
     * covers checkInterval()
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
     * covers checkInterval()
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
     * covers checkInterval()
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
     * covers checkInterval()
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
     * covers checkInterval()
     */
    public function testCheckIntervalException() : void
    {
        $input = [];
        
        $this->expectException(\Exception::class);
        
        checkInterval($input);
    }
    
    
    /**
     * @group tools
     * covers setDateTimes()
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
     * covers setDatesTimes()
     */
    public function testSetDatesTimesEquals() : void
    {
        $input = ['datefrom' => '01/01/2020 21:30:00', 'dateto' => '01/04/2020 21:30:00'];
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
     * covers checkRequest()
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
     * covers checkRequest()
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
     * covers checkRequest()
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
     * covers checkRequest()
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
     * covers checkRequest()
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
     * covers checkField()
     * @dataProvider fieldsProvider
     */
    public function testCheckFieldEquals(?array $data, string $expected) : void
    {
        $actual = checkField($data);
                
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group tools
     * covers checkField()
     */
    public function testCheckFieldException() : void
    {
        $data = ['field' => 'pippo'];
        
        $this->expectException(\Exception::class);
        
        checkField($data);
    }
        
    /**
     * @group tools
     * covers connect()
     * @dataProvider dbNameProvider
     */
    public function testConnectIsResource(string $data) : void
    {
        $actual = connect($data);
                
        $this->assertIsResource($actual);
    }
    
    /**
     * @group tools
     * covers connect()
     */
    public function testConnectIsResource1() //: resource
    {
        $actual = connect('dbcore');
                
        $this->assertIsResource($actual);

        return $actual;
    }
    
    /**
     * @group tools
     * covers connect()
     */
    public function testConnectException() : void
    {
        $dbName = 'pippo';
        
        $this->expectException(\Exception::class);
        
        connect($dbName);
    }
    
    /**
     * @group tools
     * covers query()
     * @depends testConnectIsResource1
     */
    public function testQueryIsResource($conn) //: resource
    {
        $fileName = 'query_scarichi';
        $paramValues = ['30030'];
        
        $actual = query($conn, $fileName, $paramValues);
                
        $this->assertIsResource($actual);
        
        return $actual;
    }
    
    /**
     * @group tools
     * covers query()
     * @depends testConnectIsResource1
     */
    public function testQueryIsResource1($conn)  //: resource
    {
        $fileName = 'query_formule';
        $paramValues = ['1'];
        
        $actual = query($conn, $fileName, $paramValues);
                
        $this->assertIsResource($actual);
        
        return $actual;
    }

    /**
     * @group tools
     * covers query()
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
     * covers query()
     * @depends testConnectIsResource1
     */
    public function testQueryException($conn) : void
    {
        $fileName = 'query_scarichi';
        $paramValues = [''];
        
        $this->expectException(\Exception::class);
        
        query($conn, $fileName, $paramValues);
    }
    
    /**
     * @group tools
     * covers fetch()
     * @depends testQueryIsResource
     */
    public function testFetchEquals($stmt) : void
    {
        $expected = [
            0 => [
                'scarico' => 1,
                'denominazione' => 'Diga Flumineddu - Sfioratore di superficie',
                'variabile' => 30030,
                'tipo' => 'sfioratore di superficie'
            ]
        ];
        
        $actual = fetch($stmt);
                
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group tools
     * covers fetch()
     * @depends testQueryIsResource1
     */
    public function testFetchEquals1($stmt) : void
    {
        $expected = [
            0 => [
                'tipo_formula' => 'portata sfiorante',
                'scarico' => 1,
                'mi' => 0.47,
                'scabrosita' => null,
                'lunghezza' => null,
                'larghezza' => 40.5,
                'altezza' => null,
                'angolo' => null,
                'raggio' => null,
                'quota' => 276.5,
                'velocita' => null,
                'limite' => 942.67
            ]
        ];
        $actual = fetch($stmt);
                
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group tools
     * covers fetch()
     */
    public function testFetchError() : void
    {
        $stmt = false;
        
        $this->expectError();
        
        fetch($stmt);
    }
    
    /**
     * @group tools
     * covers changeTimeZone()
     * @dataProvider utcProvider
     */
    public function testChangeTimeZoneEquals(string $dateIn, bool $isLocalToUTC, bool $format, bool $set, string $expected) : void
    {
        $actual = changeTimeZone($dateIn, $isLocalToUTC, $format, $set);
        
        $this->assertEquals($expected, $actual);
    }
    
    
    
    /**
     * @group tools
     * covers changeTimeZone()
     * @dataProvider changeTimeZoneExceptionProvider
     */
    public function testChangeTimeZoneException(string $dateIn) : void
    {
        $isLocalToUTC = true;
        $format = true;
        $set = true;
        
        $this->expectException(\Exception::class);
        
        changeTimeZone($dateIn, $isLocalToUTC, $format, $set);
    }
    
    /**
     * @group tools
     * covers checkDates()
     * @dataProvider checkDatesProvider
     */
    public function testCheckDatesEqualsWithDelta(string $db, array $params, bool $isLocalToUTC, array $expecteds) : void
    {
        $actuals = checkDates($db, $params, $isLocalToUTC);
        
        foreach ($expecteds as $key => $value) {
            if (is_a($value, 'DateTime') && is_a($actuals[$key], 'DateTime')) {
                $expected = $value->getTimestamp();
                $actual = $actuals[$key]->getTimestamp();
                $this->assertEqualsWithDelta($expected, $actual, 7200);
            } else {
                $expected = $value;
                $actual = $actuals[$key];
                $this->assertEquals($expected, $actual);
            }
        }
        
        foreach ($actuals as $key => $value) {
            if (is_a($value, 'DateTime') && is_a($expecteds[$key], 'DateTime')) {
                $expected = $expecteds[$key]->getTimestamp();
                $actual = $value->getTimestamp();
                $this->assertEqualsWithDelta($expected, $actual, 7200);
            } else {
                $expected = $expecteds[$key];
                $actual = $value;
                $this->assertEquals($expected, $actual);
            }
        }
    }
    
    /**
     * @group tools
     * covers checkDates()
     */
    public function testCheckDatesEmpty() : void
    {
        $db = 'SPT';
        $dates = [];
        $isLocalToUTC = true;
        
        $actual = checkDates($db, $dates, $isLocalToUTC);
        
        $this->assertEmpty($actual);
    }
    
    /**
     * @group tools
     * covers datesToString()
     */
    public function testDatesToStringIsString() : void
    {
        $dates = [
            'data' => new \DateTime()
        ];
        
        $format = 'Y-m-d H:i:s';
        
        $actual = datesToString($dates, $format);
        
        $this->assertIsString($actual['data']);
    }
    
    /**
     * @group tools
     * covers datesToString()
     */
    public function testDatesToStringIsNotString() : void
    {
        $dates = [
            'notData' => 123456
        ];
        
        $format = 'Y-m-d H:i:s';
        
        $actual = datesToString($dates, $format);
        
        $this->assertIsNotString($actual['notData']);
    }
    
    /**
     * @group tools
     * covers datesToString()
     */
    public function testDatesToStringEquals() : void
    {
        $dates = [
            'data' => new \DateTime('2020-10-01 23:45:56')
        ];
        
        $format = 'd/m/Y H:i:s';
        
        $expected = [
            'data' => '01/10/2020 23:45:56'
        ];
        
        $actual = datesToString($dates, $format);
        
        $this->assertEquals($expected['data'], $actual['data']);
    }
    
    /**
     * @group tools
     * covers datesToString()
     */
    public function testDatesToStringException() : void
    {
        $dates = [
            'data' => new \DateTime('2020-10-01 23:45:56')
        ];
        
        $format = 'pippo';
        
        $this->expectException(\Exception::class);
                
        datesToString($dates, $format);
    }
    
    /**
     * @group tools
     * covers setToLocal()
     * @dataProvider setToLocalProvider
     */
    public function testSetToLocalEqualsWithDelta(array $dati, string $db, array $expecteds) : void
    {
        $actuals = setToLocal($db, $dati);
        
        foreach ($expecteds as $row => $fields) {
            foreach ($fields as $key => $value) {
                if (is_a($value, 'DateTime') && is_a($actuals[$row][$key], 'DateTime')) {
                    $expected = $value->getTimestamp();
                    $actual = $actuals[$row][$key]->getTimestamp();
                    $this->assertEqualsWithDelta($expected, $actual, 3600);
                } else {
                    $expected = $value;
                    $actual = $actuals[$row][$key];
                    $this->assertEquals($expected, $actual);
                }
            }
        }
        
        foreach ($actuals as $row => $fields) {
            foreach ($fields as $key => $value) {
                if (is_a($value, 'DateTime') && is_a($expecteds[$row][$key], 'DateTime')) {
                    $expected = $expecteds[$row][$key]->getTimestamp();
                    $actual = $value->getTimestamp();
                    $this->assertEqualsWithDelta($expected, $actual, 3600);
                } else {
                    $expected = $expecteds[$row][$key];
                    $actual = $value;
                    $this->assertEquals($expected, $actual);
                }
            }
        }
    }
    
    /**
     * @group tools
     * covers setToLocal()
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
     * covers getDataFromDb()
     * @dataProvider getDataFromDbProvider
     */
    public function testGetDataFromDbEquals(string $db, string $queryFileName, array $parametri, array $expecteds) : void
    {
        $actuals = getDataFromDb($db, $queryFileName, $parametri);

        foreach ($expecteds as $row => $fields) {
            foreach ($fields as $key => $value) {
                if (is_a($value, 'DateTime') && is_a($actuals[$row][$key], 'DateTime')) {
                    $expected = $value->getTimestamp();
                    $actual = $actuals[$row][$key]->getTimestamp();
                    $this->assertEqualsWithDelta($expected, $actual, 3600);
                } else {
                    $expected = $value;
                    $actual = $actuals[$row][$key];
                    $this->assertEquals($expected, $actual);
                }
            }
        }
        
        foreach ($actuals as $row => $fields) {
            foreach ($fields as $key => $value) {
                if (is_a($value, 'DateTime') && is_a($expecteds[$row][$key], 'DateTime')) {
                    $expected = $expecteds[$row][$key]->getTimestamp();
                    $actual = $value->getTimestamp();
                    $this->assertEqualsWithDelta($expected, $actual, 3600);
                } else {
                    $expected = $expecteds[$row][$key];
                    $actual = $value;
                    $this->assertEquals($expected, $actual);
                }
            }
        }
    }
    
    /**
     * @group index
     * covers getDataFromDb()
     */
    public function testGetDataFromDbExceptions()
    {
        $db = 'dbcore';
        $queryFileName = 'query_scarichi';
        $parametri = [
            'variabile' => null
        ];
                
        $this->expectException(\Exception::class);
        
        getDataFromDb($db, $queryFileName, $parametri);
    }

    /**
     * @group index
     * covers getDataFromDb()
     */
    public function testGetDataFromDbEmpty()
    {
        $db = 'dbcore';
        $queryFileName = 'query_formule';
        $parametri = [
            'scarico' => '999999'
        ];
                
        $actual = getDataFromDb($db, $queryFileName, $parametri);
        
        $this->assertEmpty($actual);
    }
    
    /**
     * @group depends
     * covers initVolumi()
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
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'tipo_dato' => 2
            ],
            '1' => [
                'variabile' => 165071,
                'valore' => 266.140,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'tipo_dato' => 2
            ]
        ];
        
        $expected = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
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
     * @group ex
     * covers initVolumi()
     */
    public function testInitVolumiException() : array
    {
        $variabili = [
            'id_variabile' => 30030,
            'impianto' => 75,
            'unita_misura' => 'mc'
        ];
        
        $dati = [];
                
        $this->expectException(\Exception::class);
        
        initVolumi($variabili, $dati);
    }
    
    /**
     * @group depends
     * covers addCategoria()
     * @depends testInitVolumiEquals
     */
    public function testAddCategoriaEquals(array $volumi) : array
    {
        $dati = [
            'livello' => [
                '0' => [
                    'variabile' => 165071,
                    'valore' => 266.206,
                    'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                    'unita_misura' => 'mslm',
                    'tipo_dato' => 2
                ],
                '1' => [
                    'variabile' => 165071,
                    'valore' => 266.140,
                    'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                    'unita_misura' => 'mslm',
                    'tipo_dato' => 2
                ]
            ]
        ];
        
        $categoria = 'livello';
        
        $expected = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'tipo_dato' => 1
            ]
        ];
        
        $actual = addCategoria($volumi, $dati, $categoria);
        
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
     * covers addCategoria()
     * @depends testInitVolumiEquals
     */
    public function testAddCategoriaException(array $volumi) : array
    {
        $dati = [
            'livello' => [
                '0' => [
                    'variabile' => 165071,
                    'valore' => 266.206,
                    'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                    'unita_misura' => 'mslm',
                    'tipo_dato' => 2
                ],
                '1' => [
                    'variabile' => 165071,
                    'valore' => 266.140,
                    'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                    'unita_misura' => 'mslm',
                    'tipo_dato' => 2
                ],
                '2' => [
                    'variabile' => 165071,
                    'valore' => 266.127,
                    'data_e_ora' => new \DateTime('2018-01-02 00:30:00'),
                    'unita_misura' => 'mslm',
                    'tipo_dato' => 2
                ]
            ]
        ];
        
        $categoria = 'livello';
        
        $this->expectException(\Exception::class);
        
        $actual = addCategoria($volumi, $dati, $categoria);
    }
    
    /**
     * @group depends
     * covers addCategoria()
     * @depends testInitVolumiEquals
     */
    public function testAddCategoriaException1(array $volumi) : void
    {
        $dati = [
            'livello' => [
                '0' => [
                    'variabile' => 165071,
                    'valore' => 266.206,
                    'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                    'unita_misura' => 'mslm',
                    'tipo_dato' => 2
                ],
                '1' => [
                    'variabile' => 165071,
                    'valore' => 266.140,
                    'data_e_ora' => new \DateTime('2018-01-02 00:30:00'),
                    'unita_misura' => 'mslm',
                    'tipo_dato' => 2
                ]
            ]
        ];
        
        $categoria = 'livello';
        
        $this->expectException(\Exception::class);
        
        $actual = addCategoria($volumi, $dati, $categoria);
    }
    
    /**
     * @group depends
     * covers addMedia()
     * @depends testAddCategoriaEquals
     */
    public function testAddMediaEquals(array $volumi) : array
    {
        $campo = 'livello';
        
        $expected = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 266.206,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media livello' => 266.173,
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
     * covers addMedia()
     * @depends testAddCategoriaEquals
     */
    public function testAddMediaException(array $volumi) : void
    {
        $campo = 'pippo';
        
        $this->expectException(\Exception::class);
        
        $actual = addMedia($volumi, $campo);
    }
    
    /**
     * @group depends
     * covers addAltezza()
     * @depends testAddMediaEquals
     */
    public function testAddAltezzaEquals(array $volumi) : array
    {
        $specifiche = [
            'tipo_formula' => 'portata sfiorante',
            'scarico' => 1,
            'mi' => 0.47,
            'larghezza' => 40.5,
            'quota' => 266.180,
            'limite' => 942.67
        ];
        
        $expected = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 266.206,
                'altezza' => 0.026,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media livello' => 266.173,
                'altezza' => -0.007,
                'tipo_dato' => 1
            ]
        ];
        
        $actual = addAltezza($volumi, $specifiche);
        
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
     * covers addAltezza()
     */
    public function testAddAltezzaEquals1() : array
    {
        $volumi = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 265,
                'livello valle' => 266.206,
                'media livello valle' => 261,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media livello' => 265,
                'livello valle' => 266.206,
                'media livello valle' => 258,
                'tipo_dato' => 1
            ]
        ];
        
        $specifiche = [
            'tipo_formula' => 'portata galleria',
            'scarico' => 1,
            'mi' => 0.47,
            'larghezza' => 40.5,
            'quota' => 260,
            'limite' => 942.67
        ];
        
        $expected = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 265,
                'livello valle' => 266.206,
                'media livello valle' => 261,
                'altezza' => 4,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media livello' => 265,
                'livello valle' => 266.206,
                'media livello valle' => 258,
                'altezza' => 5,
                'tipo_dato' => 1
            ]
        ];
        
        $actual = addAltezza($volumi, $specifiche);
        
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
     * covers addAltezza()
     * @depends testAddMediaEquals
     */
    public function testAddAltezzaException(array $volumi) : array
    {
        $specifiche = [];
        
        $this->expectException(\Exception::class);
        
        addAltezza($volumi, $specifiche);
    }
    
    /**
     * @group depends
     * covers addPortata()
     * @depends testAddAltezzaEquals
     */
    public function testAddPortataEqualsWithDelta(array $volumi) : array
    {
        $specifiche = [
            'tipo_formula' => 'portata sfiorante',
            'scarico' => 1,
            'mi' => 0.47,
            'larghezza' => 40.5,
            'quota' => 276.5,
            'limite' => 942.67
        ];
        
        $expected = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 266.206,
                'altezza' => 0.026,
                'portata' => 0.353,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media livello' => 266.173,
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
     * covers addPortata().
     * @group depends
     * @depends testAddAltezzaEquals
     */
    public function testAddPortataEqualsWithDelta1(array $volumi) : void
    {
        $specifiche = [
            'tipo_formula' => 'portata sfiorante',
            'scarico' => 1,
            'mi' => 0.47,
            'larghezza' => 40.5,
            'quota' => 276.5,
            'limite' => 0.35
        ];
        
        $expected = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 266.206,
                'altezza' => 0.026,
                'portata' => 0,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media livello' => 266.173,
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
     * covers addPortata().
     * @group depends
     */
    public function testAddPortataEqualsWithDelta2() : void
    {
        $volumi = [
            '0' => [
                'variabile' => 30050,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 266.206,
                'altezza' => 0.026,
                'tipo_dato' => 1,
                'manovra' => 1
            ],
            '1' => [
                'variabile' => 30050,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media livello' => 266.173,
                'altezza' => -0.007,
                'tipo_dato' => 1,
                'manovra' => 1
            ]
        ];
        
        $specifiche = [
            'tipo_formula' => 'portata scarico a sezione rettangolare con velocita e apertura percentuale',
            'scarico' => 22,
            'mi' => 0.47,
            'larghezza' => 158.61,
            'quota' => 16.05,
            'limite' => 2000,
            'velocita' => 0.8
        ];
        
        $expected = [
            '0' => [
                'variabile' => 30050,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'manovra' => 1,
                'livello' => 266.206,
                'media livello' => 266.206,
                'altezza' => 0.026,
                'portata' => 2.741,
                'tipo_dato' => 1
                
            ],
            '1' => [
                'variabile' => 30050,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'manovra' => 1,
                'livello' => 266.140,
                'media livello' => 266.173,
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
     * covers addPortata().
     * @group depends
     */
    public function testAddPortataEqualsWithDelta3() : void
    {
        $volumi = [
            '0' => [
                'variabile' => 30051,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 266.206,
                'altezza' => 0.026,
                'tipo_dato' => 1,
                'manovra' => 0.4
            ],
            '1' => [
                'variabile' => 30051,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media livello' => 266.173,
                'altezza' => -0.007,
                'tipo_dato' => 1,
                'manovra' => 0.4
            ]
        ];
        
        $specifiche = [
            'tipo_formula' => 'portata scarico a sezione rettangolare ad apertura lineare',
            'scarico' => 23,
            'mi' => 0.85,
            'larghezza' => 2.8,
            'quota' => 213,
            'limite' => 2000
        ];
        
        $expected = [
            '0' => [
                'variabile' => 30051,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'manovra' => 0.4,
                'livello' => 266.206,
                'media livello' => 266.206,
                'altezza' => 0.026,
                'portata' => 0.679,
                'tipo_dato' => 1
                
            ],
            '1' => [
                'variabile' => 30051,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'manovra' => 0.4,
                'livello' => 266.140,
                'media livello' => 266.173,
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
     * covers addPortata()
     * @depends testAddAltezzaEquals
     */
    public function testAddPortataException(array $volumi) : array
    {
        $specifiche = [];
        
        $this->expectException(\Exception::class);
                
        addPortata($volumi, $specifiche);
    }
    
    /**
     * @group depends
     * covers addDelta()
     * @depends testAddPortataEqualsWithDelta
     */
    public function testAddDeltaEqualsWithDelta(array $volumi) : array
    {
        $campo = 'data_e_ora';
        
        $expected = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 266.206,
                'altezza' => 0.026,
                'portata' => 0.353,
                'delta' => 0,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media livello' => 266.173,
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
     * covers addDelta()
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
     * covers addDelta()
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
     * covers addVolume()
     * @depends testAddDeltaEqualsWithDelta
     */
    public function testAddVolumeEqualsWithDelta(array $volumi) : array
    {
        $expected = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 266.206,
                'altezza' => 0.026,
                'portata' => 0.353,
                'delta' => 0,
                'volume' => 0,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media livello' => 266.173,
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
     * covers addVolume()
     */
    public function testAddVolumeException() : void
    {
        $data = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 266.206,
                'altezza' => 0.026,
                'tipo_dato' => 1
            ]
        ];
        
        $this->expectException(\Exception::class);
        
        addVolume($data);
    }
    
    /**
     * @group depends
     * covers addVolume()
     */
    public function testAddVolumeException1() : void
    {
        $data = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 266.206,
                'altezza' => 0.026,
                'delta' => 900,
                'tipo_dato' => 1
            ]
        ];
        
        $this->expectException(\Exception::class);
        
        addVolume($data);
    }
    
    /**
     * @group depends
     * covers addVolume()
     */
    public function testAddVolumeException2() : void
    {
        $data = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 266.206,
                'altezza' => 0.026,
                'portata' => 12.98,
                'tipo_dato' => 1
            ]
        ];
        
        $this->expectException(\Exception::class);
        
        addVolume($data);
    }
    
    /**
     * @group depends
     * covers format()
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
     * covers format()
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
     * covers format()
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
     * covers format()
     */
    public function testFormatEquals3() : array
    {
        $campo = 'dummy';
        
        $volumi = [
            '0' => [
                'variabile' => 30030,
                'dummy' => 'dummy',
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media' => 266.206,
                'altezza' => 0.026,
                'portata' => 0.353,
                'delta' => 0,
                'volume' => 0,
                'tipo_dato' => 1
            ]
        ];
        
        $expected = [
            '0' => [
                'variabile' => '30030',
                'valore' => 'dummy',
                'data_e_ora' => '02/01/2018 00:00:00',
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
     * covers format()
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
     * covers filter()
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
     * covers filter()
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
     * covers filter()
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
     * covers filter()
     */
    public function testFilterException() : void
    {
        $full = false;
        $volumi = [
            '0' => [
                'variabile' => '30030',
                'dummy' => '900',
                'data_e_ora' => '02/01/2018 00:15:00',
                'tipo_dato' => '1'
            ]
        ];
        
        $this->expectException(\Exception::class);
        
        filter($volumi, $full);
    }
    
    /**
     * @group depends
     * covers setFile()
     * @dataProvider setFileProvider
     */
    public function testSetFileEquals(string $variabile, array $dates, bool $filtered, string $field, string $path, string $expected) : void
    {
        $actual = setFile($variabile, $dates, $filtered, $field, $path);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group depends
     * covers setFile()
     */
    public function testSetFileException() : void
    {
        $variabile = '30030';
        $dates = [
            'datefrom' => new \DateTime('2018-01-02 00:15:00'),
            'dateto' => new \DateTime('2020-04-01 23:59:59')
        ];
        $filtered = false;
        $field = 'volume';
        $path = 'pippo';
        
        $this->expectException(\Exception::class);
        
        setFile($variabile, $dates, $filtered, $field, $path);
    }
    
    /**
     * @group csv
     * covers printToCSV()
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
     * covers printToCSV()
     */
    public function testPrintToCsvException() : void
    {
        $fileName = __DIR__ . '/../pippo/providers/test.csv';
        
        $dati = [
            '0' => [
                'variabile' => '30030',
                'data_e_ora' => '25/03/2018 20:30:00',
                'tipo_dato' => '1',
                'valore' => '2221,930'
            ]
        ];
        
        $this->expectException(\Exception::class);
        
        printToCSV($dati, $fileName);
    }
    
    /**
     * @group csv
     * coversNothing
     */
    public function printToCsvProvider() : \vaniacarta74\scarichi\tests\classes\CsvFileIterator
    {
        $fileName = __DIR__ . '/../providers/test.csv';
        
        $iterator = new \vaniacarta74\scarichi\tests\classes\CsvFileIterator($fileName);
        
        return $iterator;
    }
    
    /**
     * @group csv
     * covers printToCSV()
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
     * covers printPart()
     */
    public function testPrintPartFileExists() : void
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
     * @group csv
     * covers printPart()
     */
    public function testPrintPartException() : void
    {
        $i = 7;
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
        
        $this->expectException(\Exception::class);
        
        printPart($dati, $i, $filtered, $field);
    }
    
    /**
     * @group depends
     * covers divideAndPrint()
     * @depends testFilterEquals
     */
    public function testDivideAndPrintFileExists(array $volumi) : void
    {
        $full = true;
        $field = 'delta';
        $limit = 1;
        
        $expecteds = [
            CSV . '/Delta_30030_201801020000_201801020000_full.csv',
            CSV . '/Delta_30030_201801020015_201801020015_full.csv',
        ];
        
        divideAndPrint($volumi, $full, $field, $limit);
        
        foreach ($expecteds as $row => $expected) {
            $this->assertFileExists($expected);
        }
    }
    
    /**
     * @group depends
     * covers divideAndPrint()
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
    
    /**
     * @group depends
     * covers divideAndPrint()
     */
    public function testDivideAndPrintFalse() : void
    {
        $full = true;
        $field = 'delta';
        $limit = 100;
        
        $dati = [];
        
        $actual = divideAndPrint($dati, $full, $field, $limit);
        
        $this->assertFalse($actual);
    }

    /**
     * @group depends
     * covers divideAndPrint()
     * @depends testFilterEquals
     */
    public function testDivideAndPrintTrue(array $dati) : void
    {
        $full = true;
        $field = 'delta';
        $limit = 100;
        
        $actual = divideAndPrint($dati, $full, $field, $limit);
        
        $this->assertTrue($actual);
    }

    
    /**
     * @group depends
     * covers divideAndPrint()
     */
    public function testDivideAndPrintException() : void
    {
        $full = true;
        $field = 'delta';
        $limit = 0;
        
        $dati = [
            '0' => [
                'variabile' => '30030',
                'data_e_ora' => '25/03/2018 20:30:00',
                'tipo_dato' => '1',
                'valore' => '2221,930'
            ]
        ];
        
        $this->expectException(\Exception::class);
        
        divideAndPrint($dati, $full, $field, $limit);
    }
    
    /**
     * @group tools
     * covers checkNull()
     */
    public function testCheckNullEquals() : void
    {
        $input = '30040';
        $expected = '30040';
        
        $actual = checkNull($input);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group tools
     * covers checkNull()
     */
    public function testCheckNullException() : void
    {
        $input = null;
        
        $this->expectException(\Exception::class);
        
        checkNull($input);
    }
    
    /**
     * @group index
     * covers response()
     * @dataProvider responseProvider
     */
    public function testResponseEquals(array $request, bool $printed, string $expected) : void
    {
        $actual = response($request, $printed);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group index
     * covers response()
     */
    public function testResponseException() : void
    {
        $printed = true;
        
        $request = [
            'var' => '30030',
            'datefrom' => new \DateTime('2017-01-01 00:00:00'),
            'dateto' => new \DateTime('2017-01-02 00:00:00'),
            'full' => '1'
        ];
        
        $this->expectException(\Exception::class);
        
        response($request, $printed);
    }
    
    /**
     * @group index
     * covers response()
     */
    public function testResponseException1() : void
    {
        $printed = true;
        
        $request = [
            'var' => '30030',
            'datefrom' => new \DateTime('2017-01-01 00:00:00'),
            'dateto' => new \DateTime('2017-01-02 00:00:00'),
            'full' => '1',
            'field' => 'volume',
            'pippo' => 'pippo'
        ];
        
        $this->expectException(\Exception::class);
        
        response($request, $printed);
    }
    
    /**
     * @group error
     * covers errorHandler()
     */
    
    public function testErrorHandlerIsString() : void
    {
        try {
            if (true) {
                throw new \Exception('Test if errorHandler() return a string');
            }
        } catch (\Exception $e) {
            $actual = errorHandler($e);

            $this->assertIsString($actual);
        }
    }
    
    /**
     * @group tools
     * covers close()
     */
    public function testCloseError() : void
    {
        $conn = false;
        
        $this->expectError(\Error::class);
        
        close($conn);
    }
    
    /**
     * @coversNothing
     */
    public function calcolaPortataProvider() : array
    {
        $dati = [
            'sfioro' => [
                'formule' => [
                    'tipo_formula' => 'portata sfiorante',
                    'mi' => 0.47,
                    'larghezza' => 0.387,
                    'limite' => 800
                ],
                'parametri' => [
                    'altezza' => 10
                ],
                'expected' => 25.478
            ],
            'superficie velocita' => [
                'formule' => [
                    'tipo_formula' => 'portata scarico a sezione rettangolare con velocita e apertura percentuale',
                    'mi' => 0.47,
                    'larghezza' => 158.61,
                    'limite' => 2379.27,
                    'velocita' => 0.8
                ],
                'parametri' => [
                    'altezza' => 3,
                    'manovra' => 1
                ],
                'expected' => 1741.8875
            ],
            'superficie velocita sotto soglia' => [
                'formule' => [
                    'tipo_formula' => 'portata scarico a sezione rettangolare con velocita e apertura percentuale',
                    'mi' => 0.47,
                    'larghezza' => 158.61,
                    'limite' => 2379.27,
                    'velocita' => 0.8
                ],
                'parametri' => [
                    'altezza' => -0.3,
                    'manovra' => 1
                ],
                'expected' => 0
            ],
            'mezzofondo lineare' => [
                'formule' => [
                    'tipo_formula' => 'portata scarico a sezione rettangolare ad apertura lineare',
                    'mi' => 0.85,
                    'larghezza' => 2.8,
                    'limite' => 189.34
                ],
                'parametri' => [
                    'altezza' => 1,
                    'manovra' => 0.40
                ],
                'expected' => 4.217
            ],
            'mezzofondo lineare sotto soglia' => [
                'formule' => [
                    'tipo_formula' => 'portata scarico a sezione rettangolare ad apertura lineare',
                    'mi' => 0.85,
                    'larghezza' => 2.8,
                    'limite' => 189.34
                ],
                'parametri' => [
                    'altezza' => -1,
                    'manovra' => 0.40
                ],
                'expected' => 0
            ],
            'by-pass' => [
                'formule' => [
                    'tipo_formula' => 'portata scarico a sezione circolare e apertura percentuale',
                    'mi' => 0.9,
                    'raggio' => 0.1,
                    'limite' => 0.75
                ],
                'parametri' => [
                    'altezza' => 10,
                    'manovra' => 0.50
                ],
                'expected' => 0.19792
            ],
            'by-pass sotto soglia' => [
                'formule' => [
                    'tipo_formula' => 'portata scarico a sezione circolare e apertura percentuale',
                    'mi' => 0.9,
                    'raggio' => 0.1,
                    'limite' => 0.75
                ],
                'parametri' => [
                    'altezza' => -10,
                    'manovra' => 0.50
                ],
                'expected' => 0
            ],
            'ventola' => [
                'formule' => [
                    'tipo_formula' => 'portata ventola',
                    'mi' => 0.48,
                    'larghezza' => 10,
                    'altezza' => 2,
                    'angolo' => 60,
                    'limite' => 139,217
                ],
                'parametri' => [
                    'altezza' => 1,
                    'manovra' => 45 / 180 * pi()
                ],
                'expected' => 79.1638
            ],
            'ventola sotto soglia' => [
                'formule' => [
                    'tipo_formula' => 'portata ventola',
                    'mi' => 0.48,
                    'larghezza' => 10,
                    'altezza' => 2,
                    'angolo' => 60,
                    'limite' => 139,217
                ],
                'parametri' => [
                    'altezza' => -1.5,
                    'manovra' => 45 / 180 * pi()
                ],
                'expected' => 0
            ],
            'saracinesca' => [
                'formule' => [
                    'tipo_formula' => 'portata saracinesca',
                    'mi' => 0.9,
                    'raggio' => 0.45,
                    'limite' => 15.26
                ],
                'parametri' => [
                    'altezza' => 30,
                    'manovra' => 0.6
                ],
                'expected' => 9.7883
            ],
            'saracinesca sotto soglia' => [
                'formule' => [
                    'tipo_formula' => 'portata saracinesca',
                    'mi' => 0.9,
                    'raggio' => 0.45,
                    'limite' => 15.26
                ],
                'parametri' => [
                    'altezza' => 0.4,
                    'manovra' => 0.9
                ],
                'expected' => 0
            ],
            'galleria' => [
                'formule' => [
                    'tipo_formula' => 'portata galleria',
                    'scabrosita' => 0.1,
                    'lunghezza' => 6890,
                    'angolo' => 301,
                    'raggio' => 1.25,
                    'quota' => 264.18,
                    'limite' => 266.247
                ],
                'parametri' => [
                    'livello' => 270,
                    'altezza' => 5,
                    'manovra' => 2
                ],
                'expected' => 6.713206
            ],
            'galleria sbocco libero' => [
                'formule' => [
                    'tipo_formula' => 'portata galleria',
                    'scabrosita' => 0.1,
                    'lunghezza' => 6890,
                    'angolo' => 301,
                    'raggio' => 1.25,
                    'quota' => 264.18,
                    'limite' => 266.247
                ],
                'parametri' => [
                    'livello' => 270,
                    'altezza' => 5.82,
                    'manovra' => 2
                ],
                'expected' => 7.2428
            ],
            'galleria moto a pelo libero' => [
                'formule' => [
                    'tipo_formula' => 'portata galleria',
                    'scabrosita' => 0.1,
                    'lunghezza' => 6890,
                    'angolo' => 301,
                    'raggio' => 1.25,
                    'quota' => 264.18,
                    'limite' => 266.247
                ],
                'parametri' => [
                    'livello' => 266,
                    'altezza' => 1.82,
                    'manovra' => 2
                ],
                'expected' => 0
            ]
        ];
        
        return $dati;
    }
    
    /**
     * covers calcolaPortata()
     * @group tools
     * @dataProvider calcolaPortataProvider
     */
    public function testCalcolaPortataEqualsWithDelta(array $formule, array $parametri, float $expected) : void
    {
        $actual = calcolaPortata($formule, $parametri);
        
        $this->assertEqualsWithDelta($expected, $actual, 0.001);
    }
    
    /**
     * @group tools
     * covers calcolaPortata()
     */
    public function testCalcolaPortataException() : void
    {
        $formule = [
            'tipo_formula' => '',
            'mi' => 0.47,
            'larghezza' => 0.387,
            'limite' => 800
        ];
        $parametri = ['altezza'];
        
        $this->expectException(\Exception::class);
        
        calcolaPortata($formule, $parametri);
    }
    
    /**
     * @group tools
     * covers integraDate()
     */
    public function testIntegraDateEquals() : void
    {
        $target = [
            '0' => [
                'variabile' => 1067,
                'valore' => 17.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                'tipo_dato' => 2
            ],
            '1' => [
                'variabile' => 1067,
                'valore' => 18.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                'tipo_dato' => 2
            ],
            '2' => [
                'variabile' => 1067,
                'valore' => 19.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 04:00:00'),
                'tipo_dato' => 2
            ]
        ];
        
        $checkers = [
            'livello valle' => [
                '0' => [
                    'variabile' => 42,
                    'valore' => 27.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                    'tipo_dato' => 2
                ],
                '1' => [
                    'variabile' => 42,
                    'valore' => 27.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                    'tipo_dato' => 2
                ],
                '2' => [
                    'variabile' => 42,
                    'valore' => 37.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                    'tipo_dato' => 2
                ],
                '3' => [
                    'variabile' => 42,
                    'valore' => 47.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                    'tipo_dato' => 2
                ]
            ],
            'manovra' => [
                '0' => [
                    'variabile' => 30033,
                    'valore' => 0,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                    'tipo_dato' => 1
                ],
                '1' => [
                    'variabile' => 30033,
                    'valore' => 0.5,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                    'tipo_dato' => 1
                ],
                '2' => [
                    'variabile' => 30033,
                    'valore' => 1,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                    'tipo_dato' => 1
                ],
                '3' => [
                    'variabile' => 30033,
                    'valore' => 0.5,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                    'tipo_dato' => 1
                ]
            ]
        ];
        
        $expected = [
            '0' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                'tipo_dato' => 2
            ],
            '1' => [
                'variabile' => 1067,
                'valore' => 17.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                'tipo_dato' => 2
            ],
            '2' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                'tipo_dato' => 2
            ],
            '3' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                'tipo_dato' => 2
            ],
            '4' => [
                'variabile' => 1067,
                'valore' => 18.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                'tipo_dato' => 2
            ],
            '5' => [
                'variabile' => 1067,
                'valore' => 19.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 04:00:00'),
                'tipo_dato' => 2
            ],
            '6' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                'tipo_dato' => 2
            ],
            '7' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                'tipo_dato' => 2
            ]
        ];
        
        $actual = integraDate($target, $checkers);
        
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
     * @group tools
     * covers integraDate()
     */
    public function testIntegraDateEquals1() : void
    {
        $target = [
            '0' => [
                'variabile' => 1067,
                'valore' => 17.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                'tipo_dato' => 2
            ]
        ];
        
        $checkers = [
            'livello valle' => [
                '0' => [
                    'variabile' => 42,
                    'valore' => 27.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                    'tipo_dato' => 2
                ],
                '1' => [
                    'variabile' => 42,
                    'valore' => 37.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                    'tipo_dato' => 2
                ],
                '2' => [
                    'variabile' => 42,
                    'valore' => 47.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                    'tipo_dato' => 2
                ]
            ],
            'manovra' => [
                '0' => [
                    'variabile' => 30033,
                    'valore' => 0,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                    'tipo_dato' => 1
                ],
                '1' => [
                    'variabile' => 30033,
                    'valore' => 0.5,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                    'tipo_dato' => 1
                ],
                '2' => [
                    'variabile' => 30033,
                    'valore' => 1,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                    'tipo_dato' => 1
                ],
                '3' => [
                    'variabile' => 30033,
                    'valore' => 0.5,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                    'tipo_dato' => 1
                ]
            ]
        ];
        
        $expected = [
            '0' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                'tipo_dato' => 2
            ],
            '1' => [
                'variabile' => 1067,
                'valore' => 17.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                'tipo_dato' => 2
            ],
            '2' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                'tipo_dato' => 2
            ],
            '3' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                'tipo_dato' => 2
            ],
            '4' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                'tipo_dato' => 2
            ],
            '5' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                'tipo_dato' => 2
            ],
            '6' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                'tipo_dato' => 2
            ]
        ];
        
        $actual = integraDate($target, $checkers);
        
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
     * @group tools
     * covers integraDate()
     */
    public function testIntegraDateException() : void
    {
        $target = [];
        
        $checkers = [
            'livello valle' => [
                '0' => [
                    'variabile' => 42,
                    'valore' => 27.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                    'tipo_dato' => 2
                ],
                '1' => [
                    'variabile' => 42,
                    'valore' => 37.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                    'tipo_dato' => 2
                ],
                '2' => [
                    'variabile' => 42,
                    'valore' => 47.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                    'tipo_dato' => 2
                ]
            ],
            'manovra' => [
                '0' => [
                    'variabile' => 30033,
                    'valore' => 0,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                    'tipo_dato' => 1
                ],
                '1' => [
                    'variabile' => 30033,
                    'valore' => 0.5,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                    'tipo_dato' => 1
                ],
                '2' => [
                    'variabile' => 30033,
                    'valore' => 1,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                    'tipo_dato' => 1
                ],
                '3' => [
                    'variabile' => 30033,
                    'valore' => 0.5,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                    'tipo_dato' => 1
                ]
            ]
        ];
        
        $this->expectException(\Exception::class);
        
        integraDate($target, $checkers);
    }
    
    /**
     * @group index
     * covers uniformaCategorie()
     */
    public function testUniformaCategorieEquals() : array
    {
        $dati_acquisiti = [
            'livello monte' => [
                '0' => [
                    'variabile' => 1067,
                    'valore' => 17.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                    'tipo_dato' => 2
                ],
                '1' => [
                    'variabile' => 1067,
                    'valore' => 18.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                    'tipo_dato' => 2
                ],
                '2' => [
                    'variabile' => 1067,
                    'valore' => 19.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 04:00:00'),
                    'tipo_dato' => 2
                ]
            ],
            'livello valle' => [
                '0' => [
                    'variabile' => 42,
                    'valore' => 17.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                    'tipo_dato' => 2
                ],
                '1' => [
                    'variabile' => 42,
                    'valore' => 27.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                    'tipo_dato' => 2
                ],
                '2' => [
                    'variabile' => 42,
                    'valore' => 37.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                    'tipo_dato' => 2
                ],
                '3' => [
                    'variabile' => 42,
                    'valore' => 47.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                    'tipo_dato' => 2
                ]
            ],
            'manovra' => [
                '0' => [
                    'variabile' => 30033,
                    'valore' => 0,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                    'tipo_dato' => 1
                ],
                '1' => [
                    'variabile' => 30033,
                    'valore' => 0.5,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                    'tipo_dato' => 1
                ],
                '2' => [
                    'variabile' => 30033,
                    'valore' => 1,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                    'tipo_dato' => 1
                ],
                '3' => [
                    'variabile' => 30033,
                    'valore' => 0.5,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                    'tipo_dato' => 1
                ]
            ]
        ];
        
        $expected = [
            'livello monte' => [
                '0' => [
                    'variabile' => 1067,
                    'valore' => null,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                    'tipo_dato' => 2
                ],
                '1' => [
                    'variabile' => 1067,
                    'valore' => 17.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                    'tipo_dato' => 2
                ],
                '2' => [
                    'variabile' => 1067,
                    'valore' => null,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                    'tipo_dato' => 2
                ],
                '3' => [
                    'variabile' => 1067,
                    'valore' => null,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                    'tipo_dato' => 2
                ],
                '4' => [
                    'variabile' => 1067,
                    'valore' => 18.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                    'tipo_dato' => 2
                ],
                '5' => [
                    'variabile' => 1067,
                    'valore' => 19.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 04:00:00'),
                    'tipo_dato' => 2
                ],
                '6' => [
                    'variabile' => 1067,
                    'valore' => null,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                    'tipo_dato' => 2
                ],
                '7' => [
                    'variabile' => 1067,
                    'valore' => null,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                    'tipo_dato' => 2
                ]
            ],
            'livello valle' => [
                '0' => [
                    'variabile' => 42,
                    'valore' => 17.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                    'tipo_dato' => 2
                ],
                '1' => [
                    'variabile' => 42,
                    'valore' => 27.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                    'tipo_dato' => 2
                ],
                '2' => [
                    'variabile' => 42,
                    'valore' => null,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                    'tipo_dato' => 2
                ],
                '3' => [
                    'variabile' => 42,
                    'valore' => null,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                    'tipo_dato' => 2
                ],
                '4' => [
                    'variabile' => 42,
                    'valore' => 37.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                    'tipo_dato' => 2
                ],
                '5' => [
                    'variabile' => 42,
                    'valore' => null,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 04:00:00'),
                    'tipo_dato' => 2
                ],
                '6' => [
                    'variabile' => 42,
                    'valore' => 47.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                    'tipo_dato' => 2
                ],
                '7' => [
                    'variabile' => 42,
                    'valore' => null,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                    'tipo_dato' => 2
                ]
            ],
            'manovra' => [
                '0' => [
                    'variabile' => 30033,
                    'valore' => null,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                    'tipo_dato' => 1
                ],
                '1' => [
                    'variabile' => 30033,
                    'valore' => null,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                    'tipo_dato' => 1
                ],
                '2' => [
                    'variabile' => 30033,
                    'valore' => 0,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                    'tipo_dato' => 1
                ],
                '3' => [
                    'variabile' => 30033,
                    'valore' => 0.5,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                    'tipo_dato' => 1
                ],
                '4' => [
                    'variabile' => 30033,
                    'valore' => null,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                    'tipo_dato' => 1
                ],
                '5' => [
                    'variabile' => 30033,
                    'valore' => null,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 04:00:00'),
                    'tipo_dato' => 1
                ],
                '6' => [
                    'variabile' => 30033,
                    'valore' => 1,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                    'tipo_dato' => 1
                ],
                '7' => [
                    'variabile' => 30033,
                    'valore' => 0.5,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                    'tipo_dato' => 1
                ]
            ]
        ];
        
        $actual = uniformaCategorie($dati_acquisiti);
        
        foreach ($actual as $categoria => $dati) {
            foreach ($dati as $row => $fields) {
                foreach ($fields as $key => $value) {
                    $this->assertEquals($expected[$categoria][$row][$key], $value);
                }
            }
        }
        
        foreach ($expected as $categoria => $dati) {
            foreach ($dati as $row => $fields) {
                foreach ($fields as $key => $value) {
                    $this->assertEquals($value, $actual[$categoria][$row][$key]);
                }
            }
        }
        return $actual;
    }
    
    /**
     * @group index
     * covers uniformaCategorie()
     */
    public function testUniformaCategorieEquals1() : void
    {
        $dati_acquisiti = [
            'livello' => [
                '0' => [
                    'variabile' => 1067,
                    'valore' => 17.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                    'tipo_dato' => 2
                ],
                '1' => [
                    'variabile' => 1067,
                    'valore' => 18.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                    'tipo_dato' => 2
                ],
                '2' => [
                    'variabile' => 1067,
                    'valore' => 19.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 04:00:00'),
                    'tipo_dato' => 2
                ]
            ]
        ];
        
        $expected = [
            'livello' => [
                '0' => [
                    'variabile' => 1067,
                    'valore' => 17.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                    'tipo_dato' => 2
                ],
                '1' => [
                    'variabile' => 1067,
                    'valore' => 18.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                    'tipo_dato' => 2
                ],
                '2' => [
                    'variabile' => 1067,
                    'valore' => 19.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 04:00:00'),
                    'tipo_dato' => 2
                ]
            ]
        ];
        
        $actual = uniformaCategorie($dati_acquisiti);
        
        foreach ($actual as $categoria => $dati) {
            foreach ($dati as $row => $fields) {
                foreach ($fields as $key => $value) {
                    $this->assertEquals($expected[$categoria][$row][$key], $value);
                }
            }
        }
        
        foreach ($expected as $categoria => $dati) {
            foreach ($dati as $row => $fields) {
                foreach ($fields as $key => $value) {
                    $this->assertEquals($value, $actual[$categoria][$row][$key]);
                }
            }
        }
    }
    
    /**
     * @group index
     * covers uniformaCategorie()
     */
    public function testUniformaCategorieEquals2() : array
    {
        $dati_acquisiti = [
            'livello' => [
                '0' => [
                    'variabile' => 1067,
                    'valore' => 35.97,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                    'tipo_dato' => 2
                ],
                '1' => [
                    'variabile' => 1067,
                    'valore' => 35.97,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 00:30:00'),
                    'tipo_dato' => 2
                ],
                '2' => [
                    'variabile' => 1067,
                    'valore' => 35.97,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 01:00:00'),
                    'tipo_dato' => 2
                ],
                '3' => [
                    'variabile' => 1067,
                    'valore' => 35.97,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                    'tipo_dato' => 2
                ],
                '4' => [
                    'variabile' => 1067,
                    'valore' => 35.97,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                    'tipo_dato' => 2
                ],
                '5' => [
                    'variabile' => 1067,
                    'valore' => 35.97,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:30:00'),
                    'tipo_dato' => 2
                ],
                '6' => [
                    'variabile' => 1067,
                    'valore' => 35.97,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 03:00:00'),
                    'tipo_dato' => 2
                ],
                '7' => [
                    'variabile' => 1067,
                    'valore' => 35.97,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 03:30:00'),
                    'tipo_dato' => 2
                ],
                '8' => [
                    'variabile' => 1067,
                    'valore' => 35.97,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 04:00:00'),
                    'tipo_dato' => 2
                ]
            ],
            'manovra' => [
                '0' => [
                    'variabile' => 30033,
                    'valore' => 0,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 0:59:00'),
                    'tipo_dato' => 1
                ],
                '1' => [
                    'variabile' => 30033,
                    'valore' => 0,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 01:11:00'),
                    'tipo_dato' => 1
                ],
                '2' => [
                    'variabile' => 30033,
                    'valore' => 0,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                    'tipo_dato' => 1
                ],
                '3' => [
                    'variabile' => 30033,
                    'valore' => 0,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:59:00'),
                    'tipo_dato' => 1
                ],
                '4' => [
                    'variabile' => 30033,
                    'valore' => 0,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 03:59:00'),
                    'tipo_dato' => 1
                ]
            ]
        ];
        
        $expected = [
            'livello' => [
                '0' => [
                    'variabile' => 1067,
                    'valore' => 35.97,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                    'tipo_dato' => 2
                ],
                '1' => [
                    'variabile' => 1067,
                    'valore' => 35.97,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 00:30:00'),
                    'tipo_dato' => 2
                ],
                '2' => [
                    'variabile' => 1067,
                    'valore' => null,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 0:59:00'),
                    'tipo_dato' => 2
                ],
                '3' => [
                    'variabile' => 1067,
                    'valore' => 35.97,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 01:00:00'),
                    'tipo_dato' => 2
                ],
                '4' => [
                    'variabile' => 1067,
                    'valore' => null,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 01:11:00'),
                    'tipo_dato' => 2
                ],
                '5' => [
                    'variabile' => 1067,
                    'valore' => 35.97,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                    'tipo_dato' => 2
                ],
                '6' => [
                    'variabile' => 1067,
                    'valore' => 35.97,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                    'tipo_dato' => 2
                ],
                '7' => [
                    'variabile' => 1067,
                    'valore' => 35.97,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:30:00'),
                    'tipo_dato' => 2
                ],
                '8' => [
                    'variabile' => 1067,
                    'valore' => null,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:59:00'),
                    'tipo_dato' => 2
                ],
                '9' => [
                    'variabile' => 1067,
                    'valore' => 35.97,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 03:00:00'),
                    'tipo_dato' => 2
                ],
                '10' => [
                    'variabile' => 1067,
                    'valore' => 35.97,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 03:30:00'),
                    'tipo_dato' => 2
                ],
                '11' => [
                    'variabile' => 1067,
                    'valore' => null,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 03:59:00'),
                    'tipo_dato' => 2
                ],
                '12' => [
                    'variabile' => 1067,
                    'valore' => 35.97,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 04:00:00'),
                    'tipo_dato' => 2
                ]
            ],
            'manovra' => [
                '0' => [
                    'variabile' => 30033,
                    'valore' => null,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                    'tipo_dato' => 1
                ],
                '1' => [
                    'variabile' => 30033,
                    'valore' => null,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 00:30:00'),
                    'tipo_dato' => 1
                ],
                '2' => [
                    'variabile' => 30033,
                    'valore' => 0,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 0:59:00'),
                    'tipo_dato' => 1
                ],
                '3' => [
                    'variabile' => 30033,
                    'valore' => null,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 01:00:00'),
                    'tipo_dato' => 1
                ],
                '4' => [
                    'variabile' => 30033,
                    'valore' => 0,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 01:11:00'),
                    'tipo_dato' => 1
                ],
                '5' => [
                    'variabile' => 30033,
                    'valore' => null,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                    'tipo_dato' => 1
                ],
                '6' => [
                    'variabile' => 30033,
                    'valore' => null,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                    'tipo_dato' => 1
                ],
                '7' => [
                    'variabile' => 30033,
                    'valore' => null,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:30:00'),
                    'tipo_dato' => 1
                ],
                '8' => [
                    'variabile' => 30033,
                    'valore' => 0,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:59:00'),
                    'tipo_dato' => 1
                ],
                '9' => [
                    'variabile' => 30033,
                    'valore' => null,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 03:00:00'),
                    'tipo_dato' => 1
                ],
                '10' => [
                    'variabile' => 30033,
                    'valore' => null,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 03:30:00'),
                    'tipo_dato' => 1
                ],
                '11' => [
                    'variabile' => 30033,
                    'valore' => 0,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 03:59:00'),
                    'tipo_dato' => 1
                ],
                '12' => [
                    'variabile' => 30033,
                    'valore' => null,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 04:00:00'),
                    'tipo_dato' => 1
                ]
            ],
        ];
        
        $actual = uniformaCategorie($dati_acquisiti);
        
        foreach ($actual as $categoria => $dati) {
            foreach ($dati as $row => $fields) {
                foreach ($fields as $key => $value) {
                    $this->assertEquals($expected[$categoria][$row][$key], $value);
                }
            }
        }
        
        foreach ($expected as $categoria => $dati) {
            foreach ($dati as $row => $fields) {
                foreach ($fields as $key => $value) {
                    $this->assertEquals($value, $actual[$categoria][$row][$key]);
                }
            }
        }
        return $actual;
    }
    
    /**
     * @group index
     * covers uniformaCategorie()
     */
    public function testUniformaCategorieException() : void
    {
        $dati_acquisiti = [];
        
        $this->expectException(\Exception::class);
        
        uniformaCategorie($dati_acquisiti);
    }
    
    /**
     * covers completaDati().
     * @group index
     * @depends testUniformaCategorieEquals
     */
    public function testCompletaDatiEquals(array $input) : void
    {
        $expected = [
            'livello monte' => [
                '0' => [
                    'variabile' => 1067,
                    'valore' => 17.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                    'tipo_dato' => 2
                ],
                '1' => [
                    'variabile' => 1067,
                    'valore' => 17.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                    'tipo_dato' => 2
                ],
                '2' => [
                    'variabile' => 1067,
                    'valore' => 17.55,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                    'tipo_dato' => 2
                ],
                '3' => [
                    'variabile' => 1067,
                    'valore' => 18.05,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                    'tipo_dato' => 2
                ],
                '4' => [
                    'variabile' => 1067,
                    'valore' => 18.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                    'tipo_dato' => 2
                ],
                '5' => [
                    'variabile' => 1067,
                    'valore' => 19.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 04:00:00'),
                    'tipo_dato' => 2
                ],
                '6' => [
                    'variabile' => 1067,
                    'valore' => 19.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                    'tipo_dato' => 2
                ],
                '7' => [
                    'variabile' => 1067,
                    'valore' => 19.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                    'tipo_dato' => 2
                ]
            ],
            'livello valle' => [
                '0' => [
                    'variabile' => 42,
                    'valore' => 17.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                    'tipo_dato' => 2
                ],
                '1' => [
                    'variabile' => 42,
                    'valore' => 27.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                    'tipo_dato' => 2
                ],
                '2' => [
                    'variabile' => 42,
                    'valore' => 29.8,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                    'tipo_dato' => 2
                ],
                '3' => [
                    'variabile' => 42,
                    'valore' => 34.8,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                    'tipo_dato' => 2
                ],
                '4' => [
                    'variabile' => 42,
                    'valore' => 37.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                    'tipo_dato' => 2
                ],
                '5' => [
                    'variabile' => 42,
                    'valore' => 43.967,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 04:00:00'),
                    'tipo_dato' => 2
                ],
                '6' => [
                    'variabile' => 42,
                    'valore' => 47.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                    'tipo_dato' => 2
                ],
                '7' => [
                    'variabile' => 42,
                    'valore' => 47.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                    'tipo_dato' => 2
                ]
            ],
            'manovra' => [
                '0' => [
                    'variabile' => 30033,
                    'valore' => 0,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                    'tipo_dato' => 1
                ],
                '1' => [
                    'variabile' => 30033,
                    'valore' => 0,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                    'tipo_dato' => 1
                ],
                '2' => [
                    'variabile' => 30033,
                    'valore' => 0,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                    'tipo_dato' => 1
                ],
                '3' => [
                    'variabile' => 30033,
                    'valore' => 0.5,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                    'tipo_dato' => 1
                ],
                '4' => [
                    'variabile' => 30033,
                    'valore' => 0.5,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                    'tipo_dato' => 1
                ],
                '5' => [
                    'variabile' => 30033,
                    'valore' => 0.5,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 04:00:00'),
                    'tipo_dato' => 1
                ],
                '6' => [
                    'variabile' => 30033,
                    'valore' => 1,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                    'tipo_dato' => 1
                ],
                '7' => [
                    'variabile' => 30033,
                    'valore' => 0.5,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                    'tipo_dato' => 1
                ]
            ]
        ];
        
        $actual = completaDati($input);
        
        foreach ($actual as $categoria => $dati) {
            foreach ($dati as $row => $fields) {
                foreach ($fields as $key => $value) {
                    $this->assertEqualsWithDelta($expected[$categoria][$row][$key], $value, 0.001);
                }
            }
        }
        
        foreach ($expected as $categoria => $dati) {
            foreach ($dati as $row => $fields) {
                foreach ($fields as $key => $value) {
                    $this->assertEqualsWithDelta($value, $actual[$categoria][$row][$key], 0.001);
                }
            }
        }
    }
    
    /**
     * covers completaDati()
     * @group index
     */
    public function testCompletaDatiException() : void
    {
        $dati_acquisiti = [];
        
        $this->expectException(\Exception::class);
        
        completaDati($dati_acquisiti);
    }
    
    /**
     * @coversNothing
     * @group tools
     */
    public function trovaCapiProvider() : array
    {
        $dati = [
            'standard' => [
                'input' => [
                    '0' => [
                        'variabile' => 1067,
                        'valore' => null,
                        'unita_misura' => 'mslm',
                        'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                        'tipo_dato' => 2
                    ],
                    '1' => [
                        'variabile' => 1067,
                        'valore' => 17.3,
                        'unita_misura' => 'mslm',
                        'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                        'tipo_dato' => 2
                    ],
                    '2' => [
                        'variabile' => 1067,
                        'valore' => null,
                        'unita_misura' => 'mslm',
                        'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                        'tipo_dato' => 2
                    ],
                    '3' => [
                        'variabile' => 1067,
                        'valore' => null,
                        'unita_misura' => 'mslm',
                        'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                        'tipo_dato' => 2
                    ],
                    '4' => [
                        'variabile' => 1067,
                        'valore' => 19.3,
                        'unita_misura' => 'mslm',
                        'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                        'tipo_dato' => 2
                    ],
                    '5' => [
                        'variabile' => 1067,
                        'valore' => null,
                        'unita_misura' => 'mslm',
                        'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                        'tipo_dato' => 2
                    ],
                    '6' => [
                        'variabile' => 1067,
                        'valore' => null,
                        'unita_misura' => 'mslm',
                        'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                        'tipo_dato' => 2
                    ]
                ],
                'expected' => [
                    'testa' => [
                        'id' => 1,
                        'valore' => 17.3
                    ],
                    'coda' => [
                        'id' => 4,
                        'valore' => 19.3
                    ]
                ]
            ],
            'full' => [
                'input' => [
                    '0' => [
                        'variabile' => 1067,
                        'valore' => 16.3,
                        'unita_misura' => 'mslm',
                        'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                        'tipo_dato' => 2
                    ],
                    '1' => [
                        'variabile' => 1067,
                        'valore' => 17.3,
                        'unita_misura' => 'mslm',
                        'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                        'tipo_dato' => 2
                    ],
                    '2' => [
                        'variabile' => 1067,
                        'valore' => null,
                        'unita_misura' => 'mslm',
                        'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                        'tipo_dato' => 2
                    ],
                    '3' => [
                        'variabile' => 1067,
                        'valore' => null,
                        'unita_misura' => 'mslm',
                        'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                        'tipo_dato' => 2
                    ],
                    '4' => [
                        'variabile' => 1067,
                        'valore' => 19.3,
                        'unita_misura' => 'mslm',
                        'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                        'tipo_dato' => 2
                    ],
                    '5' => [
                        'variabile' => 1067,
                        'valore' => null,
                        'unita_misura' => 'mslm',
                        'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                        'tipo_dato' => 2
                    ],
                    '6' => [
                        'variabile' => 1067,
                        'valore' => 21.3,
                        'unita_misura' => 'mslm',
                        'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                        'tipo_dato' => 2
                    ]
                ],
                'expected' => [
                    'testa' => [
                        'id' => 0,
                        'valore' => 16.3
                    ],
                    'coda' => [
                        'id' => 6,
                        'valore' => 21.3
                    ]
                ]
            ],
            'middle' => [
                'input' => [
                    '0' => [
                        'variabile' => 1067,
                        'valore' => null,
                        'unita_misura' => 'mslm',
                        'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                        'tipo_dato' => 2
                    ],
                    '1' => [
                        'variabile' => 1067,
                        'valore' => 17.3,
                        'unita_misura' => 'mslm',
                        'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                        'tipo_dato' => 2
                    ],
                    '2' => [
                        'variabile' => 1067,
                        'valore' => null,
                        'unita_misura' => 'mslm',
                        'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                        'tipo_dato' => 2
                    ]
                ],
                'expected' => [
                    'testa' => [
                        'id' => 1,
                        'valore' => 17.3
                    ],
                    'coda' => [
                        'id' => 1,
                        'valore' => 17.3
                    ]
                ]
            ],
            'top' => [
                'input' => [
                    '0' => [
                        'variabile' => 1067,
                        'valore' => 17.3,
                        'unita_misura' => 'mslm',
                        'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                        'tipo_dato' => 2
                    ],
                    '1' => [
                        'variabile' => 1067,
                        'valore' => null,
                        'unita_misura' => 'mslm',
                        'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                        'tipo_dato' => 2
                    ],
                    '2' => [
                        'variabile' => 1067,
                        'valore' => null,
                        'unita_misura' => 'mslm',
                        'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                        'tipo_dato' => 2
                    ]
                ],
                'expected' => [
                    'testa' => [
                        'id' => 0,
                        'valore' => 17.3
                    ],
                    'coda' => [
                        'id' => 0,
                        'valore' => 17.3
                    ]
                ]
            ],
            'bottom' => [
                'input' => [
                    '0' => [
                        'variabile' => 1067,
                        'valore' => null,
                        'unita_misura' => 'mslm',
                        'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                        'tipo_dato' => 2
                    ],
                    '1' => [
                        'variabile' => 1067,
                        'valore' => null,
                        'unita_misura' => 'mslm',
                        'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                        'tipo_dato' => 2
                    ],
                    '2' => [
                        'variabile' => 1067,
                        'valore' => 17.3,
                        'unita_misura' => 'mslm',
                        'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                        'tipo_dato' => 2
                    ]
                ],
                'expected' => [
                    'testa' => [
                        'id' => 2,
                        'valore' => 17.3
                    ],
                    'coda' => [
                        'id' => 2,
                        'valore' => 17.3
                    ]
                ]
            ]
        ];
        return $dati;
    }
    
    /**
     * covers trovaCapi()
     * @group tools
     * @dataProvider trovaCapiProvider
     */
    public function testTrovaCapiEquals(array $input, array $expected) : void
    {
        $actual = trovaCapi($input);
        
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
     * covers trovaCapi().
     * @group tools
     */
    public function testTrovaCapiException() : void
    {
        $input = [
            '0' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                'tipo_dato' => 2
            ],
            '1' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                'tipo_dato' => 2
            ],
            '2' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                'tipo_dato' => 2
            ]
        ];
        
        $this->expectException(\Exception::class);
        
        trovaCapi($input);
    }
    
    /**
     * covers riempiCode().
     * @group tools
     */
    public function testRiempiCodeEquals() : void
    {
        $dati = [
            '0' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                'tipo_dato' => 2
            ],
            '1' => [
                'variabile' => 1067,
                'valore' => 17.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                'tipo_dato' => 2
            ],
            '2' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                'tipo_dato' => 2
            ],
            '3' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                'tipo_dato' => 2
            ],
            '4' => [
                'variabile' => 1067,
                'valore' => 19.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                'tipo_dato' => 2
            ],
            '5' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                'tipo_dato' => 2
            ],
            '6' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                'tipo_dato' => 2
            ]
        ];
        
        $expected = [
            '0' => [
                'variabile' => 1067,
                'valore' => 17.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                'tipo_dato' => 2
            ],
            '1' => [
                'variabile' => 1067,
                'valore' => 17.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                'tipo_dato' => 2
            ],
            '2' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                'tipo_dato' => 2
            ],
            '3' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                'tipo_dato' => 2
            ],
            '4' => [
                'variabile' => 1067,
                'valore' => 19.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                'tipo_dato' => 2
            ],
            '5' => [
                'variabile' => 1067,
                'valore' => 19.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                'tipo_dato' => 2
            ],
            '6' => [
                'variabile' => 1067,
                'valore' => 19.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                'tipo_dato' => 2
            ]
        ];
        
        $actual = riempiCode($dati);
        
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
     * covers riempiCode().
     * @group tools
     */
    public function testRiempiCodeException() : void
    {
        $input = [
            '0' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                'tipo_dato' => 2
            ],
            '1' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                'tipo_dato' => 2
            ],
            '2' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                'tipo_dato' => 2
            ]
        ];
        
        $this->expectException(\Exception::class);
        
        riempiCode($input);
    }
    
    /**
     * covers riempiNull().
     * @group tools
     */
    public function testRiempiNullEquals() : void
    {
        $dati = [
            '0' => [
                'variabile' => 1067,
                'valore' => 17.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                'tipo_dato' => 2
            ],
            '1' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                'tipo_dato' => 2
            ],
            '2' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                'tipo_dato' => 2
            ],
            '3' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                'tipo_dato' => 2
            ],
            '4' => [
                'variabile' => 1067,
                'valore' => 19.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                'tipo_dato' => 2
            ],
            '5' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                'tipo_dato' => 2
            ],
            '6' => [
                'variabile' => 1067,
                'valore' => 21.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                'tipo_dato' => 2
            ]
        ];
        
        $expected = [
            '0' => [
                'variabile' => 1067,
                'valore' => 17.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                'tipo_dato' => 2
            ],
            '1' => [
                'variabile' => 1067,
                'valore' => 17.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                'tipo_dato' => 2
            ],
            '2' => [
                'variabile' => 1067,
                'valore' => 17.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                'tipo_dato' => 2
            ],
            '3' => [
                'variabile' => 1067,
                'valore' => 17.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                'tipo_dato' => 2
            ],
            '4' => [
                'variabile' => 1067,
                'valore' => 19.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                'tipo_dato' => 2
            ],
            '5' => [
                'variabile' => 1067,
                'valore' => 19.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                'tipo_dato' => 2
            ],
            '6' => [
                'variabile' => 1067,
                'valore' => 21.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                'tipo_dato' => 2
            ]
        ];
        
        $actual = riempiNull($dati);
        
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
     * covers riempiNull().
     * @group tools
     */
    public function testRiempiNullException() : void
    {
        $input = [
            '0' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                'tipo_dato' => 2
            ],
            '1' => [
                'variabile' => 1067,
                'valore' => 17.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                'tipo_dato' => 2
            ],
            '2' => [
                'variabile' => 1067,
                'valore' => 19.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                'tipo_dato' => 2
            ]
        ];
        
        $this->expectException(\Exception::class);
        
        riempiNull($input);
    }
    
    /**
     * covers interpolaNull().
     * @group tools
     */
    public function testInterpolaNullException() : void
    {
        $input = [
            '0' => [
                'variabile' => 1067,
                'valore' => null,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                'tipo_dato' => 2
            ],
            '1' => [
                'variabile' => 1067,
                'valore' => 17.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                'tipo_dato' => 2
            ],
            '2' => [
                'variabile' => 1067,
                'valore' => 19.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                'tipo_dato' => 2
            ]
        ];
        
        $this->expectException(\Exception::class);
        
        interpolaNull($input);
    }
    
    /**
     * @coversNothing
     */
    public function interpolaProvider() : array
    {
        $dati = [
            [10, 20, 10, 20, 15, 15],
            [0, 10, 0, 20, 15, 30],
            [10, 0, 20, 0, 15, 30],
            [-10, -20, 10, 20, 15, -15],
            [10, 20, -10, -20, -15, 15],
            [17.3, 23.5, 13.5, 44.9, 17.6, 15.019]
        ];
        
        return $dati;
    }
    
    /**
     * covers interpola().
     * @group tools
     * @dataProvider interpolaProvider
     */
    public function testInterpolaEqualsWithDelta(float $x1, float $x2, float $y1, float $y2, float $x, float $expected) : void
    {
        $actual = interpola($x1, $x2, $y1, $y2, $x);
        
        $this->assertEqualsWithDelta($expected, $actual, 0.001);
    }
    
    /**
     * covers interpola().
     * @group tools
     */
    public function testInterpolaException() : void
    {
        $x = [10, 10, 10, 20, 15];
        
        $this->expectException(\Exception::class);
        
        interpola($x[0], $x[1], $x[2], $x[3], $x[4]);
    }
    
    /**
     * @coversNothing
     */
    public function convertiUnitaProvider() : array
    {
        $dati = [
            'manovra %' => [
                'dati' => [
                    'unita_misura' => '%',
                    'valore' => 100
                ],
                'categoria' => 'manovra',
                'expected' => 1
            ],
            'livello' => [
                'dati' => [
                    'unita_misura' => 'mslm',
                    'valore' => 17.3467
                ],
                'categoria' => 'livello',
                'expected' => 17.3467
            ],
            'manovra cm' => [
                'dati' => [
                    'unita_misura' => 'cm',
                    'valore' => 234
                ],
                'categoria' => 'manovra',
                'expected' => 2.34
            ],
            'manovra m' => [
                'dati' => [
                    'unita_misura' => 'm',
                    'valore' => 2.34
                ],
                'categoria' => 'manovra',
                'expected' => 2.34
            ],
            'manovra gradi' => [
                'dati' => [
                    'unita_misura' => 'gradi',
                    'valore' => 60
                ],
                'categoria' => 'manovra',
                'expected' => 1.04719
            ]
        ];
        
        return $dati;
    }
    
    /**
     * covers convertiUnita()
     * @group tools
     * @dataProvider convertiUnitaProvider
     */
    public function testConvertiUnitaWithDelta(array $dati, string $categoria, float $expected) : void
    {
        $actual = convertiUnita($dati, $categoria);
        
        $this->assertEqualsWithDelta($expected, $actual, 0.001);
    }
    
    /**
     * covers convertiUnita()
     * @group tools
     */
    public function testConvertiUnitaException() : void
    {
        $dati = [
            'unita_di_misura' => '%',
            'valore' => 100
        ];
        $categoria = 'manovra';
        
        $this->expectException(\Exception::class);
        
        convertiUnita($dati, $categoria);
    }
    
    /**
     * @group index
     * covers eraseDoubleDate()
     */
    public function testEraseDoubleDateEquals() : array
    {
        $dati_acquisiti = [
            'livello monte' => [
                '0' => [
                    'variabile' => 1067,
                    'valore' => 17.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                    'tipo_dato' => 2
                ],
                '1' => [
                    'variabile' => 1067,
                    'valore' => 18.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 04:00:00'),
                    'tipo_dato' => 2
                ],
                '2' => [
                    'variabile' => 1067,
                    'valore' => 19.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 04:00:00'),
                    'tipo_dato' => 2
                ]
            ],
            'livello valle' => [
                '0' => [
                    'variabile' => 42,
                    'valore' => 17.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                    'tipo_dato' => 2
                ],
                '1' => [
                    'variabile' => 42,
                    'valore' => 27.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                    'tipo_dato' => 2
                ],
                '2' => [
                    'variabile' => 42,
                    'valore' => 37.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                    'tipo_dato' => 2
                ],
                '3' => [
                    'variabile' => 42,
                    'valore' => 57.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                    'tipo_dato' => 2
                ],
                '4' => [
                    'variabile' => 42,
                    'valore' => 47.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                    'tipo_dato' => 2
                ]
            ],
            'manovra' => [
                '0' => [
                    'variabile' => 30033,
                    'valore' => 0,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 00:30:00'),
                    'tipo_dato' => 1
                ],
                '1' => [
                    'variabile' => 30033,
                    'valore' => 0.5,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 00:30:00'),
                    'tipo_dato' => 1
                ],
                '2' => [
                    'variabile' => 30033,
                    'valore' => 1,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                    'tipo_dato' => 1
                ],
                '3' => [
                    'variabile' => 30033,
                    'valore' => 0.5,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                    'tipo_dato' => 1
                ]
            ]
        ];
        
        $expected = [
            'livello monte' => [
                '0' => [
                    'variabile' => 1067,
                    'valore' => 17.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                    'tipo_dato' => 2
                ],
                '1' => [
                    'variabile' => 1067,
                    'valore' => 18.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 04:00:00'),
                    'tipo_dato' => 2
                ]
            ],
            'livello valle' => [
                '0' => [
                    'variabile' => 42,
                    'valore' => 17.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                    'tipo_dato' => 2
                ],
                '1' => [
                    'variabile' => 42,
                    'valore' => 27.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                    'tipo_dato' => 2
                ],
                '2' => [
                    'variabile' => 42,
                    'valore' => 47.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                    'tipo_dato' => 2
                ]
            ],
            'manovra' => [
                '0' => [
                    'variabile' => 30033,
                    'valore' => 0,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 00:30:00'),
                    'tipo_dato' => 1
                ],
                '1' => [
                    'variabile' => 30033,
                    'valore' => 1,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                    'tipo_dato' => 1
                ],
                '2' => [
                    'variabile' => 30033,
                    'valore' => 0.5,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                    'tipo_dato' => 1
                ]
            ]
        ];
        
        $actual = eraseDoubleDate($dati_acquisiti);
        
        foreach ($actual as $categoria => $dati) {
            foreach ($dati as $row => $fields) {
                foreach ($fields as $key => $value) {
                    $this->assertEquals($expected[$categoria][$row][$key], $value);
                }
            }
        }
        
        foreach ($expected as $categoria => $dati) {
            foreach ($dati as $row => $fields) {
                foreach ($fields as $key => $value) {
                    $this->assertEquals($value, $actual[$categoria][$row][$key]);
                }
            }
        }
        return $actual;
    }
    
    /**
     * covers eraseDoubleDate().
     * @group index
     */
    public function testEraseDoubleDateException() : void
    {
        $dati_acquisiti = [];
        
        $this->expectException(\Exception::class);
        
        eraseDoubleDate($dati_acquisiti);
    }
    
    /**
     * covers changeDate().
     * @group tools
     */
    public function testChangeDateEquals() : void
    {
        $dati = [
            'pluto' => 'pippo',
            'topolino' => new \DateTime('2020-12-31 00:30:00'),
            'paperino' => 313
        ];
        
        $expected = [
            'pluto' => 'pippo',
            'topolino' => '31/12/2020 00:30:00',
            'paperino' => 313
        ];
        
        $actual = changeDate($dati);
        
        foreach ($actual as $key => $value) {
            $this->assertEquals($expected[$key], $value);
        }
        
        foreach ($expected as $key => $value) {
            $this->assertEquals($value, $actual[$key]);
        }
    }
    
    /**
     * @group csv
     * covers debugOnCSV()
     */
    public function testDebugOnCsvFileExists() : void
    {
        $fileName = 'test_debug';
        
        $dati = [
            '0' => [
                'variabile' => '30030',
                'data_e_ora' => new \DateTime('2020-12-31 20:30:00'),
                'tipo_dato' => '1',
                'valore' => '2221,930'
            ],
            '1' => [
                'variabile' => '30030',
                'data_e_ora' => new \DateTime('2020-12-31 20:45:00'),
                'tipo_dato' => '1',
                'valore' => '2250,234'
            ],
            '2' => [
                'variabile' => '30030',
                'data_e_ora' => new \DateTime('2020-12-31 21:00:00'),
                'tipo_dato' => '1',
                'valore' => '2278,234'
            ]
        ];
        
        debugOnCSV($dati, $fileName);
        
        $this->assertFileExists($fileName);
    }
}
