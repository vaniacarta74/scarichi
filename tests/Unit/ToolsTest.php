<?php
namespace vaniacarta74\Scarichi\tests\Unit;

use PHPUnit\Framework\TestCase;
use vaniacarta74\Scarichi\tests\classes\CsvFileIterator;

use function vaniacarta74\Scarichi\checkFilter as checkFilter;
use function vaniacarta74\Scarichi\checkVariable as checkVariable;
use function vaniacarta74\Scarichi\formatDate as formatDate;
use function vaniacarta74\Scarichi\formatDateTime as formatDateTime;
use function vaniacarta74\Scarichi\checkInterval as checkInterval;
use function vaniacarta74\Scarichi\setDateTimes as setDateTimes;
use function vaniacarta74\Scarichi\checkRequest as checkRequest;
use function vaniacarta74\Scarichi\checkField as checkField;
use function vaniacarta74\Scarichi\connect as connect;
use function vaniacarta74\Scarichi\query as query;
use function vaniacarta74\Scarichi\fetch as fetch;
use function vaniacarta74\Scarichi\changeTimeZone as changeTimeZone;
use function vaniacarta74\Scarichi\checkDates as checkDates;
use function vaniacarta74\Scarichi\datesToString as datesToString;
use function vaniacarta74\Scarichi\setToLocal as setToLocal;
use function vaniacarta74\Scarichi\getDataFromDb as getDataFromDb;
use function vaniacarta74\Scarichi\initVolumi as initVolumi;
use function vaniacarta74\Scarichi\addCategoria as addCategoria;
use function vaniacarta74\Scarichi\addMedia as addMedia;
use function vaniacarta74\Scarichi\addAltezza as addAltezza;
use function vaniacarta74\Scarichi\addPortata as addPortata;
use function vaniacarta74\Scarichi\addDelta as addDelta;
use function vaniacarta74\Scarichi\addVolume as addVolume;
use function vaniacarta74\Scarichi\format as format;
use function vaniacarta74\Scarichi\setPath as setPath;
use function vaniacarta74\Scarichi\setFile as setFile;
use function vaniacarta74\Scarichi\printToCSV as printToCSV;
use function vaniacarta74\Scarichi\printPart as printPart;
use function vaniacarta74\Scarichi\divideAndPrint as divideAndPrint;
use function vaniacarta74\Scarichi\checkNull as checkNull;
use function vaniacarta74\Scarichi\response as response;
use function vaniacarta74\Scarichi\close as close;
use function vaniacarta74\Scarichi\calcolaPortata as calcolaPortata;
use function vaniacarta74\Scarichi\integraDate as integraDate;
use function vaniacarta74\Scarichi\uniformaCategorie as uniformaCategorie;
use function vaniacarta74\Scarichi\completaDati as completaDati;
use function vaniacarta74\Scarichi\trovaCapi as trovaCapi;
use function vaniacarta74\Scarichi\riempiCode as riempiCode;
use function vaniacarta74\Scarichi\riempiNull as riempiNull;
use function vaniacarta74\Scarichi\interpolaNull as interpolaNull;
use function vaniacarta74\Scarichi\interpola as interpola;
use function vaniacarta74\Scarichi\convertiUnita as convertiUnita;
use function vaniacarta74\Scarichi\eraseDoubleDate as eraseDoubleDate;
use function vaniacarta74\Scarichi\changeDate as changeDate;
use function vaniacarta74\Scarichi\filter as filter;
use function vaniacarta74\Scarichi\debugOnCSV as debugOnCSV;
use function vaniacarta74\Scarichi\getHelpLines as getHelpLines;
use function vaniacarta74\Scarichi\formatParams as formatParams;
use function vaniacarta74\Scarichi\formatDefault as formatDefault;
use function vaniacarta74\Scarichi\formatVariables as formatVariables;
use function vaniacarta74\Scarichi\formatCostants as formatCostants;
use function vaniacarta74\Scarichi\formatOptions as formatOptions;
use function vaniacarta74\Scarichi\formatDescriptions as formatDescriptions;
use function vaniacarta74\Scarichi\formatShort as formatShort;
use function vaniacarta74\Scarichi\formatLong as formatLong;
use function vaniacarta74\Scarichi\formatPardef as formatPardef;
use function vaniacarta74\Scarichi\fillLineSections as fillLineSections;
use function vaniacarta74\Scarichi\getMaxLenght as getMaxLenght;
use function vaniacarta74\Scarichi\setConsole as setConsole;
use function vaniacarta74\Scarichi\setHeader as setHeader;
use function vaniacarta74\Scarichi\setMsgHtml as setMsgHtml;
use function vaniacarta74\Scarichi\setMsgVersion as setMsgVersion;
use function vaniacarta74\Scarichi\setMsgDefault as setMsgDefault;
use function vaniacarta74\Scarichi\setMsgHelp as setMsgHelp;
use function vaniacarta74\Scarichi\setMsgOk as setMsgOk;
use function vaniacarta74\Scarichi\setMsgError as setMsgError;
use function vaniacarta74\Scarichi\getMessage as getMessage;
use function vaniacarta74\Scarichi\propertyToString as propertyToString;
use function vaniacarta74\Scarichi\stringsToString as stringsToString;
use function vaniacarta74\Scarichi\getProperties as getProperties;
use function vaniacarta74\Scarichi\filterProperties as filterProperties;
use function vaniacarta74\Scarichi\selectAllQuery as selectAllQuery;
use function vaniacarta74\Scarichi\allParameterSet as allParameterSet;
use function vaniacarta74\Scarichi\checkCliVar as checkCliVar;
use function vaniacarta74\Scarichi\checkCliDatefrom as checkCliDatefrom;
use function vaniacarta74\Scarichi\checkCliDateto as checkCliDateto;
use function vaniacarta74\Scarichi\checkCliField as checkCliField;
use function vaniacarta74\Scarichi\checkCliFull as checkCliFull;
use function vaniacarta74\Scarichi\checkParameter as checkParameter;
use function vaniacarta74\Scarichi\setParameter as setParameter;
use function vaniacarta74\Scarichi\shuntTypes as shuntTypes;
use function vaniacarta74\Scarichi\setParameters as setParameters;
use function vaniacarta74\Scarichi\fillParameters as fillParameters;
use function vaniacarta74\Scarichi\fillVar as fillVar;
use function vaniacarta74\Scarichi\fillDatefrom as fillDateFrom;
use function vaniacarta74\Scarichi\fillDateto as fillDateto;
use function vaniacarta74\Scarichi\fillField as fillField;
use function vaniacarta74\Scarichi\fillFull as fillFull;
use function vaniacarta74\Scarichi\setPostParameters as setPostParameters;
use function vaniacarta74\Scarichi\goCurl as goCurl;
use function vaniacarta74\Scarichi\insertNoData as insertNoData;
use function vaniacarta74\Scarichi\selectLastPrevData as selectLastPrevData;
use function vaniacarta74\Scarichi\checkCurlResponse as checkCurlResponse;

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
            ],
            'ultimo precedente' => [
                'dbase' => 'SSCP_data',
                'file' => 'query_ultimo_precedente',
                'parametri' => [
                    'variabile' => '30028',
                    'tipo dato' => '1',
                    'data_e_ora' => new \DateTime('2020-04-21 00:00:00')
                ],
                'expected' => [
                    '0' => [
                        'variabile' => 30028,
                        'valore' => 0,
                        'unita_misura' => 'cm',
                        'tipo_dato' => 1
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
            ],
            'volume' => [
                [
                    'field' => 'V'
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
     * @group depends
     * covers initVolumi()
     */
    public function testInitVolumiNoDataEquals() : void
    {
        $variabili = [
            'id_variabile' => 30030,
            'impianto' => 75,
            'unita_misura' => 'mc'
        ];
        $dati = [];
        $expected = [];
        
        $actual = initVolumi($variabili, $dati);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group ex
     * covers initVolumi()
     */
    public function testInitVolumiException() : array
    {
        $variabili = [
            'variabile' => 30030,
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
                
        $this->expectException(\Exception::class);
        
        initVolumi($variabili, $dati);
    }
    
    /**
     * @group depends
     * covers addCategoria()
     */
    public function testAddCategoriaEquals() : array
    {
        $volumi = [
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
     */
    public function testAddCategoriaManovraEquals() : void
    {
        $volumi = [
            '0' => [
                'variabile' => 30050,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 266.206,
                'altezza' => 0.026,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30050,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media livello' => 266.173,
                'altezza' => -0.007,
                'tipo_dato' => 1
            ]
        ];
        
        $dati = [
            'manovra' => [
                '0' => [
                    'variabile' => 39999,
                    'valore' => 100,
                    'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                    'unita_misura' => '%',
                    'tipo_dato' => 1
                ],
                '1' => [
                    'variabile' => 39999,
                    'valore' => 100,
                    'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                    'unita_misura' => '%',
                    'tipo_dato' => 1
                ]
            ]
        ];
        
        $categoria = 'manovra';
        
        $expected = [
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
    }
    
    /**
     * @group depends
     * covers addCategoria()
     */
    public function testAddCategoriaNoDataManovraEquals() : void
    {
        $volumi = [
            '0' => [
                'variabile' => 30050,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 266.206,
                'altezza' => 0.026,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30050,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media livello' => 266.173,
                'altezza' => -0.007,
                'tipo_dato' => 1
            ]
        ];
        
        $dati = [
            'manovra' => [
                '0' => [
                    'variabile' => NODATA,
                    'valore' => NODATA,
                    'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                    'unita_misura' => NODATA,
                    'tipo_dato' => NODATA
                ],
                '1' => [
                    'variabile' => NODATA,
                    'valore' => NODATA,
                    'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                    'unita_misura' => NODATA,
                    'tipo_dato' => NODATA
                ]
            ]
        ];
        
        $categoria = 'manovra';
        
        $expected = [
            '0' => [
                'variabile' => 30050,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 266.206,
                'altezza' => 0.026,
                'tipo_dato' => 1,
                'manovra' => NODATA
            ],
            '1' => [
                'variabile' => 30050,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media livello' => 266.173,
                'altezza' => -0.007,
                'tipo_dato' => 1,
                'manovra' => NODATA
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
    }
    
    /**
     * @group depends
     * covers addCategoria()
     */
    public function testAddCategoriaNoCategoryEquals() : void
    {
        $volumi = [];
        $dati = [
            'livello' => [],
            'livello monte' => [],
            'manovra' => [],
        ];
        $categoria = 'manovra';
        $expected = [];
        
        $actual = addCategoria($volumi, $dati, $categoria);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group depends
     * covers addCategoria()
     */
    public function testAddCategoriaArrayDifferentException() : array
    {
        $volumi = [
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
     */
    public function testAddCategoriaDateDifferentException() : void
    {
        $volumi = [
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
     */
    public function testAddMediaNoCategoryEquals() : void
    {
        $volumi = [];
        $campo = 'livello';
        $expected = [];
        
        $actual = addMedia($volumi, $campo);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group depends
     * covers addMedia()
     */
    public function testAddMediaNoLevelEquals() : void
    {
        $volumi = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 265,
                'livello valle' => NODATA,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media livello' => 265,
                'livello valle' => NODATA,
                'tipo_dato' => 1
            ]
        ];
        $campo = 'livello valle';
        $expected = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 265,
                'livello valle' => NODATA,
                'media livello valle' => NODATA,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media livello' => 265,
                'livello valle' => NODATA,
                'media livello valle' => NODATA,
                'tipo_dato' => 1
            ]
        ];
        
        $actual = addMedia($volumi, $campo);
        
        $this->assertEquals($expected, $actual);
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
    public function testAddAltezzaNo1LevelEquals() : void
    {
        $volumi = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => NODATA,
                'media livello' => NODATA,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => NODATA,
                'media livello' => NODATA,
                'tipo_dato' => 1
            ]
        ];
        
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
                'livello' => NODATA,
                'media livello' => NODATA,
                'altezza' => NODATA,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => NODATA,
                'media livello' => NODATA,
                'altezza' => NODATA,
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
     */
    public function testAddAltezzaNo2LevelEquals() : array
    {
        $volumi = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 265,
                'livello valle' => NODATA,
                'media livello valle' => NODATA,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media livello' => 265,
                'livello valle' => NODATA,
                'media livello valle' => NODATA,
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
                'livello valle' => NODATA,
                'media livello valle' => NODATA,
                'altezza' => NODATA,
                'tipo_dato' => 1
            ],
            '1' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media livello' => 265,
                'livello valle' => NODATA,
                'media livello valle' => NODATA,
                'altezza' => NODATA,
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
    public function testAddAltezzaNoCategoryEquals() : void
    {
        $volumi = [];
        $specifiche = [
            'tipo_formula' => 'portata galleria',
            'scarico' => 1,
            'mi' => 0.47,
            'larghezza' => 40.5,
            'quota' => 260,
            'limite' => 942.67
        ];
        $expected = [];
        
        $actual = addAltezza($volumi, $specifiche);
        
        $this->assertEquals($expected, $actual);
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
     * covers addPortata().
     * @group depends
     */
    public function testAddPortataNoDataEquals() : void
    {
        $volumi = [
            '0' => [
                'variabile' => 30050,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => NODATA,
                'media livello' => NODATA,
                'altezza' => NODATA,
                'tipo_dato' => 1,
                'manovra' => NODATA
            ],
            '1' => [
                'variabile' => 30050,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => NODATA,
                'media livello' => NODATA,
                'altezza' => NODATA,
                'tipo_dato' => 1,
                'manovra' => NODATA
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
                'livello' => NODATA,
                'media livello' => NODATA,
                'altezza' => NODATA,
                'tipo_dato' => 1,
                'manovra' => NODATA,
                'portata' => NODATA
            ],
            '1' => [
                'variabile' => 30050,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => NODATA,
                'media livello' => NODATA,
                'altezza' => NODATA,
                'tipo_dato' => 1,
                'manovra' => NODATA,
                'portata' => NODATA
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
    public function testAddPortataNoDataManovraEquals() : void
    {
        $volumi = [
            '0' => [
                'variabile' => 30051,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 266.206,
                'altezza' => 0.026,
                'tipo_dato' => 1,
                'manovra' => NODATA
            ],
            '1' => [
                'variabile' => 30051,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media livello' => 266.173,
                'altezza' => -0.007,
                'tipo_dato' => 1,
                'manovra' => NODATA
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
                'variabile' => 30051,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 266.206,
                'altezza' => 0.026,
                'tipo_dato' => 1,
                'manovra' => NODATA,
                'portata' => NODATA
            ],
            '1' => [
                'variabile' => 30051,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media livello' => 266.173,
                'altezza' => -0.007,
                'tipo_dato' => 1,
                'manovra' => NODATA,
                'portata' => NODATA
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
    public function testAddPortataNoCategoryEquals() : void
    {
        $volumi = [];
        
        $specifiche = [
            'tipo_formula' => 'portata scarico a sezione rettangolare con velocita e apertura percentuale',
            'scarico' => 22,
            'mi' => 0.47,
            'larghezza' => 158.61,
            'quota' => 16.05,
            'limite' => 2000,
            'velocita' => 0.8
        ];
        
        $expected = [];
        
        $actual = addPortata($volumi, $specifiche);
        
        $this->assertEquals($expected, $actual);
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
    public function testAddDeltaNoDataEquals() : void
    {
        $volumi = [
            '0' => [
                'variabile' => 30051,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 266.206,
                'altezza' => 0.026,
                'tipo_dato' => 1,
                'manovra' => NODATA,
                'portata' => NODATA
            ],
            '1' => [
                'variabile' => 30051,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media livello' => 266.173,
                'altezza' => -0.007,
                'tipo_dato' => 1,
                'manovra' => NODATA,
                'portata' => NODATA
            ]
        ];
        $campo = 'data_e_ora';
        $expected = [
            '0' => [
                'variabile' => 30051,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 266.206,
                'altezza' => 0.026,
                'tipo_dato' => 1,
                'manovra' => NODATA,
                'portata' => NODATA,
                'delta' => 0
            ],
            '1' => [
                'variabile' => 30051,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media livello' => 266.173,
                'altezza' => -0.007,
                'tipo_dato' => 1,
                'manovra' => NODATA,
                'portata' => NODATA,
                'delta' => 900
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
    }
    
    /**
     * @group depends
     * covers addDelta()
     */
    public function testAddDeltaNoCategoryEquals() : void
    {
        $volumi = [];
        $campo = 'data_e_ora';
        $expected = [];
        
        $actual = addDelta($volumi, $campo);
        
        $this->assertEquals($expected, $actual);
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
    public function testAddVolumeNoDataEquals() : void
    {
        $volumi = [
            '0' => [
                'variabile' => 30051,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 266.206,
                'altezza' => 0.026,
                'tipo_dato' => 1,
                'manovra' => NODATA,
                'portata' => NODATA,
                'delta' => 0
            ],
            '1' => [
                'variabile' => 30051,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media livello' => 266.173,
                'altezza' => -0.007,
                'tipo_dato' => 1,
                'manovra' => NODATA,
                'portata' => NODATA,
                'delta' => 900
            ]
        ];
        
        $expected = [
            '0' => [
                'variabile' => 30051,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media livello' => 266.206,
                'altezza' => 0.026,
                'tipo_dato' => 1,
                'manovra' => NODATA,
                'portata' => NODATA,
                'delta' => 0,
                'volume' => NODATA
            ],
            '1' => [
                'variabile' => 30051,
                'data_e_ora' => new \DateTime('2018-01-02 00:15:00'),
                'livello' => 266.140,
                'media livello' => 266.173,
                'altezza' => -0.007,
                'tipo_dato' => 1,
                'manovra' => NODATA,
                'portata' => NODATA,
                'delta' => 900,
                'volume' => NODATA
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
    }
    
    /**
     * @group depends
     * covers addVolume()
     */
    public function testAddVolumeNoCategoryEquals() : void
    {
        $volumi = [];
        $expected = [];
        
        $actual = addVolume($volumi);
        
        $this->assertEquals($expected, $actual);
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
     */
    public function testFormatNoDataEquals() : array
    {
        $campo = 'portata';
        
        $volumi = [
            '0' => [
                'variabile' => 30030,
                'data_e_ora' => new \DateTime('2018-01-02 00:00:00'),
                'livello' => 266.206,
                'media' => 266.206,
                'altezza' => 0.026,
                'portata' => NODATA,
                'delta' => 0,
                'volume' => 0,
                'tipo_dato' => 1
            ]
        ];
        
        $expected = [
            '0' => [
                'variabile' => '30030',
                'valore' => NODATA,
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
     */
    public function testFormatNoCategoryEquals() : void
    {
        $campo = 'portata';
        $volumi = [];
        $expected = [];
        
        $actual = format($volumi, $campo);
        
        $this->assertEquals($expected, $actual);
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
        $filter = 0;
        
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
        
        $actual = filter($volumi, $full, $filter);
        
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
     */
    public function testFilterNoDataEquals() : void
    {
        $volumi = [
            '0' => [
                'variabile' => '30030',
                'valore' => strval(NODATA),
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
        $full = false;
        $filter = NODATA;
        
        $expected = [
            '1' => [
                'variabile' => '30030',
                'valore' => '0',
                'data_e_ora' => '02/01/2018 00:15:00',
                'tipo_dato' => '1'
            ]
        ];
        
        $actual = filter($volumi, $full, $filter);
        
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
     * @depends testFormatEquals2
     */
    public function testFilterEquals1(array $volumi) : void
    {
        $full = false;
        $filter = 0;
        
        $expected = [
            '1' => [
                'variabile' => '30030',
                'valore' => '900',
                'data_e_ora' => '02/01/2018 00:15:00',
                'tipo_dato' => '1'
            ]
        ];
        
        $actual = filter($volumi, $full, $filter);
        
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
        $filter = 0;
        
        $actual = filter($volumi, $full, $filter);
        
        $this->assertEmpty($actual);
    }
    
    /**
     * @group depends
     * covers filter()
     */
    public function testFilterException() : void
    {
        $full = false;
        $filter = 0;
        $volumi = [
            '0' => [
                'variabile' => '30030',
                'dummy' => '900',
                'data_e_ora' => '02/01/2018 00:15:00',
                'tipo_dato' => '1'
            ]
        ];
        
        $this->expectException(\Exception::class);
        
        filter($volumi, $full, $filter);
    }
    
    /**
     * @group depends
     * covers setPath()
     */
    public function testSetPathNoSubEquals() : void
    {
        $variabile = 'Test';
        $path = CSV;
        $makeDir = false;
        
        $expected = CSV;
        
        $actual = setPath($variabile, $path, $makeDir);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group depends
     * covers setPath()
     */
    public function testSetPathSubEquals() : string
    {
        $variabile = 'Test';
        $path = CSV;
        $makeDir = true;
        
        $expected = CSV . '/v' . $variabile;
        
        $actual = setPath($variabile, $path, $makeDir);
        
        $this->assertEquals($expected, $actual);
        
        return $variabile;
    }
    
    /**
     * @group depends
     * covers setPath()
     * @depends testSetPathSubEquals
     */
    public function testSetPathSubEquals2($variabile) : void
    {
        $path = CSV;
        $makeDir = true;
        
        $expected = CSV . '/v' . $variabile;
        
        $actual = setPath($variabile, $path, $makeDir);
        
        $this->assertEquals($expected, $actual);
        
        rmdir($actual);
    }
    
    /**
     * @group depends
     * covers setPath()
     */
    public function testSetPathException() : void
    {
        $variabile = '30030';
        $path = 'pippo';
        $makeDir = true;
        
        $this->expectException(\Exception::class);
        
        setPath($variabile, $path, $makeDir);
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
    public function printToCsvProvider() : CsvFileIterator
    {
        $fileName = __DIR__ . '/../providers/test.csv';
        
        $iterator = new CsvFileIterator($fileName);
        
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
        
        $subDir = MAKESUBDIR ? '/v30030' : '';
        $expected = CSV . $subDir . '/Livello_30030_201803252030_201803252045_full.csv';
        
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
        
        $subDir = MAKESUBDIR ? '/v30030' : '';
        $expecteds = [
            CSV . $subDir . '/Delta_30030_201801020000_201801020000_full.csv',
            CSV . $subDir . '/Delta_30030_201801020015_201801020015_full.csv',
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
        
        $subDir = MAKESUBDIR ? '/v30030' : '';
        $expecteds = [
            CSV . $subDir . '/Delta_30030_201801020000_201801020015_full.csv'
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
    public function testIntegraDateNoTarget1CheckEquals() : void
    {
        $target = [];
        
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
            ]
        ];
        
        $expected = [
            '0' => [
                'variabile' => NODATA,
                'valore' => NODATA,
                'unita_misura' => NODATA,
                'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                'tipo_dato' => NODATA
            ],
            '1' => [
                'variabile' => NODATA,
                'valore' => null,
                'unita_misura' => NODATA,
                'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                'tipo_dato' => NODATA
            ],
            '2' => [
                'variabile' => NODATA,
                'valore' => null,
                'unita_misura' => NODATA,
                'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                'tipo_dato' => NODATA
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
    public function testIntegraDateNoTarget2CheckEquals() : void
    {
        $target = [];
        
        $checkers = [
            'livello valle' => [
                '0' => [
                    'variabile' => 42,
                    'valore' => 27.3,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 01:00:00'),
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
                'variabile' => NODATA,
                'valore' => null,
                'unita_misura' => NODATA,
                'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                'tipo_dato' => NODATA
            ],
            '1' => [
                'variabile' => NODATA,
                'valore' => NODATA,
                'unita_misura' => NODATA,
                'data_e_ora' => new \DateTime('05/01/2020 01:00:00'),
                'tipo_dato' => NODATA
            ],
            '2' => [
                'variabile' => NODATA,
                'valore' => null,
                'unita_misura' => NODATA,
                'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                'tipo_dato' => NODATA
            ],
            '3' => [
                'variabile' => NODATA,
                'valore' => null,
                'unita_misura' => NODATA,
                'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                'tipo_dato' => NODATA
            ],
            '4' => [
                'variabile' => NODATA,
                'valore' => null,
                'unita_misura' => NODATA,
                'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                'tipo_dato' => NODATA
            ],
            '5' => [
                'variabile' => NODATA,
                'valore' => null,
                'unita_misura' => NODATA,
                'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                'tipo_dato' => NODATA
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
    public function testIntegraDateNoTarget1NoCheckEquals() : void
    {
        $target = [];
        
        $checkers = [
            'livello valle' => [],
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
                'variabile' => NODATA,
                'valore' => NODATA,
                'unita_misura' => NODATA,
                'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                'tipo_dato' => NODATA
            ],
            '1' => [
                'variabile' => NODATA,
                'valore' => null,
                'unita_misura' => NODATA,
                'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                'tipo_dato' => NODATA
            ],
            '2' => [
                'variabile' => NODATA,
                'valore' => null,
                'unita_misura' => NODATA,
                'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                'tipo_dato' => NODATA
            ],
            '3' => [
                'variabile' => NODATA,
                'valore' => null,
                'unita_misura' => NODATA,
                'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                'tipo_dato' => NODATA
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
    public function testIntegraDateNoTarget2NoCheckEquals() : void
    {
        $target = [];
        
        $checkers = [
            'livello valle' => [],
            'manovra' => []
        ];
        
        $expected = [];
        
        $actual = integraDate($target, $checkers);
        
        $this->assertEquals($expected, $actual);
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
                '0' => [],
                '1' => [
                    'variabile' => 30033,
                    'valore' => 0.5,
                    'unita_misura' => 'cm',
                    'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                    'tipo_dato' => 1
                ]
            ]
        ];
        
        $this->expectException(\Exception::class);
        
        integraDate($target, $checkers);
    }
    
    /**
     * @group tools
     * covers insertNoData()
     */
    public function testInsertNoDataEquals() : void
    {
        $target = [];
        
        $dato = [
            'variabile' => 42,
            'valore' => 27.3,
            'unita_misura' => 'mslm',
            'data_e_ora' => new \DateTime('05/01/2020 01:00:00'),
            'tipo_dato' => 2
        ];
        
        $expected = [
            '0' => [
                'variabile' => NODATA,
                'valore' => NODATA,
                'unita_misura' => NODATA,
                'data_e_ora' => new \DateTime('05/01/2020 01:00:00'),
                'tipo_dato' => NODATA
            ]
        ];
        
        $actual = insertNoData($target, $dato);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group tools
     * covers insertNoData()
     */
    public function testInsertNoDataWithTargetEquals() : void
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
        
        $dato = [
            'variabile' => 42,
            'valore' => 27.3,
            'unita_misura' => 'mslm',
            'data_e_ora' => new \DateTime('05/01/2020 01:00:00'),
            'tipo_dato' => 2
        ];
        
        $expected = [
            '0' => [
                'variabile' => 1067,
                'valore' => 17.3,
                'unita_misura' => 'mslm',
                'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                'tipo_dato' => 2
            ]
        ];
        
        $actual = insertNoData($target, $dato);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group tools
     * covers insertNoData()
     */
    public function testInsertNoDataException() : void
    {
        $target = [];
        $dato = [];
        
        $this->expectException(\Exception::class);
        
        insertNoData($target, $dato);
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
    public function testUniformaCategorieNoManovreEquals() : array
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
                ]
            ],
            'manovra' => []
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
                    'valore' => 35.97,
                    'unita_misura' => 'mslm',
                    'data_e_ora' => new \DateTime('05/01/2020 01:00:00'),
                    'tipo_dato' => 2
                ]
            ],
            'manovra' => [
                '0' => [
                    'variabile' => NODATA,
                    'valore' => NODATA,
                    'unita_misura' => NODATA,
                    'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                    'tipo_dato' => NODATA
                ],
                '1' => [
                    'variabile' => NODATA,
                    'valore' => null,
                    'unita_misura' => NODATA,
                    'data_e_ora' => new \DateTime('05/01/2020 00:30:00'),
                    'tipo_dato' => NODATA
                ],
                '2' => [
                    'variabile' => NODATA,
                    'valore' => null,
                    'unita_misura' => NODATA,
                    'data_e_ora' => new \DateTime('05/01/2020 01:00:00'),
                    'tipo_dato' => NODATA
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
    public function testUniformaCategorieNoCategoryEquals() : void
    {
        $input = [
            'livello monte' => [],
            'livello valle' => [],
            'manovra' => []
        ];
        
        $expected = [
            'livello monte' => [],
            'livello valle' => [],
            'manovra' => []
        ];
        
        $actual = uniformaCategorie($input);
        
        $this->assertEquals($expected, $actual);
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
     */
    public function testCompletaDatiEquals() : void
    {
        $input = [
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
     * covers completaDati().
     * @group index
     */
    public function testCompletaDatiNoDataEquals() : void
    {
        $input = [
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
                    'variabile' => NODATA,
                    'valore' => null,
                    'unita_misura' => NODATA,
                    'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                    'tipo_dato' => NODATA
                ],
                '1' => [
                    'variabile' => NODATA,
                    'valore' => NODATA,
                    'unita_misura' => NODATA,
                    'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                    'tipo_dato' => NODATA
                ],
                '2' => [
                    'variabile' => NODATA,
                    'valore' => null,
                    'unita_misura' => NODATA,
                    'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                    'tipo_dato' => NODATA
                ],
                '3' => [
                    'variabile' => NODATA,
                    'valore' => null,
                    'unita_misura' => NODATA,
                    'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                    'tipo_dato' => NODATA
                ],
                '4' => [
                    'variabile' => NODATA,
                    'valore' => null,
                    'unita_misura' => NODATA,
                    'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                    'tipo_dato' => NODATA
                ],
                '5' => [
                    'variabile' => NODATA,
                    'valore' => null,
                    'unita_misura' => NODATA,
                    'data_e_ora' => new \DateTime('05/01/2020 04:00:00'),
                    'tipo_dato' => NODATA
                ],
                '6' => [
                    'variabile' => NODATA,
                    'valore' => null,
                    'unita_misura' => NODATA,
                    'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                    'tipo_dato' => NODATA
                ],
                '7' => [
                    'variabile' => NODATA,
                    'valore' => null,
                    'unita_misura' => NODATA,
                    'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                    'tipo_dato' => NODATA
                ]
            ]
        ];
        
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
                    'variabile' => NODATA,
                    'valore' => NODATA,
                    'unita_misura' => NODATA,
                    'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                    'tipo_dato' => NODATA
                ],
                '1' => [
                    'variabile' => NODATA,
                    'valore' => NODATA,
                    'unita_misura' => NODATA,
                    'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                    'tipo_dato' => NODATA
                ],
                '2' => [
                    'variabile' => NODATA,
                    'valore' => NODATA,
                    'unita_misura' => NODATA,
                    'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                    'tipo_dato' => NODATA
                ],
                '3' => [
                    'variabile' => NODATA,
                    'valore' => NODATA,
                    'unita_misura' => NODATA,
                    'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                    'tipo_dato' => NODATA
                ],
                '4' => [
                    'variabile' => NODATA,
                    'valore' => NODATA,
                    'unita_misura' => NODATA,
                    'data_e_ora' => new \DateTime('05/01/2020 02:00:00'),
                    'tipo_dato' => NODATA
                ],
                '5' => [
                    'variabile' => NODATA,
                    'valore' => NODATA,
                    'unita_misura' => NODATA,
                    'data_e_ora' => new \DateTime('05/01/2020 04:00:00'),
                    'tipo_dato' => NODATA
                ],
                '6' => [
                    'variabile' => NODATA,
                    'valore' => NODATA,
                    'unita_misura' => NODATA,
                    'data_e_ora' => new \DateTime('05/01/2020 05:00:00'),
                    'tipo_dato' => NODATA
                ],
                '7' => [
                    'variabile' => NODATA,
                    'valore' => NODATA,
                    'unita_misura' => NODATA,
                    'data_e_ora' => new \DateTime('05/01/2020 05:30:00'),
                    'tipo_dato' => NODATA
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
     * covers completaDati().
     * @group index
     */
    public function testCompletaDatiNoCategoryEquals() : void
    {
        $input = [
            'livello monte' => [],
            'livello valle' => [],
            'manovra' => []
        ];
        
        $expected = [
            'livello monte' => [],
            'livello valle' => [],
            'manovra' => []
        ];
        
        $actual = completaDati($input);
        
        $this->assertEquals($expected, $actual);
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
                ],
            ],
            'middle no data' => [
                'input' => [
                    '0' => [
                        'variabile' => NODATA,
                        'valore' => null,
                        'unita_misura' => NODATA,
                        'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                        'tipo_dato' => NODATA
                    ],
                    '1' => [
                        'variabile' => NODATA,
                        'valore' => NODATA,
                        'unita_misura' => NODATA,
                        'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                        'tipo_dato' => NODATA
                    ],
                    '2' => [
                        'variabile' => NODATA,
                        'valore' => null,
                        'unita_misura' => NODATA,
                        'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                        'tipo_dato' => NODATA
                    ]
                ],
                'expected' => [
                    'testa' => [
                        'id' => 1,
                        'valore' => NODATA
                    ],
                    'coda' => [
                        'id' => 1,
                        'valore' => NODATA
                    ]
                ]
            ],
            'top no data' => [
                'input' => [
                    '0' => [
                        'variabile' => NODATA,
                        'valore' => NODATA,
                        'unita_misura' => NODATA,
                        'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                        'tipo_dato' => NODATA
                    ],
                    '1' => [
                        'variabile' => NODATA,
                        'valore' => null,
                        'unita_misura' => NODATA,
                        'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                        'tipo_dato' => NODATA
                    ],
                    '2' => [
                        'variabile' => NODATA,
                        'valore' => null,
                        'unita_misura' => NODATA,
                        'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                        'tipo_dato' => NODATA
                    ]
                ],
                'expected' => [
                    'testa' => [
                        'id' => 0,
                        'valore' => NODATA
                    ],
                    'coda' => [
                        'id' => 0,
                        'valore' => NODATA
                    ]
                ]
            ],
            'bottom no data' => [
                'input' => [
                    '0' => [
                        'variabile' => NODATA,
                        'valore' => null,
                        'unita_misura' => NODATA,
                        'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                        'tipo_dato' => NODATA
                    ],
                    '1' => [
                        'variabile' => NODATA,
                        'valore' => null,
                        'unita_misura' => NODATA,
                        'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                        'tipo_dato' => NODATA
                    ],
                    '2' => [
                        'variabile' => NODATA,
                        'valore' => NODATA,
                        'unita_misura' => NODATA,
                        'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                        'tipo_dato' => NODATA
                    ]
                ],
                'expected' => [
                    'testa' => [
                        'id' => 2,
                        'valore' => NODATA
                    ],
                    'coda' => [
                        'id' => 2,
                        'valore' => NODATA
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
     * covers trovaCapi().
     * @group tools
     */
    public function testTrovaCapiNoDataException() : void
    {
        $input = [];
        
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
    public function testRiempiCodeNoDataEquals() : void
    {
        $dati = [
            '0' => [
                'variabile' => NODATA,
                'valore' => null,
                'unita_misura' => NODATA,
                'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                'tipo_dato' => NODATA
            ],
            '1' => [
                'variabile' => NODATA,
                'valore' => NODATA,
                'unita_misura' => NODATA,
                'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                'tipo_dato' => NODATA
            ],
            '2' => [
                'variabile' => NODATA,
                'valore' => null,
                'unita_misura' => NODATA,
                'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                'tipo_dato' => NODATA
            ],
            '3' => [
                'variabile' => NODATA,
                'valore' => null,
                'unita_misura' => NODATA,
                'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                'tipo_dato' => NODATA
            ]
        ];
        
        $expected = [
            '0' => [
                'variabile' => NODATA,
                'valore' => NODATA,
                'unita_misura' => NODATA,
                'data_e_ora' => new \DateTime('04/01/2020 23:00:00'),
                'tipo_dato' => NODATA
            ],
            '1' => [
                'variabile' => NODATA,
                'valore' => NODATA,
                'unita_misura' => NODATA,
                'data_e_ora' => new \DateTime('05/01/2020 00:00:00'),
                'tipo_dato' => NODATA
            ],
            '2' => [
                'variabile' => NODATA,
                'valore' => NODATA,
                'unita_misura' => NODATA,
                'data_e_ora' => new \DateTime('05/01/2020 0:30:00'),
                'tipo_dato' => NODATA
            ],
            '3' => [
                'variabile' => NODATA,
                'valore' => NODATA,
                'unita_misura' => NODATA,
                'data_e_ora' => new \DateTime('05/01/2020 01:30:00'),
                'tipo_dato' => NODATA
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
            ],
            'manovra no data' => [
                'dati' => [
                    'unita_misura' => NODATA,
                    'valore' => NODATA
                ],
                'categoria' => 'manovra',
                'expected' => NODATA
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
     * @group index
     * covers eraseDoubleDate()
     */
    public function testEraseDoubleDateSomeDataEquals() : void
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
            'livello valle' => [],
            'manovra' => []
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
            'livello valle' => [],
            'manovra' => []
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
    }
    
    /**
     * @group index
     * covers eraseDoubleDate()
     */
    public function testEraseDoubleDateNoDataEquals() : void
    {
        $dati_acquisiti = [
            'livello monte' => [],
            'livello valle' => [],
            'manovra' => []
        ];
        
        $expected = [
            'livello monte' => [],
            'livello valle' => [],
            'manovra' => []
        ];
        
        $actual = eraseDoubleDate($dati_acquisiti);
        
        $this->assertEquals($expected, $actual);
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
        
        $filePath = debugOnCSV($dati, $fileName);
        
        $this->assertFileExists($filePath);
    }
    
    /**
     * @group test
     * covers getHelpLines()
     */
    public function testGetHelpLinesMultiEquals() : void
    {
        $parameters = [
            "var" => [
                "name" => "variabile",
                "short" => "V",
                "long" => "var",
                "default" => "ALL",
                "options" => [
                    "variables" => [
                        "var1",
                        "var2"
                    ],
                    "costants" => [
                        "ALL"
                    ],
                    "alias" => [
                        "tutti"
                    ]
                ],
                "regex" => "\/^[0-9]{5}([,][0-9]{5})*$\/",
                "descriptions" => [
                    "Esegue il calcolo per ciascuna delle variabili",
                    "<var> o per tutte (ALL). Default ALL."
                ],
                "type" => "group"
            ]
        ];
        
        $sections = [
            "params",
            "default",
            "descriptions"
        ];
        
        $expected = [
            [
                "params" => "-V --var",
                "default" => "[=ALL]",
                "descriptions" => "Esegue il calcolo per ciascuna delle variabili"
            ],
            [
                "params" => "",
                "default" => "",
                "descriptions" => "<var> o per tutte (ALL). Default ALL."
            ]
        ];
        
        $actual = getHelpLines($parameters, $sections);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers getHelpLines()
     */
    public function testGetHelpLinesSingleEquals() : void
    {
        $parameters = [
            "var" => [
                "name" => "variabile",
                "short" => "V",
                "long" => "var",
                "default" => "ALL",
                "options" => [
                    "variables" => [
                        "var1",
                        "var2"
                    ],
                    "costants" => [
                        "ALL"
                    ],
                    "alias" => [
                        "tutti"
                    ]
                ],
                "regex" => "\/^[0-9]{5}([,][0-9]{5})*$\/",
                "descriptions" => [
                    "Esegue il calcolo per ciascuna delle variabili",
                    "<var> o per tutte (ALL). Default ALL."
                ],
                "type" => "group"
            ]
        ];
        
        $sections = [
                "params",
                "descriptions",
                "default"
        ];
        
        $expected = [
            [
                "params" => "-V --var",
                "descriptions" => "Esegue il calcolo per ciascuna delle variabili <var> o per tutte (ALL). Default ALL.",
                "default" => "[=ALL]"
            ]
        ];
        
        $actual = getHelpLines($parameters, $sections);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers getHelpLines()
     */
    public function testGetHelpLinesParametersException() : void
    {
        $parameters = [];
        $sections = [
            "params",
            "default",
            "descriptions"
        ];
        
        $this->expectException(\Exception::class);
        
        getHelpLines($parameters, $sections);
    }
    
    /**
     * @group test
     * covers getHelpLines()
     */
    public function testGetHelpLinesSectionsException() : void
    {
        $parameters = ['pippo'];
        $sections = [];
        
        $this->expectException(\Exception::class);
        
        getHelpLines($parameters, $sections);
    }
    
    /**
     * @group test
     * covers formatParams()
     */
    public function testFormatParamsEquals() : void
    {
        $properties = [
                "short" => "V",
                "long" => "var"
        ];
        
        $expected = "-V --var";
         
        $actual = formatParams($properties);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers formatParams()
     */
    public function testFormatParamsException() : void
    {
        $properties = [
                "short" => "",
                "long" => "va"
        ];
        
        $this->expectException(\Exception::class);
        
        formatParams($properties);
    }
    
    /**
     * @group test
     * covers formatDefault()
     */
    public function testFormatDefaultEquals() : void
    {
        $properties = [
                "default" => "ALL"
        ];
        
        $expected = "[=ALL]";
         
        $actual = formatDefault($properties);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers formatDefault()
     */
    public function testFormatDefaultVoidEquals() : void
    {
        $properties = [
                "default" => ""
        ];
        
        $expected = "";
         
        $actual = formatDefault($properties);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers formatDefault()
     */
    public function testFormatDefaultException() : void
    {
        $properties = [
                "pippo" => "V"
        ];
        
        $this->expectException(\Exception::class);
        
        formatDefault($properties);
    }
    
    /**
     * @group test
     * covers formatDefault()
     */
    public function testFormatDefaultRegexException() : void
    {
        $properties = [
                "default" => "v"
        ];
        
        $this->expectException(\Exception::class);
        
        formatDefault($properties);
    }
    
    /**
     * @group test
     * covers formatVariables()
     */
    public function testFormatVariablesEquals() : void
    {
        $properties = [
            "options" => [
                "variables" => ['30030','30040']
            ]
        ];
        
        $expected = "<30030>,<30040>";
         
        $actual = formatVariables($properties);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers formatVariables()
     */
    public function testFormatVariablesVoidEquals() : void
    {
        $properties = [
            "options" => [
                "variables" => []
            ]
        ];
        
        $expected = "";
         
        $actual = formatVariables($properties);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers formatVariables()
     */
    public function testFormatVariablesException() : void
    {
        $properties = [
            "options" => [
                "pippo" => ['30030','30040']
            ]
        ];
        
        $this->expectException(\Exception::class);
        
        formatVariables($properties);
    }
    
    /**
     * @group test
     * covers formatCostants()
     */
    public function testFormatCostantsEquals() : void
    {
        $properties = [
            "options" => [
                "costants" => ['TRUE','FALSE'],
                "limits" => []
            ]
        ];
        
        $expected = "TRUE|FALSE";
         
        $actual = formatCostants($properties);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers formatCostants()
     */
    public function testFormatCostantsLimitsEquals() : void
    {
        $properties = [
            "options" => [
                "costants" => ['D','W','M','Y'],
                "limits" => ['364','51','11','9']
            ]
        ];
        
        $expected = "[1-364]D|[1-51]W|[1-11]M|[1-9]Y";
         
        $actual = formatCostants($properties);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers formatCostants()
     */
    public function testFormatCostantsVoidEquals() : void
    {
        $properties = [
            "options" => [
                "costants" => [],
                "limits" => []
            ]
        ];
        
        $expected = "";
         
        $actual = formatCostants($properties);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers formatCostants()
     */
    public function testFormatCostantsException() : void
    {
        $properties = [
            "options" => [
                "pippo" => ['TRUE','FALSE']
            ]
        ];
        
        $this->expectException(\Exception::class);
        
        formatCostants($properties);
    }
    
    /**
     * @coversNothing
     */
    public function formatOptionsProvider() : array
    {
        $data = [
            'full' => [
                'properties' => [
                    "options" => [
                        "variables" => ['var1'],
                        "costants" => ['V'],
                        "limits" => []
                    ]
                ],
                'expected' => "<var1>|V"
            ],
            'full multi' => [
                'properties' => [
                    "options" => [
                        "variables" => ['var1','var2'],
                        "costants" => ['V','M','Q'],
                        "limits" => []
                    ]
                ],
                'expected' => "<var1>,<var2>|V|M|Q"
            ],
            'only variables' => [
                'properties' => [
                    "options" => [
                        "variables" => ['var1','var2'],
                        "costants" => [],
                        "limits" => []
                    ]
                ],
                'expected' => "<var1>,<var2>"
            ],
            'only costants' => [
                'properties' => [
                    "options" => [
                        "variables" => [],
                        "costants" => ['V','M','Q'],
                        "limits" => []
                    ]
                ],
                'expected' => "V|M|Q"
            ],
            'no options' => [
                'properties' => [
                    "options" => [
                        "variables" => [],
                        "costants" => [],
                        "limits" => []
                    ]
                ],
                'expected' => ""
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers formatOptions()
     * @dataProvider formatOptionsProvider
     */
    public function testFormatOptionsEquals(array $properties, string $expected) : void
    {
        $actual = formatOptions($properties);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function formatOptionsExceptionsProvider() : array
    {
        $data = [
            'variables exception' => [
                'properties' => [
                    "options" => [
                        "variables" => ['','var2'],
                        "costants" => ['V'],
                        "limits" => []
                    ]
                ]
            ],
            'costants exception1' => [
                'properties' => [
                    "options" => [
                        "variables" => ['var1','var2'],
                        "costants" => ['v','m','q'],
                        "limits" => []
                    ]
                ]
            ],
            'costants exception2' => [
                'properties' => [
                    "options" => [
                        "variables" => ['var1','var2'],
                        "costants" => ['','M','Q'],
                        "limits" => []
                    ]
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers formatOptions()
     * @dataProvider formatOptionsExceptionsProvider
     */
    public function testFormatOptionsExceptions(array $parameters) : void
    {
        $this->expectException(\Exception::class);
        
        formatOptions($parameters);
    }
    
    /**
     * @group test
     * covers formatDescriptions()
     */
    public function testFormatDescriptionsEquals() : void
    {
        $properties = [
            "descriptions" => [
                "pippo",
                "pluto",
                "paperino"
            ]
        ];
        
        $expected = "pippo pluto paperino";
         
        $actual = formatDescriptions($properties);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers formatDescriptions()
     */
    public function testFormatDescriptionsVoidEquals() : void
    {
        $properties = [
            "descriptions" => []
        ];
        
        $expected = "";
         
        $actual = formatDescriptions($properties);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers formatDescriptions()
     */
    public function testFormatDescriptionsException() : void
    {
        $properties = [
            "options" => [
                "pippo",
                "pluto",
                "paperino"
            ]
        ];
        
        $this->expectException(\Exception::class);
        
        formatDescriptions($properties);
    }
    
    /**
     * @group test
     * covers formatShort()
     */
    public function testFormatShortEquals() : void
    {
        $properties = [
            "short" => "h"
        ];
        
        $expected = "-h";
         
        $actual = formatShort($properties);
        
        $this->assertEquals($expected, $actual);
    }
      
    /**
     * @group test
     * covers formatShort()
     */
    public function testFormatShortException() : void
    {
        $properties = [
            "short" => "ha"
        ];
        
        $this->expectException(\Exception::class);
        
        formatShort($properties);
    }
    
    /**
     * @group test
     * covers formatLong()
     */
    public function testFormatLongEquals() : void
    {
        $properties = [
            "long" => "version"
        ];
        
        $expected = "--version";
         
        $actual = formatLong($properties);
        
        $this->assertEquals($expected, $actual);
    }
      
    /**
     * @group test
     * covers formatLong()
     */
    public function testFormatLongException() : void
    {
        $properties = [
            "long" => "to"
        ];
        
        $this->expectException(\Exception::class);
        
        formatLong($properties);
    }
    
    /**
     * @group test
     * covers formatPardef()
     */
    public function testFormatPardefEquals() : void
    {
        $properties = [
            "short" => "f",
            "long" => "field",
            "default" => "V"
        ];
        
        $expected = "-f --field[=V]";
         
        $actual = formatPardef($properties);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers formatPardef()
     */
    public function testFormatPardefVoidEquals() : void
    {
        $properties = [
            "short" => "f",
            "long" => "field",
            "default" => ""
        ];
        
        $expected = "-f --field";
         
        $actual = formatPardef($properties);
        
        $this->assertEquals($expected, $actual);
    }
      
    /**
     * @group test
     * covers formatPardef()
     */
    public function testFormatPardefException() : void
    {
        $properties = [
            "short" => "f",
            "long" => "field",
            "default" => "v"
        ];
        
        $this->expectException(\Exception::class);
        
        formatPardef($properties);
    }
    
    /**
     * @group test
     * covers fillLineSections()
     */
    public function testFillLineSectionsEquals() : void
    {
        $properties = [
            "short" => "V",
            "long" => "var",
            "default" => "ALL",
            "options" => [
                "variables" => [
                    "var1",
                    "var2"
                ],
                "costants" => [
                    "ALL",
                    "TRUE"
                ],
                "limits" => []
            ],
            "descriptions" => [
                "Esegue il calcolo per ciascuna delle variabili",
                "<var> o per tutte (ALL). Default ALL."
            ]
        ];
        
        $sections = [
            "short",
            "long",
            "params",
            "default",
            "pardef",
            "variables",
            "costants",
            "options",
            "descriptions"
        ];
        
        $expected = [
            "-V",
            "--var",
            "-V --var",
            "[=ALL]",
            "-V --var[=ALL]",
            "<var1>,<var2>",
            "ALL|TRUE",
            "<var1>,<var2>|ALL|TRUE",
            "Esegue il calcolo per ciascuna delle variabili <var> o per tutte (ALL). Default ALL."
        ];
         
        $actual = fillLineSections($properties, $sections);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers fillLineSections()
     */
    public function testFillLineSectionsSecExceptions() : void
    {
        $properties = [
            "short" => "V",
            "long" => "var",
            "default" => "ALL",
            "options" => [
                "variables" => [
                    "var1",
                    "var2"
                ],
                "costants" => [
                    "ALL"
                ]
            ],
            "descriptions" => [
                "Esegue il calcolo per ciascuna delle variabili",
                "<var> o per tutte (ALL). Default ALL."
            ]
        ];
        
        $sections = ["pippo"];
        
        $this->expectException(\Exception::class);
        
        fillLineSections($properties, $sections);
    }
    
    /**
     * @group test
     * covers fillLineSections()
     */
    public function testFillLineSectionsProExceptions() : void
    {
        $properties = [];
        
        $sections = [
            "short",
            "long",
            "params",
            "default",
            "pardef",
            "variables",
            "costants",
            "options",
            "descriptions"
        ];
        
        $this->expectException(\Exception::class);
        
        fillLineSections($properties, $sections);
    }
    
    /**
     * @group test
     * covers getMaxLenght()
     */
    public function testGetMaxLenghtEquals() : void
    {
        $help = [
            [
                "params" => "-V --var",
                "default" => "[=ALL]",
                "descriptions" => "Esegue il calcolo per ciascuna delle variabili"
            ],
            [
                "params" => "",
                "default" => "",
                "descriptions" => "<var> o per tutte (ALL). Default ALL."
            ]
        ];
        
        $key = "descriptions";
        
        $expected = 46;
         
        $actual = getMaxLenght($help, $key);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers getMaxLenght()
     */
    public function testGetMaxLenghtKeyExceptions() : void
    {
        $help = [
            [
                "params" => "-V --var",
                "default" => "[=ALL]",
                "descriptions" => "Esegue il calcolo per ciascuna delle variabili"
            ],
            [
                "params" => "",
                "default" => "",
                "descriptions" => "<var> o per tutte (ALL). Default ALL."
            ]
        ];
        
        $key = "pippo";
        
        $this->expectException(\Exception::class);
        
        getMaxLenght($help, $key);
    }
    
    /**
     * @group test
     * covers getMaxLenght()
     */
    public function testGetMaxLenghtArrayExceptions() : void
    {
        $help = [];
        
        $key = "descriptions";
        
        $this->expectException(\Exception::class);
        
        getMaxLenght($help, $key);
    }
    
    /**
     * @group test
     * covers getMaxLenght()
     */
    public function testSetConsoleEquals() : void
    {
        $help = [
            "global" => [
                "sections" => [
                    "short",
                    "long",
                    "params",
                    "default",
                    "pardef",
                    "variables",
                    "costants",
                    "options",
                    "descriptions"
                ],
                "offset" => "2"
            ],
            "parameters" => [
                "help" => [
                    "short" => "h",
                    "long" => "help",
                    "default" => "",
                    "options" => [
                        "variables" => [],
                        "costants" => [],
                        "limits" => []
                    ],
                    "descriptions" => [
                        "Stampa questo help."
                    ]
                ],
                "var" => [
                    "short" => "V",
                    "long" => "var",
                    "default" => "ALL",
                    "options" => [
                        "variables" => [
                            "var1",
                            "var2"
                        ],
                        "costants" => [
                            "ALL"
                        ],
                        "limits" => []
                    ],
                    "descriptions" => [
                        "Esegue il calcolo per ciascuna delle variabili",
                        "<var> o per tutte (ALL). Default ALL."
                    ],
                ]
            ]
        ];
        
        $expected = '  -h  --help  -h --help          -h --help                                              Stampa questo help.' . PHP_EOL;
        $expected .= '  -V  --var   -V --var   [=ALL]  -V --var[=ALL]  <var1>,<var2>  ALL  <var1>,<var2>|ALL  Esegue il calcolo per ciascuna delle variabili' . PHP_EOL;
        $expected .= '                                                                                        <var> o per tutte (ALL). Default ALL.' . PHP_EOL;
         
        $actual = setConsole($help, PHP_EOL);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers setConsole()
     */
    public function testSetConsoleExceptions() : void
    {
        $help = [];
        
        $this->expectException(\Exception::class);
        
        setConsole($help, PHP_EOL);
    }
    
    /**
     * @group test
     * covers setHeader()
     */
    public function testSetHeaderEquals() : void
    {
        $composer = [
            "description" => "Applicazione",
            "version" => "1.2.0",
            "authors" => [
                [
                    "name" => "Vania Carta"
                ]
            ],
        ];
        
        $expected = 'scarichi 1.2.0 by Vania Carta and contributors';
         
        $actual = setHeader($composer);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers setHeader()
     */
    public function testSetHeaderExceptions() : void
    {
        $composer = [];
        
        $this->expectException(\Exception::class);
        
        setHeader($composer);
    }
    
    /**
     * @coverNothing
     */
    public function setMsgProvider() : array
    {
        $composer = [
            "description" => "Applicazione",
            "version" => "1.2.0",
            "authors" => [
                [
                    "name" => "Vania Carta"
                ]
            ],
        ];
        
        $help = [
            "command" => "php",
            "global" => [
                "sections" => [
                    "pardef",
                    "options",
                    "descriptions"
                ],
                "offset" => "2"
            ],
            "parameters" => [
                "help" => [
                    "name" => "help",
                    "short" => "h",
                    "long" => "help",
                    "default" => "",
                    "options" => [
                        "variables" => [],
                        "costants" => [],
                        "limits" => []
                    ],
                    "descriptions" => [
                        "Stampa questo help."
                    ],
                    "type" => "single"
                ],
                "var" => [
                    "name" => "variabile",
                    "short" => "V",
                    "long" => "var",
                    "default" => "ALL",
                    "options" => [
                        "variables" => [
                            "var1",
                            "var2"
                        ],
                        "costants" => [
                            "ALL"
                        ],
                        "limits" => []
                    ],
                    "descriptions" => [
                        "Esegue il calcolo per ciascuna delle variabili",
                        "<var> o per tutte (ALL). Default ALL."
                    ],
                    "type" => "group"
                ]
            ]
        ];
        
        $data = [
            "equals" => [
                "composer" => $composer,
                "help" => $help
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers setMsgHtml()
     * @dataProvider setMsgProvider
     */
    public function testSetMsgHtmlContainsString(array $composer, array $help) : void
    {
        $expected = '[user@localhost ~]# php -h<br/>';
         
        $actual = setMsgHtml($composer, $help, '<br/>');
        
        $this->assertStringContainsString($expected, $actual);
    }
    
    /**
     * @group test
     * covers setMsgHtml()
     */
    public function testSetMsgHtmlComposerExceptions() : void
    {
        $composer = [];
        $help = ['some values'];
        
        $this->expectException(\Exception::class);
        
        setMsgHtml($composer, $help, '<br/>');
    }
    
    /**
     * @group test
     * covers setMsgHtml()
     */
    public function testSetMsgHtmlHelpExceptions() : void
    {
        $composer = ['some values'];
        $help = [];
        
        $this->expectException(\Exception::class);
        
        setMsgHtml($composer, $help, '<br/>');
    }
    
    /**
     * @group test
     * covers setMsgVersion()
     * @dataProvider setMsgProvider
     */
    public function testSetMsgVersionContainsString(array $composer, array $help) : void
    {
        $expected = setHeader($composer) . PHP_EOL;
         
        $actual = setMsgVersion($composer, $help, PHP_EOL);
        
        $this->assertStringContainsString($expected, $actual);
    }
    
    /**
     * @group test
     * covers setMsgVersion()
     */
    public function testSetMsgVersionComposerExceptions() : void
    {
        $composer = [];
        $help = ['some values'];
        
        $this->expectException(\Exception::class);
        
        setMsgVersion($composer, $help, PHP_EOL);
    }
    
    /**
     * @group test
     * covers setMsgVersion()
     */
    public function testSetMsgVersionHelpExceptions() : void
    {
        $composer = ['some values'];
        $help = [];
        
        $this->expectException(\Exception::class);
        
        setMsgVersion($composer, $help, PHP_EOL);
    }
    
    /**
     * @group test
     * covers setMsgDefault()
     * @dataProvider setMsgProvider
     */
    public function testSetMsgDefaultContainsString(array $composer, array $help) : void
    {
        $expected = "php -V ALL" . PHP_EOL;
         
        $actual = setMsgDefault($composer, $help, PHP_EOL);
        
        $this->assertStringContainsString($expected, $actual);
    }
    
    /**
     * @group test
     * covers setMsgDefault()
     */
    public function testSetMsgDefaultComposerExceptions() : void
    {
        $composer = [];
        $help = ['some values'];
        
        $this->expectException(\Exception::class);
        
        setMsgDefault($composer, $help, PHP_EOL);
    }
    
    /**
     * @group test
     * covers setMsgDefault()
     */
    public function testSetMsgDefaultHelpExceptions() : void
    {
        $composer = ['some values'];
        $help = [];
        
        $this->expectException(\Exception::class);
        
        setMsgDefault($composer, $help, PHP_EOL);
    }
    
    /**
     * @group test
     * covers setMsgHelp()
     * @dataProvider setMsgProvider
     */
    public function testSetMsgHelpContainsString(array $composer, array $help) : void
    {
        $expected1 = "Applicazione" . PHP_EOL;
        $expected2 = "  php [-h]" . PHP_EOL;
        $expected3 = "  php -V [options]" . PHP_EOL;
         
        $actual = setMsgHelp($composer, $help, PHP_EOL);
        
        $this->assertStringContainsString($expected1, $actual);
        $this->assertStringContainsString($expected2, $actual);
        $this->assertStringContainsString($expected3, $actual);
    }
    
    /**
     * @group test
     * covers setMsgHelp()
     */
    public function testSetMsgHelpComposerExceptions() : void
    {
        $composer = [];
        $help = ['some values'];
        
        $this->expectException(\Exception::class);
        
        setMsgHelp($composer, $help, PHP_EOL);
    }
    
    /**
     * @group test
     * covers setMsgDefault()
     */
    public function testSetMsgHelpExceptions() : void
    {
        $composer = ['some values'];
        $help = [];
        
        $this->expectException(\Exception::class);
        
        setMsgHelp($composer, $help, PHP_EOL);
    }
    
    /**
     * @group test
     * covers setMsgOk()
     * @dataProvider setMsgProvider
     */
    public function testSetMsgOkContainsString(array $composer, array $help) : void
    {
        $expected = setHeader($composer) . PHP_EOL;
         
        $actual = setMsgOk($composer, $help, PHP_EOL);
        
        $this->assertStringContainsString($expected, $actual);
    }
    
    /**
     * @group test
     * covers setMsgOk()
     */
    public function testSetMsgOkComposerExceptions() : void
    {
        $composer = [];
        $help = ['some values'];
        
        $this->expectException(\Exception::class);
        
        setMsgOk($composer, $help, PHP_EOL);
    }
    
    /**
     * @group test
     * covers setMsgOk()
     */
    public function testSetMsgOkHelpExceptions() : void
    {
        $composer = ['some values'];
        $help = [];
        
        $this->expectException(\Exception::class);
        
        setMsgOk($composer, $help, PHP_EOL);
    }
    
    /**
     * @group test
     * covers setMsgError()
     * @dataProvider setMsgProvider
     */
    public function testSetMsgErrorContainsString(array $composer, array $help) : void
    {
        $expected = 'Per info digitare: php -h' . PHP_EOL;
         
        $actual = setMsgError($composer, $help, PHP_EOL);
        
        $this->assertStringContainsString($expected, $actual);
    }
    
    /**
     * @group test
     * covers setMsgError()
     */
    public function testSetMsgErrorComposerExceptions() : void
    {
        $composer = [];
        $help = ['some values'];
        
        $this->expectException(\Exception::class);
        
        setMsgError($composer, $help, PHP_EOL);
    }
    
    /**
     * @group test
     * covers setMsgError()
     */
    public function testSetMsgErrorHelpExceptions() : void
    {
        $composer = ['some values'];
        $help = [];
        
        $this->expectException(\Exception::class);
        
        setMsgError($composer, $help, PHP_EOL);
    }
    
    /**
     * @coverNothing
     */
    public function getMessageProvider() : array
    {
        $composer = [
            "description" => "Applicazione",
            "version" => "1.2.0",
            "authors" => [
                [
                    "name" => "Vania Carta"
                ]
            ],
        ];
        
        $help = [
            "command" => "php",
            "global" => [
                "sections" => [
                    "pardef",
                    "options",
                    "descriptions"
                ],
                "offset" => "2"
            ],
            "parameters" => [
                "help" => [
                    "name" => "help",
                    "short" => "h",
                    "long" => "help",
                    "default" => "",
                    "options" => [
                        "variables" => [],
                        "costants" => [],
                        "limits" => []
                    ],
                    "descriptions" => [
                        "Stampa questo help."
                    ],
                    "type" => "single"
                ],
                "var" => [
                    "name" => "variabile",
                    "short" => "V",
                    "long" => "var",
                    "default" => "ALL",
                    "options" => [
                        "variables" => [
                            "var1",
                            "var2"
                        ],
                        "costants" => [
                            "ALL"
                        ],
                        "limits" => []
                    ],
                    "descriptions" => [
                        "Esegue il calcolo per ciascuna delle variabili",
                        "<var> o per tutte (ALL). Default ALL."
                    ],
                    "type" => "group"
                ]
            ]
        ];
        
        $data = [
            "redirect" => [
                "composer" => $composer,
                "help" => $help,
                "type" => "html"
            ],
            "version" => [
                "composer" => $composer,
                "help" => $help,
                "type" => "version"
            ],
            "default" => [
                "composer" => $composer,
                "help" => $help,
                "type" => "default"
            ],
            "help" => [
                "composer" => $composer,
                "help" => $help,
                "type" => "help"
            ],
            "ok" => [
                "composer" => $composer,
                "help" => $help,
                "type" => "ok"
            ],
            "error" => [
                "composer" => $composer,
                "help" => $help,
                "type" => "error"
            ]
            
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers getMessage()
     * @dataProvider getMessageProvider
     */
    public function testGetMessageIsString(array $composer, array $help, string $type) : void
    {
        $actual = getMessage($composer, $help, $type);
        
        $this->assertIsString($actual);
    }
    
    /**
     * @group test
     * covers getMessage()
     */
    public function testGetMessageComposerExceptions() : void
    {
        $composer = [];
        $help = ['some values'];
        $type = 'ok';
        
        $this->expectException(\Exception::class);
        
        getMessage($composer, $help, $type);
    }
    
    /**
     * @group test
     * covers getMessage()
     */
    public function testGetMessageHelpExceptions() : void
    {
        $composer = ['some values'];
        $help = [];
        $type = 'ok';
        
        $this->expectException(\Exception::class);
        
        getMessage($composer, $help, $type);
    }
    
    /**
     * @group test
     * covers getMessage()
     */
    public function testGetMessageTypeExceptions() : void
    {
        $composer = ['some values'];
        $help = ['some other values'];
        $type = 'pippo';
        
        $this->expectException(\Exception::class);
        
        getMessage($composer, $help, $type);
    }
    
    /**
     * @coversNothing
     */
    public function PropertyToStringProvider() : array
    {
        $parameters = [
            "var" => [
                "name" => "variabile",
                "short" => "V",
                "long" => "var",
                "default" => "ALL",
                "options" => [
                    "variables" => [
                        "var1",
                        "var2"
                    ],
                    "costants" => [
                        "ALL"
                    ],
                    "alias" => [
                        "tutti"
                    ]
                ],
                "regex" => "\/^[0-9]{5}([,][0-9]{5})*$\/",
                "descriptions" => [
                    "Esegue il calcolo per ciascuna delle variabili",
                    "<var> o per tutte (ALL). Default ALL."
                ],
                "type" => "group"
            ]
        ];

        $data = [
            'no param' => [
                'parameters' => $parameters,
                'paramName' => 'pippo',
                'propertyName' => 'default',
                'expected' => ''
            ],
            'default' => [
                'parameters' => $parameters,
                'paramName' => 'var',
                'propertyName' => 'default',
                'expected' => 'ALL'
            ],
            'options' => [
                'parameters' => $parameters,
                'paramName' => 'var',
                'propertyName' => 'options',
                'expected' => 'var1 var2 ALL tutti'
            ],
            'descriptions' => [
                'parameters' => $parameters,
                'paramName' => 'var',
                'propertyName' => 'descriptions',
                'expected' => 'Esegue il calcolo per ciascuna delle variabili <var> o per tutte (ALL). Default ALL.'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers propertyToString()
     * @dataProvider propertyToStringProvider
     */
    public function testPropertyToStringEquals(array $parameters, string $paramName, string $propertyName, string $expected) : void
    {
        $actual = propertyToString($parameters, $paramName, $propertyName);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers propertyToString()
     */
    public function testPropertyToStringException() : void
    {
        $parameters = [];
        $paramName = 'var';
        $propertyName = 'default';
        
        $this->expectException(\Exception::class);
        
        propertyToString($parameters, $paramName, $propertyName);
    }

    /**
     * @coversNothing
     */
    public function getPropertiesProvider() : array
    {
        $parameters = [
            "dummy" => [
                "name" => "dummy",
                "short" => "x",
                "pippo" => "dummy",
                "default" => "",
                "options" => [
                    "variables" => [],
                    "costants" => []
                ],
                "descriptions" => [
                    "Stampa questo help."
                ],
                "type" => "single"
            ],
            "help" => [
                "name" => "help",
                "short" => "h",
                "long" => "help",
                "default" => "",
                "options" => [
                    "variables" => [],
                    "costants" => []
                ],
                "descriptions" => [
                    "Stampa questo help."
                ],
                "type" => "single"
            ],
            "variabile" => [
                "name" => "variabile",
                "short" => "V",
                "long" => "var",
                "default" => "ALL",
                "options" => [
                    "variables" => [
                        "var1",
                        "var2"
                    ],
                    "costants" => [
                        "ALL"
                    ],
                    "alias" => [
                        "tutti"
                    ]
                ],
                "regex" => "\/^[0-9]{5}([,][0-9]{5})*$\/",
                "descriptions" => [
                    "Esegue il calcolo per ciascuna delle variabili",
                    "<var> o per tutte (ALL). Default ALL."
                ],
                "type" => "group"
            ],
            "variabile2" => [
                "name" => "variabile n2",
                "short" => "v",
                "long" => "var2",
                "default" => "ALL",
                "options" => [
                    "variables" => [
                        "var1",
                        "var2"
                    ],
                    "costants" => [
                        "ALL"
                    ],
                    "alias" => [
                        "tutti"
                    ]
                ],
                "regex" => "\/^[0-9]{5}([,][0-9]{5})*$\/",
                "descriptions" => [
                    "Esegue il calcolo per ciascuna delle variabili",
                    "<var> o per tutte (ALL). Default ALL."
                ],
                "type" => "group"
            ]
        ];

        $data = [
            'no property name' => [
                'parameters' => $parameters,
                'propertyName' => 'null',
                'assoc' => null,
                'filterInField' => null,
                'filterInValue' => null,
                'filterOutField' => null,
                'filterOutValue' => null,
                'prefix' => null,
                'expected' => []
            ],
            'dummy' => [
                'parameters' => $parameters,
                'propertyName' => 'pippo',
                'assoc' => null,
                'filterInField' => null,
                'filterInValue' => null,
                'filterOutField' => null,
                'filterOutValue' => null,
                'prefix' => null,
                'expected' => [
                    'dummy'
                ]
            ],
            'base' => [
                'parameters' => $parameters,
                'propertyName' => 'long',
                'assoc' => null,
                'filterInField' => null,
                'filterInValue' => null,
                'filterOutField' => null,
                'filterOutValue' => null,
                'prefix' => null,
                'expected' => [
                    'help',
                    'var',
                    'var2'
                ]
            ],
            'fieldIn' => [
                'parameters' => $parameters,
                'propertyName' => 'long',
                'assoc' => null,
                'filterInField' => 'type',
                'filterInValue' => 'group',
                'filterOutField' => null,
                'filterOutValue' => null,
                'prefix' => null,
                'expected' => [
                    'var',
                    'var2'
                ]
            ],
            'fieldOut' => [
                'parameters' => $parameters,
                'propertyName' => 'long',
                'assoc' => null,
                'filterInField' => null,
                'filterInValue' => null,
                'filterOutField' => 'type',
                'filterOutValue' => 'single',
                'prefix' => null,
                'expected' => [
                    'var',
                    'var2'
                ]
            ],
            'inAndOut' => [
                'parameters' => $parameters,
                'propertyName' => 'long',
                'assoc' => null,
                'filterInField' => 'type',
                'filterInValue' => 'group',
                'filterOutField' => 'long',
                'filterOutValue' => 'var2',
                'prefix' => null,
                'expected' => [
                    'var'
                ]
            ],
            'prefix' => [
                'parameters' => $parameters,
                'propertyName' => 'long',
                'assoc' => null,
                'filterInField' => null,
                'filterInValue' => null,
                'filterOutField' => null,
                'filterOutValue' => null,
                'prefix' => '*',
                'expected' => [
                    '*help',
                    '*var',
                    '*var2'
                ]
            ],
            'fieldIn wrong' => [
                'parameters' => $parameters,
                'propertyName' => 'name',
                'assoc' => null,
                'filterInField' => 'pluto',
                'filterInValue' => 'paperino',
                'filterOutField' => null,
                'filterOutValue' => null,
                'prefix' => null,
                'expected' => [
                    'dummy',
                    'help',
                    'variabile',
                    'variabile n2'
                ]
            ],
            'fieldOut wrong' => [
                'parameters' => $parameters,
                'propertyName' => 'name',
                'assoc' => null,
                'filterInField' => null,
                'filterInValue' => null,
                'filterOutField' => 'pluto',
                'filterOutValue' => 'paperino',
                'prefix' => null,
                'expected' => [
                    'dummy',
                    'help',
                    'variabile',
                    'variabile n2'
                ]
            ],
            'In wrong Out right' => [
                'parameters' => $parameters,
                'propertyName' => 'name',
                'assoc' => null,
                'filterInField' => 'pluto',
                'filterInValue' => 'paperino',
                'filterOutField' => 'type',
                'filterOutValue' => 'group',
                'prefix' => null,
                'expected' => [
                    'dummy',
                    'help'
                ]
            ],
            'In right Out wrong' => [
                'parameters' => $parameters,
                'propertyName' => 'name',
                'assoc' => null,
                'filterInField' => 'type',
                'filterInValue' => 'single',
                'filterOutField' => 'pluto',
                'filterOutValue' => 'paperino',
                'prefix' => null,
                'expected' => [
                    'dummy',
                    'help'
                ]
            ],
            'eraser' => [
                'parameters' => $parameters,
                'propertyName' => 'name',
                'assoc' => null,
                'filterInField' => 'type',
                'filterInValue' => 'single',
                'filterOutField' => 'type',
                'filterOutValue' => 'single',
                'prefix' => '-',
                'expected' => []
            ],
            'assoc' => [
                'parameters' => $parameters,
                'propertyName' => 'short',
                'assoc' => true,
                'filterInField' => null,
                'filterInValue' => null,
                'filterOutField' => null,
                'filterOutValue' => null,
                'prefix' => null,
                'expected' => [
                    'dummy' => 'x',
                    'help' => 'h',
                    'variabile' => 'V',
                    'variabile2' => 'v'
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers getProperties()
     * @dataProvider getPropertiesProvider
     */
    public function testGetPropertiesEquals(array $parameters, string $propertyName, ?bool $assoc, ?string $filterInName, ?string $filterInValue, ?string $filterOutName, ?string $filterOutValue, ?string $prefix, array $expected) : void
    {
        $actual = getProperties($parameters, $propertyName, $assoc, $filterInName, $filterInValue, $filterOutName, $filterOutValue, $prefix);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers getProperties()
     */
    public function testGetPropertiesException() : void
    {
        $parameters = [];
        $propertyName = 'default';
        
        $this->expectException(\Exception::class);
        
        getProperties($parameters, $propertyName);
    }
    
    /**
     * @coversNothing
     */
    public function filterPropertiesProvider() : array
    {
        $parameters = [
           "help" => [
                "name" => "help",
                "type" => "single"
            ],
            "variabile" => [
                "name" => "variabile",
                "type" => "group"
            ],
            "variabile2" => [
                "name" => "variabile",
                "type" => "group"
            ]
        ];

        $data = [
            'include' => [
                'parameters' => $parameters,
                'field' => 'type',
                'value' => 'group',
                'include' => true,
                'expected' => [
                    "variabile" => [
                        "name" => "variabile",
                        "type" => "group"
                    ],
                    "variabile2" => [
                        "name" => "variabile",
                        "type" => "group"
                    ]
                ]
            ],
            'exclude' => [
                'parameters' => $parameters,
                'field' => 'type',
                'value' => 'group',
                'include' => false,
                'expected' => [
                    "help" => [
                        "name" => "help",
                        "type" => "single"
                    ]
                ]
            ],
            'include null' => [
                'parameters' => $parameters,
                'field' => null,
                'value' => null,
                'include' => true,
                'expected' => [
                    "help" => [
                        "name" => "help",
                        "type" => "single"
                    ],
                    "variabile" => [
                        "name" => "variabile",
                        "type" => "group"
                    ],
                    "variabile2" => [
                        "name" => "variabile",
                        "type" => "group"
                    ]
                ]
            ],
            'exclude null' => [
                'parameters' => $parameters,
                'field' => null,
                'value' => null,
                'include' => false,
                'expected' => [
                    "help" => [
                        "name" => "help",
                        "type" => "single"
                    ],
                    "variabile" => [
                        "name" => "variabile",
                        "type" => "group"
                    ],
                    "variabile2" => [
                        "name" => "variabile",
                        "type" => "group"
                    ]
                ]
            ],
            'no field' => [
                'parameters' => $parameters,
                'field' => 'pippo',
                'value' => 'pluto',
                'include' => true,
                'expected' => [
                    "help" => [
                        "name" => "help",
                        "type" => "single"
                    ],
                    "variabile" => [
                        "name" => "variabile",
                        "type" => "group"
                    ],
                    "variabile2" => [
                        "name" => "variabile",
                        "type" => "group"
                    ]
                ]
            ],
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers filterProperties()
     * @dataProvider filterPropertiesProvider
     */
    public function testFilterPropertiesEquals(array $parameters, ?string $field, ?string $value, bool $include, array $expected) : void
    {
        $actual = filterProperties($parameters, $field, $value, $include);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers filterProperties()
     */
    public function testFilterPropertiesException() : void
    {
        $parameters = [];
        $field = 'default';
        $value = 'ALL';
        $include = true;
        
        $this->expectException(\Exception::class);
        
        filterProperties($parameters, $field, $value, $include);
    }
    
    /**
     * @group test
     * covers selectAllQuery()
     */
    public function testSelectAllQueryEquals() : void
    {
        $db = 'SSCP_data';
        $query = 'query_variabili_ALL';
        $expecteds = ['30030', '30040', '30041'];
               
        $actuals = selectAllQuery($db, $query);
        
        foreach ($expecteds as $key => $expected) {
            $this->assertEquals($expected, $actuals[$key]);
        }
    }
    
    /**
     * @group test
     * covers selectAllQuery()
     */
    public function testSelectAllQueryException() : void
    {
        $db = 'dbutz';
        $query = 'query_variabili_ALL';
        
        $this->expectException(\Exception::class);
        
        selectAllQuery($db, $query);
    }
    
    /**
     * @coversNothing
     */
    public function allParameterSetProvider() : array
    {
        $parameters = [
           "help" => [
               "short" => "h",
               "long" => "help",
               "type" => "single"
            ],
            "var" => [
               "short" => "V",
               "long" => "var",
               "type" => "group"
            ],
            "datefrom" => [
               "short" => "f",
               "long" => "datefrom",
               "type" => "group"
            ],
            "dateto" => [
               "short" => "t",
               "long" => "dateto",
               "type" => "group"
            ],
            "field" => [
               "short" => "c",
               "long" => "campo",
               "type" => "group"
            ],
            "full" => [
               "short" => "n",
               "long" => "nozero",
               "type" => "group"
            ],
        ];

        $data = [
            'no arg' => [
                'parameters' => $parameters,
                'argv' => [
                    'scarichi.php'
                ],
                'expected' => false
            ],
            'same arg' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php', '-V', '-f', '-t', '-c'],
                'expected' => false
            ],
            'same mixed arg' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php', '--var', '-f', '-t', '--campo'],
                'expected' => false
            ],
            'all short arg' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php', '-V', '-f', '-t', '-c', '-n'],
                'expected' => true
            ],
            'all mixed arg' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php', '--var', '-f', '--dateto', '-c', '--nozero'],
                'expected' => true
            ],
            'all mixed with val' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php', '--var', '30030,30040', '-f', 'YEAR', '--dateto', '01/01/2020', '-c', '--nozero'],
                'expected' => true
            ],
            'sovra arg' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php', '--var', '30030,30040', '-f', '--dateto', '-c', '--nozero', '-V', '--datefrom', '01/01/2020'],
                'expected' => true
            ],
            'bad arg' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php', '-v', '30030,30040', '--f', '-dateto', '01/01/2020', '-c', '--nozero'],
                'expected' => false
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers allParameterSet()
     * @dataProvider allParameterSetProvider
     */
    public function testAllParameterSetEquals(array $parameters, array $arguments, bool $expected) : void
    {
        $actual = allParameterSet($parameters, $arguments);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers allParameterSet()
     */
    public function testAllParameterSetException() : void
    {
        $parameters = [];
        $arguments = ['scarichi.php', '-V', '-f', '-t', '-c', '-n'];
        
        $this->expectException(\Exception::class);
        
        allParameterSet($parameters, $arguments);
    }
    
    /**
     * @coversNothing
     */
    public function checkCliVarProvider() : array
    {
        $data = [
            'base' => [
                'value' => '30030',
                'expected' => ['30030']
            ],
            'multi' => [
                'value' => '30030,30040',
                'expected' => ['30030','30040']
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers checkCliVar()
     * @dataProvider checkCliVarProvider
     */
    public function testCheckCliVarEquals(string $value, array $expected) : void
    {
        $actual = checkCliVar($value);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers checkCliVar()
     */
    public function testCheckCliVarException() : void
    {
        $value = '40000';
        
        $this->expectException(\Exception::class);
        
        checkCliVar($value);
    }
    
    /**
     * @group test
     * covers checkCliDatefrom()
     */
    public function testCheckCliDatefromEquals() : void
    {
        $value = '31/01/2020';
        $expected = ['2020-01-31'];
        
        $actual = checkCliDatefrom($value);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers checkCliDatefrom()
     */
    public function testCheckCliDatefromCostantEquals() : void
    {
        $value = '364D';
        $expected = ['364D'];
        
        $actual = checkCliDatefrom($value);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers checkCliDatefrom()
     */
    public function testCheckCliDatefromException() : void
    {
        $value = '92/01/2020';
        
        $this->expectException(\Exception::class);
        
        checkCliDatefrom($value);
    }
    
    /**
     * @group test
     * covers checkCliDateto()
     */
    public function testCheckCliDatetoEquals() : void
    {
        $value = '31/01/2020';
        $expected = ['2020-01-31'];
        
        $actual = checkCliDateto($value);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers checkCliDateto()
     */
    public function testCheckCliDatetoException() : void
    {
        $value = '2020-01-31';
        
        $this->expectException(\Exception::class);
        
        checkCliDateto($value);
    }
    
    /**
     * @coversNothing
     */
    public function checkCliFieldProvider() : array
    {
        $data = [
            'volume' => [
                'value' => 'V',
                'expected' => ['volume']
            ],
            'livello' => [
                'value' => 'L',
                'expected' => ['livello']
            ],
            'poertata' => [
                'value' => 'Q',
                'expected' => ['portata']
            ],
            'altezza' => [
                'value' => 'H',
                'expected' => ['altezza']
            ],
            'delta' => [
                'value' => 'D',
                'expected' => ['delta']
            ],
            'media' => [
                'value' => 'M',
                'expected' => ['media']
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers checkCliField()
     * @dataProvider checkCliFieldProvider
     */
    public function testCheckCliFieldEquals(string $value, array $expected) : void
    {
        $actual = checkCliField($value);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers checkCliField()
     */
    public function testCheckCliFieldException() : void
    {
        $value = 'K';
        
        $this->expectException(\Exception::class);
        
        checkCliField($value);
    }
    
    /**
     * @coversNothing
     */
    public function checkCliFullProvider() : array
    {
        $data = [
            'true' => [
                'value' => 'TRUE',
                'expected' => [true]
            ],
            'false' => [
                'value' => 'FALSE',
                'expected' => [false]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers checkCliFull()
     * @dataProvider checkCliFullProvider
     */
    public function testCheckCliFullEquals(string $value, array $expected) : void
    {
        $actual = checkCliFull($value);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers checkCliFull()
     */
    public function testCheckCliFullException() : void
    {
        $value = 'pippo';
        
        $this->expectException(\Exception::class);
        
        checkCliFull($value);
    }
    
    /**
     * @coversNothing
     */
    public function checkParameterProvider() : array
    {
        $data = [
            "var base" => [
               "name" => "var",
               "value" => "30030",
               "regex" => "/^[0-9]{5}([,][0-9]{5})*$/",
               "default" => "ALL",
               "expected" => ['30030']
            ],
            "var multi" => [
               "name" => "var",
               "value" => "30030,30040",
               "regex" => "/^[0-9]{5}([,][0-9]{5})*$/",
               "default" => "ALL",
               "expected" => ['30030','30040']
            ],
            "var default" => [
               "name" => "var",
               "value" => "ALL",
               "regex" => "/^[0-9]{5}([,][0-9]{5})*$/",
               "default" => "ALL",
               "expected" => ['ALL']
            ],
            "datefrom base" => [
               "name" => "datefrom",
               "value" => "31/12/2020",
               "regex" => "/^[0-9]{2}[\/][0-9]{2}[\/][0-9]{4}$/",
               "default" => "YEAR",
               "expected" => ['2020-12-31']
            ],
            "datefrom default" => [
               "name" => "datefrom",
               "value" => "YEAR",
               "regex" => "/^[0-9]{2}[\/][0-9]{2}[\/][0-9]{4}$/",
               "default" => "YEAR",
               "expected" => ['YEAR']
            ],
            "dateto default" => [
               "name" => "dateto",
               "value" => "NOW",
               "regex" => "/^[0-9]{2}[\/][0-9]{2}[\/][0-9]{4}$/",
               "default" => "NOW",
               "expected" => ['NOW']
            ],
            "field base" => [
               "name" => "field",
               "value" => "Q",
               "regex" => "/^[V|L|M|D|H|Q]$/",
               "default" => "V",
               "expected" => ['portata']
            ],
            "field default" => [
               "name" => "field",
               "value" => "V",
               "regex" => "/^[V|L|M|D|H|Q]$/",
               "default" => "V",
               "expected" => ['volume']
            ],
            "full base" => [
               "name" => "full",
               "value" => "TRUE",
               "regex" => "/^((TRUE)|(FALSE))$/",
               "default" => "FALSE",
               "expected" => [true]
            ],
            "full default" => [
               "name" => "full",
               "value" => "FALSE",
               "regex" => "/^((TRUE)|(FALSE))$/",
               "default" => "FALSE",
               "expected" => [false]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers checkParameter()
     * @dataProvider checkParameterProvider
     */
    public function testCheckParameterEquals(string $paramName, string $paramValue, string $regex, string $default, array $expected) : void
    {
        $actual = checkParameter($paramName, $paramValue, $regex, $default);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers checkParameter()
     */
    public function testCheckParameterException() : void
    {
        $paramName = 'pippo';
        $paramValue = 'pluto';
        $regex = '/^[0-9]{5}([,][0-9]{5})*$/';
        $default = 'paperoga';
        
        $this->expectException(\Exception::class);
        
        checkParameter($paramName, $paramValue, $regex, $default);
    }
    
    /**
     * @coversNothing
     */
    public function setParameterProvider() : array
    {
        $parameters = [
            "var" => [
               "short" => "V",
               "long" => "var",
               "regex" => "/^[0-9]{5}([,][0-9]{5})*$/",
               "default" => "ALL"
            ],
            "datefrom" => [
               "short" => "f",
               "long" => "datefrom",
               "regex" => "/^[0-9]{2}[\/][0-9]{2}[\/][0-9]{4}$/",
               "default" => "YEAR"
            ],
            "dateto" => [
               "short" => "t",
               "long" => "dateto",
               "regex" => "/^[0-9]{2}[\/][0-9]{2}[\/][0-9]{4}$/",
               "default" => "NOW"
            ],
            "field" => [
               "short" => "c",
               "long" => "campo",
               "regex" => "/^[V|L|M|D|H|Q]$/",
               "default" => "V"
            ],
            "full" => [
               "short" => "n",
               "long" => "nozero",
               "regex" => "/^((TRUE)|(FALSE))$/",
               "default" => "FALSE"
            ]
        ];

        $data = [
            'var base' => [
                'parameters' => $parameters,
                'param' => 'var',
                'argv' => ['scarichi.php', '-V', '30030', '-f', '-t', '-c', '-n'],
                'expected' => ['30030']
            ],
            'var multi' => [
                'parameters' => $parameters,
                'param' => 'var',
                'argv' => ['scarichi.php', '-V', '30030,30040', '-f', '-t', '-c', '-n'],
                'expected' => ['30030','30040']
            ],
            'var default' => [
                'parameters' => $parameters,
                'param' => 'var',
                'argv' => ['scarichi.php', '-V', 'ALL', '-f', '-t', '-c', '-n'],
                'expected' => ['ALL']
            ],
            'var no value' => [
                'parameters' => $parameters,
                'param' => 'var',
                'argv' => ['scarichi.php', '-V', '-f', '-t', '-c', '-n'],
                'expected' => ['ALL']
            ],
            'var double' => [
                'parameters' => $parameters,
                'param' => 'var',
                'argv' => ['scarichi.php', '-V', '-f', '-t', '-c', '-n', '--var', '30030'],
                'expected' => ['30030']
            ],
            'datefrom base' => [
                'parameters' => $parameters,
                'param' => 'datefrom',
                'argv' => ['scarichi.php', '-V', '-f', '31/12/2020', '-t', '-c', '-n'],
                'expected' => ['2020-12-31']
            ],
            'datefrom default' => [
                'parameters' => $parameters,
                'param' => 'datefrom',
                'argv' => ['scarichi.php', '-V', '-f', 'YEAR', '-t', '-c', '-n'],
                'expected' => ['YEAR']
            ],
            'datefrom no value' => [
                'parameters' => $parameters,
                'param' => 'datefrom',
                'argv' => ['scarichi.php', '-V', '-f', '-t', '-c', '-n'],
                'expected' => ['YEAR']
            ],
            'dateto base' => [
                'parameters' => $parameters,
                'param' => 'dateto',
                'argv' => ['scarichi.php', '-V', '-f', '-t', '31/12/2020', '-c', '-n'],
                'expected' => ['2020-12-31']
            ],
            'dateto default' => [
                'parameters' => $parameters,
                'param' => 'dateto',
                'argv' => ['scarichi.php', '-V', '-f', '-t', 'NOW', '-c', '-n'],
                'expected' => ['NOW']
            ],
            'dateto no value' => [
                'parameters' => $parameters,
                'param' => 'dateto',
                'argv' => ['scarichi.php', '-V', '-f', '-t', '-c', '-n'],
                'expected' => ['NOW']
            ],
            'field base' => [
                'parameters' => $parameters,
                'param' => 'field',
                'argv' => ['scarichi.php', '-V', '-f', '-t', '-c', 'Q', '-n'],
                'expected' => ['portata']
            ],
            'field default' => [
                'parameters' => $parameters,
                'param' => 'field',
                'argv' => ['scarichi.php', '-V', '-f', '-t', '-c', 'V', '-n'],
                'expected' => ['volume']
            ],
            'field no value' => [
                'parameters' => $parameters,
                'param' => 'field',
                'argv' => ['scarichi.php', '-V', '-f', '-t', '-c', '-n'],
                'expected' => ['V']
            ],
            'full base' => [
                'parameters' => $parameters,
                'param' => 'full',
                'argv' => ['scarichi.php', '-V', '-f', '-t', '-c', 'Q', '-n', 'TRUE'],
                'expected' => [true]
            ],
            'full default' => [
                'parameters' => $parameters,
                'param' => 'full',
                'argv' => ['scarichi.php', '-V', '-f', '-t', '-c', 'V', '-n', 'FALSE'],
                'expected' => [false]
            ],
            'full no value' => [
                'parameters' => $parameters,
                'param' => 'full',
                'argv' => ['scarichi.php', '-V', '-f', '-t', '-c', '-n'],
                'expected' => ['FALSE']
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers setParameter()
     * @dataProvider setParameterProvider
     */
    public function testSetParameterEquals(array $parameters, string $paramName, array $arguments, array $expected) : void
    {
        $actual = setParameter($parameters, $paramName, $arguments);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers etParameter()
     */
    public function testSetParameterException() : void
    {
        $parameters = [];
        $paramName = 'var';
        $arguments = ['scarichi.php', '-V', '-f', '-t', '-c', '-n'];
        
        $this->expectException(\Exception::class);
        
        setParameter($parameters, $paramName, $arguments);
    }
    
    /**
     * @coversNothing
     */
    public function shuntTypesProvider() : array
    {
        $parameters = [
            "help" => [
               "short" => "h",
               "long" => "help",
               "regex" => "",
               "default" => "",
               "type" => "single"
            ],
            "version" => [
               "short" => "v",
               "long" => "version",
               "regex" => "",
               "default" => "",
               "type" => "single"
            ],
            "default" => [
               "short" => "d",
               "long" => "default",
               "regex" => "",
               "default" => "",
               "type" => "single"
            ],
            "var" => [
               "short" => "V",
               "long" => "var",
               "regex" => "/^[0-9]{5}([,][0-9]{5})*$/",
               "default" => "ALL",
               "type" => "group"
            ],
            "datefrom" => [
               "short" => "f",
               "long" => "datefrom",
               "regex" => "/^[0-9]{2}[\/][0-9]{2}[\/][0-9]{4}$/",
               "default" => "YEAR",
               "type" => "group"
            ],
            "dateto" => [
               "short" => "t",
               "long" => "dateto",
               "regex" => "/^[0-9]{2}[\/][0-9]{2}[\/][0-9]{4}$/",
               "default" => "NOW",
               "type" => "group"
            ],
            "field" => [
               "short" => "c",
               "long" => "campo",
               "regex" => "/^[V|L|M|D|H|Q]$/",
               "default" => "V",
               "type" => "group"
            ],
            "full" => [
               "short" => "n",
               "long" => "nozero",
               "regex" => "/^((TRUE)|(FALSE))$/",
               "default" => "FALSE",
               "type" => "group"
            ]
        ];

        $data = [
            'html' => [
                'parameters' => $parameters,
                'argv' => null,
                'expected' => 'html'
            ],
            'no arg' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php'],
                'expected' => 'help'
            ],
            'help short' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php', '-h'],
                'expected' => 'help'
            ],
            'help long' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php', '--help'],
                'expected' => 'help'
            ],
            'version short' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php', '-v'],
                'expected' => 'version'
            ],
            'version long' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php', '--version'],
                'expected' => 'version'
            ],
            'default short' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php', '-d'],
                'expected' => 'default'
            ],
            'default long' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php', '--default'],
                'expected' => 'default'
            ],
            'ok short' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php', '-V', '-f', '-t', '-c', '-n'],
                'expected' => 'ok'
            ],
            'ok long' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php', '--var', '--datefrom', '--dateto', '--campo', '--nozero'],
                'expected' => 'ok'
            ],
            'ok mixed' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php', '-V', '30030', '--datefrom', '-t', 'NOW', '-c', '-n', '--var', 'ALL'],
                'expected' => 'ok'
            ],
            'error' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php', '-V', '30030', '--datefrom', '-t', 'NOW', '-c'],
                'expected' => 'error'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers shuntTypes()
     * @dataProvider shuntTypesProvider
     */
    public function testShuntTypesEquals(array $parameters, ?array $arguments, string $expected) : void
    {
        $actual = shuntTypes($parameters, $arguments);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers shuntTypes()
     */
    public function testShuntTypesException() : void
    {
        $parameters = [];
        $arguments = ['scarichi.php', '-V', '-f', '-t', '-c', '-n'];
        
        $this->expectException(\Exception::class);
        
        shuntTypes($parameters, $arguments);
    }
    
    /**
     * @coversNothing
     */
    public function setParametersProvider() : array
    {
        $parameters = [
            "help" => [
               "name" => "help",
               "short" => "h",
               "long" => "help",
               "regex" => "",
               "default" => "",
               "type" => "single"
            ],
            "version" => [
               "name" => "version",
               "short" => "v",
               "long" => "version",
               "regex" => "",
               "default" => "",
               "type" => "single"
            ],
            "default" => [
               "name" => "default",
               "short" => "d",
               "long" => "default",
               "regex" => "",
               "default" => "",
               "type" => "single"
            ],
            "var" => [
               "name" => "variabile",
               "short" => "V",
               "long" => "var",
               "regex" => "/^[0-9]{5}([,][0-9]{5})*$/",
               "default" => "ALL",
               "type" => "group"
            ],
            "datefrom" => [
               "name" => "datefrom",
               "short" => "f",
               "long" => "datefrom",
               "regex" => "/^[0-9]{2}[\/][0-9]{2}[\/][0-9]{4}$/",
               "default" => "YEAR",
               "type" => "group"
            ],
            "dateto" => [
               "name" => "dateto",
               "short" => "t",
               "long" => "dateto",
               "regex" => "/^[0-9]{2}[\/][0-9]{2}[\/][0-9]{4}$/",
               "default" => "NOW",
               "type" => "group"
            ],
            "field" => [
               "name" => "campo",
               "short" => "c",
               "long" => "campo",
               "regex" => "/^[V|L|M|D|H|Q]$/",
               "default" => "V",
               "type" => "group"
            ],
            "full" => [
               "name" => "no zero",
               "short" => "n",
               "long" => "nozero",
               "regex" => "/^((TRUE)|(FALSE))$/",
               "default" => "FALSE",
               "type" => "group"
            ]
        ];

        $data = [
            'html' => [
                'parameters' => $parameters,
                'argv' => null,
                'type' => 'html',
                'expected' => []
            ],
            'no arg' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php'],
                'type' => 'help',
                'expected' => []
            ],
            'help' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php', '-h'],
                'type' => 'help',
                'expected' => []
            ],
            'version' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php', '-v'],
                'type' => 'version',
                'expected' => []
            ],
            'default' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php', '-d'],
                'type' => 'default',
                'expected' => [
                    'var' => ['ALL'],
                    'datefrom' => ['YEAR'],
                    'dateto' => ['NOW'],
                    'field' => ['V'],
                    'full' => ['FALSE']
                ]
            ],
            'ok default' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php', '-V', '-f', '-t', '-c', '-n'],
                'type' => 'ok',
                'expected' => [
                    'var' => ['ALL'],
                    'datefrom' => ['YEAR'],
                    'dateto' => ['NOW'],
                    'field' => ['V'],
                    'full' => ['FALSE']
                ]
                
            ],
            'ok mixed' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php', '-V', 'ALL', '--datefrom', '31/12/2020', '-t', 'NOW', '-c', 'Q', '-n', '--var', '30030,30040'],
                'type' => 'ok',
                'expected' => [
                    'var' => ['30030','30040'],
                    'datefrom' => ['2020-12-31'],
                    'dateto' => ['NOW'],
                    'field' => ['portata'],
                    'full' => ['FALSE']
                ]
            ],
            'error' => [
                'parameters' => $parameters,
                'argv' => ['scarichi.php', '-V', '30030', '--datefrom', '-t', 'NOW', '-c'],
                'type' => 'error',
                'expected' => []
                
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers setParameters()
     * @dataProvider setParametersProvider
     */
    public function testSetParametersEquals(array $parameters, ?array $arguments, string $type, array $expected) : void
    {
        $actual = setParameters($parameters, $arguments, $type);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers setParameters()
     */
    public function testSetParametersException() : void
    {
        $parameters = [];
        $arguments = ['scarichi.php', '-V', '-f', '-t', '-c', '-n'];
        $type = 'ok';
        
        $this->expectException(\Exception::class);
        
        setParameters($parameters, $arguments, $type);
    }
    
    /**
     * @coversNothing
     */
    public function fillParametersProvider() : array
    {
        $parameters = [
            "var" => [
                "default" => "ALL",
                "options" => [
                    "costants" => [],
                    "alias" => [],
                    "limits" => []
                ]
            ],
            "datefrom" => [
                "default" => "1D",
                "options" => [
                    "costants" => ['D'],
                    "alias" => ['giorno'],
                    "limits" => ['364']
                ]
            ],
            "dateto" => [
                "default" => "NOW",
                "options" => [
                    "costants" => [],
                    "alias" => [],
                    "limits" => []
                ]
            ],
            "field" => [
                "default" => "V",
                "options" => [
                    "costants" => [
                        "V",
                        "L",
                        "M",
                        "D",
                        "H",
                        "Q"
                    ],
                    "alias" => [
                        "volume",
                        "livello",
                        "media",
                        "delta",
                        "altezza",
                        "portata"
                    ],
                    "limits" => []
                ]
            ],
            "full" => [
                "default" => "FALSE",
                "options" => [
                    "costants" => [],
                    "alias" => [],
                    "limits" => []
                ]
            ]
        ];
        
        $now = new \DateTime();
        $day = new \DateTime();
        $interval = new \DateInterval('P1D');
        $day->sub($interval);
        $all = selectAllQuery('SSCP_data', 'query_variabili_ALL');

        $data = [
            'no values' => [
                'parameters' => $parameters,
                'values' => [],
                'expected' => []
            ],
            'default' => [
                'parameters' => $parameters,
                'values' => [
                    'var' => ['ALL'],
                    'datefrom' => ['1D'],
                    'dateto' => ['NOW'],
                    'field' => ['V'],
                    'full' => ['FALSE']
                ],
                'expected' => [
                    'var' => $all,
                    'datefrom' => $day->format('d/m/Y'),
                    'dateto' => $now->format('d/m/Y'),
                    'field' => 'volume',
                    'full' => '1'
                ]
            ],
            'mixed 1' => [
                'parameters' => $parameters,
                'values' => [
                    'var' => ['ALL'],
                    'datefrom' => ['1D'],
                    'dateto' => ['2020-12-31'],
                    'field' => ['volume'],
                    'full' => [false]
                ],
                'expected' => [
                    'var' => $all,
                    'datefrom' => '30/12/2020',
                    'dateto' => '31/12/2020',
                    'field' => 'volume',
                    'full' => '1'
                ]
                
            ],
            'mixed 2' => [
                'parameters' => $parameters,
                'values' => [
                    'var' => ['30030','30040'],
                    'datefrom' => ['2020-12-31'],
                    'dateto' => ['NOW'],
                    'field' => ['portata'],
                    'full' => ['FALSE']
                ],
                'expected' => [
                    'var' => ['30030','30040'],
                    'datefrom' => '31/12/2020',
                    'dateto' => $now->format('d/m/Y'),
                    'field' => 'portata',
                    'full' => '1'
                ]
            ],
            'all new' => [
                'parameters' => $parameters,
                'values' => [
                    'var' => ['30030','30040'],
                    'datefrom' => ['2020-12-30'],
                    'dateto' => ['2020-12-31'],
                    'field' => ['portata'],
                    'full' => [true]
                ],
                'expected' => [
                    'var' => ['30030','30040'],
                    'datefrom' => '30/12/2020',
                    'dateto' => '31/12/2020',
                    'field' => 'portata',
                    'full' => '0'
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers fillParameters()
     * @dataProvider fillParametersProvider
     */
    public function testFillParametersEquals(array $parameters, array $values, array $expected) : void
    {
        $actual = fillParameters($parameters, $values);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers fillParameters()
     */
    public function testFillParametersException() : void
    {
        $parameters = [];
        $values = [
            'var' => ['30030','30040'],
            'datefrom' => ['2020-12-30'],
            'dateto' => ['2020-12-31'],
            'field' => ['portata'],
            'full' => [true]
        ];
        
        $this->expectException(\Exception::class);
        
        fillParameters($parameters, $values);
    }
    
    /**
     * @group test
     * covers fillParameters()
     */
    public function testFillParametersOptionException() : void
    {
        $parameters = ['pippo'];
        $values = [
            'variabile' => ['30030','30040'],
            'datefrom' => ['2020-12-30'],
            'dateto' => ['2020-12-31'],
            'field' => ['portata'],
            'full' => [true]
        ];
        
        $this->expectException(\Exception::class);
        
        fillParameters($parameters, $values);
    }
    
    /**
     * @coversNothing
     */
    public function fillVarProvider() : array
    {
        $parameters = [
            "var" => [
                "default" => "ALL",
                "options" => [
                    "costants" => [],
                    "alias" => []
                ]
            ]
        ];
        
        $all = selectAllQuery('SSCP_data', 'query_variabili_ALL');

        $data = [
            'default' => [
                'parameters' => $parameters,
                'values' => [
                    'var' => ['ALL']
                ],
                'postArray' => [],
                'expected' => [
                    'var' => $all
                ]
            ],
            'single' => [
                'parameters' => $parameters,
                'values' => [
                    'var' => ['30030'],
                ],
                'postArray' => [],
                'expected' => [
                    'var' => ['30030'],
                 ]
            ],
            'double' => [
                'parameters' => $parameters,
                'values' => [
                    'var' => ['30030','30040'],
                ],
                'postArray' => [],
                'expected' => [
                    'var' => ['30030','30040'],
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers fillVar()
     * @dataProvider fillVarProvider
     */
    public function testFillVarEquals(array $parameters, array $values, array $postVars, array $expected) : void
    {
        $actual = fillVar($parameters, $values, $postVars);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers fillVar()
     */
    public function testFillVarException() : void
    {
        $parameters = [];
        $values = [
            'var' => ['30030','30040'],
            'datefrom' => ['2020-12-30'],
            'dateto' => ['2020-12-31'],
            'field' => ['portata'],
            'full' => [true]
        ];
        $postVars = [];
        
        $this->expectException(\Exception::class);
        
        fillVar($parameters, $values, $postVars);
    }

    /**
     * @coversNothing
     */
    public function fillDatefromProvider() : array
    {
        $parameters = [
            "datefrom" => [
                "default" => "1D",
                "options" => [
                    "costants" => ["D"],
                    "alias" => ['giorno'],
                    "limits" => ['364']
                ]
            ],
            "dateto" => [
                "default" => "NOW",
                "options" => [
                    "costants" => [],
                    "alias" => []
                ]
            ]
        ];
        
        $now = new \DateTime();
        $day = new \DateTime();
        $interval = new \DateInterval('P1D');
        $day->sub($interval);

        $data = [
            'default' => [
                'parameters' => $parameters,
                'values' => [
                    'datefrom' => ['1D'],
                    'dateto' => ['NOW']
                ],
                'postArray' => [
                    'var' => ['30030','30040']
                ],
                'expected' => [
                    'var' => ['30030','30040'],
                    'datefrom' => $day->format('d/m/Y')
                ]
            ],
            'dependent' => [
                'parameters' => $parameters,
                'values' => [
                    'datefrom' => ['1D'],
                    'dateto' => ['2020-12-31'],
                ],
                'postArray' => [
                    'var' => ['30030','30040']
                ],
                'expected' => [
                    'var' => ['30030','30040'],
                    'datefrom' => '30/12/2020'
                 ]
            ],
            'indipendent' => [
                'parameters' => $parameters,
                'values' => [
                    'datefrom' => ['2020-12-31']
                ],
                'postArray' => [
                    'var' => ['30030','30040']
                ],
                'expected' => [
                    'var' => ['30030','30040'],
                    'datefrom' => '31/12/2020'
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers fillDatefrom()
     * @dataProvider fillDatefromProvider
     */
    public function testFillDatefromEquals(array $parameters, array $values, array $postVars, array $expected) : void
    {
        $actual = fillDatefrom($parameters, $values, $postVars);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers fillDatefrom()
     */
    public function testFillDatefromException() : void
    {
        $parameters = [];
        $values = [
            'var' => ['30030','30040'],
            'datefrom' => ['2020-12-30'],
            'dateto' => ['2020-12-31'],
            'field' => ['portata'],
            'full' => [true]
        ];
        $postVars = [];
        
        $this->expectException(\Exception::class);
        
        fillDatefrom($parameters, $values, $postVars);
    }
    
    /**
     * @coversNothing
     */
    public function fillDatetoProvider() : array
    {
        $parameters = [
            "dateto" => [
                "default" => "NOW",
                "options" => [
                    "costants" => [],
                    "alias" => []
                ]
            ]
        ];
        
        $now = new \DateTime();

        $data = [
            'default' => [
                'parameters' => $parameters,
                'values' => [
                    'dateto' => ['NOW']
                ],
                'postArray' => [
                    'var' => ['30030','30040'],
                    'datefrom' => '31/12/2019'
                ],
                'expected' => [
                    'var' => ['30030','30040'],
                    'datefrom' => '31/12/2019',
                    'dateto' => $now->format('d/m/Y')
                ]
            ],
            'indipendent' => [
                'parameters' => $parameters,
                'values' => [
                    'dateto' => ['2020-12-31']
                ],
                'postArray' => [
                    'var' => ['30030','30040'],
                    'datefrom' => '31/12/2019'
                ],
                'expected' => [
                    'var' => ['30030','30040'],
                    'datefrom' => '31/12/2019',
                    'dateto' => '31/12/2020'
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers fillDateto()
     * @dataProvider fillDatetoProvider
     */
    public function testFillDatetoEquals(array $parameters, array $values, array $postVars, array $expected) : void
    {
        $actual = fillDateto($parameters, $values, $postVars);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers fillDateto()
     */
    public function testFillDatetoException() : void
    {
        $parameters = [];
        $values = [
            'var' => ['30030','30040'],
            'datefrom' => ['2020-12-30'],
            'dateto' => ['2020-12-31'],
            'field' => ['portata'],
            'full' => [true]
        ];
        $postVars = [];
        
        $this->expectException(\Exception::class);
        
        fillDateto($parameters, $values, $postVars);
    }
    
    /**
     * @coversNothing
     */
    public function fillFieldProvider() : array
    {
        $parameters = [
            "field" => [
                "default" => "V",
                "options" => [
                    "costants" => [
                        "V",
                        "L",
                        "M",
                        "D",
                        "H",
                        "Q"
                    ],
                    "alias" => [
                        "volume",
                        "livello",
                        "media",
                        "delta",
                        "altezza",
                        "portata"
                    ]
                ]
            ]
        ];
        
        $data = [
            'default' => [
                'parameters' => $parameters,
                'values' => [
                    'field' => ['V']
                ],
                'postArray' => [
                    'var' => ['30030','30040'],
                    'datefrom' => '31/12/2019',
                    'dateto' => '31/12/2020'
                ],
                'expected' => [
                    'var' => ['30030','30040'],
                    'datefrom' => '31/12/2019',
                    'dateto' => '31/12/2020',
                    'field' => 'volume'
                ]
            ],
            'dependent' => [
                'parameters' => $parameters,
                'values' => [
                    'field' => ['volume']
                ],
                'postArray' => [
                    'var' => ['30030','30040'],
                    'datefrom' => '31/12/2019',
                    'dateto' => '31/12/2020'
                    
                ],
                'expected' => [
                    'var' => ['30030','30040'],
                    'datefrom' => '31/12/2019',
                    'dateto' => '31/12/2020',
                    'field' => 'volume'
                ]
            ],
            'independent' => [
                'parameters' => $parameters,
                'values' => [
                    'field' => ['portata']
                ],
                'postArray' => [
                    'var' => ['30030','30040'],
                    'datefrom' => '31/12/2019',
                    'dateto' => '31/12/2020'
                    
                ],
                'expected' => [
                    'var' => ['30030','30040'],
                    'datefrom' => '31/12/2019',
                    'dateto' => '31/12/2020',
                    'field' => 'portata'
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers fillField()
     * @dataProvider fillFieldProvider
     */
    public function testFillFieldEquals(array $parameters, array $values, array $postVars, array $expected) : void
    {
        $actual = fillField($parameters, $values, $postVars);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers fillField()
     */
    public function testFillFieldException() : void
    {
        $parameters = [];
        $values = [
            'var' => ['30030','30040'],
            'datefrom' => ['2020-12-30'],
            'dateto' => ['2020-12-31'],
            'field' => ['portata'],
            'full' => [true]
        ];
        $postVars = [];
        
        $this->expectException(\Exception::class);
        
        fillField($parameters, $values, $postVars);
    }
    
    /**
     * @coversNothing
     */
    public function fillFullProvider() : array
    {
        $parameters = [
            "full" => [
                "default" => "FALSE",
                "options" => [
                    "costants" => [],
                    "alias" => []
                ]
            ]
        ];
        
        $data = [
            'default' => [
                'parameters' => $parameters,
                'values' => [
                    'full' => ['FALSE']
                ],
                'postArray' => [
                    'var' => ['30030','30040'],
                    'datefrom' => '31/12/2019',
                    'dateto' => '31/12/2020',
                    'field' => 'volume'
                ],
                'expected' => [
                    'var' => ['30030','30040'],
                    'datefrom' => '31/12/2019',
                    'dateto' => '31/12/2020',
                    'field' => 'volume',
                    'full' => '1'
                ]
            ],
            'dependent' => [
                'parameters' => $parameters,
                'values' => [
                    'full' => [false]
                ],
                'postArray' => [
                    'var' => ['30030','30040'],
                    'datefrom' => '31/12/2019',
                    'dateto' => '31/12/2020',
                    'field' => 'volume'
                    
                ],
                'expected' => [
                    'var' => ['30030','30040'],
                    'datefrom' => '31/12/2019',
                    'dateto' => '31/12/2020',
                    'field' => 'volume',
                    'full' => '1'
                ]
            ],
            'independent' => [
                'parameters' => $parameters,
                'values' => [
                    'full' => [true]
                ],
                'postArray' => [
                    'var' => ['30030','30040'],
                    'datefrom' => '31/12/2019',
                    'dateto' => '31/12/2020',
                    'field' => 'volume',
                    
                ],
                'expected' => [
                    'var' => ['30030','30040'],
                    'datefrom' => '31/12/2019',
                    'dateto' => '31/12/2020',
                    'field' => 'volume',
                    'full' => '0'
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers fillFull()
     * @dataProvider fillFullProvider
     */
    public function testFillFullEquals(array $parameters, array $values, array $postVars, array $expected) : void
    {
        $actual = fillFull($parameters, $values, $postVars);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers fillFull()
     */
    public function testFillFullException() : void
    {
        $parameters = [];
        $values = [
            'var' => ['30030','30040'],
            'datefrom' => ['2020-12-30'],
            'dateto' => ['2020-12-31'],
            'field' => ['portata'],
            'full' => [true]
        ];
        $postVars = [];
        
        $this->expectException(\Exception::class);
        
        fillFull($parameters, $values, $postVars);
    }
    
    /**
     * @coversNothing
     */
    public function setPostParametersProvider() : array
    {
        $parameters = [
            'help' => [],
            'version' => [],
            'default' => [],
            'var' => [],
            'datefrom' => [],
            'dateto' => [],
            'field' => [],
            'full' => []
        ];
        
        $data = [
            'no filled values' => [
                'parameters' => $parameters,
                'filled values' => [],
                'expected' => []
            ],
            'single' => [
                'parameters' => $parameters,
                'filled values' => [
                    'var' => ['30030'],
                    'datefrom' => '30/12/2020',
                    'dateto' => '31/12/2020',
                    'field' => 'portata',
                    'full' => '0'
                ],
                'expected' => [
                    [
                        'var' => '30030',
                        'datefrom' => '30/12/2020',
                        'dateto' => '31/12/2020',
                        'field' => 'portata',
                        'full' => '0'
                    ]
                ]
            ],
            'multi' => [
                'parameters' => $parameters,
                'filled values' => [
                    'var' => ['30030','30040'],
                    'datefrom' => '30/12/2020',
                    'dateto' => '31/12/2020',
                    'field' => 'portata',
                    'full' => '0'
                ],
                'expected' => [
                    [
                        'var' => '30030',
                        'datefrom' => '30/12/2020',
                        'dateto' => '31/12/2020',
                        'field' => 'portata',
                        'full' => '0'
                    ],
                    [
                        'var' => '30040',
                        'datefrom' => '30/12/2020',
                        'dateto' => '31/12/2020',
                        'field' => 'portata',
                        'full' => '0'
                    ]
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers setPostParameters()
     * @dataProvider setPostParametersProvider
     */
    public function testSetPostParametersEquals($parameters, $filledValues, $expected) : void
    {
        $actual = setPostParameters($parameters, $filledValues);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers setPostParameters()
     */
    public function testSetPostParametersException() : void
    {
        $parameters = [
            'help' => [],
            'version' => [],
            'default' => [],
            'var' => [],
            'datefrom' => [],
            'dateto' => [],
            'field' => [],
            'full' => []
        ];
        $filledValues = [
            'variabile' => ['30030'],
            'datefrom' => '30/12/2020',
            'dateto' => '31/12/2020',
            'field' => 'portata',
            'full' => '0'
        ];
        
        $this->expectException(\Exception::class);
        
        setPostParameters($parameters, $filledValues);
    }
    
    /**
     * @group test
     * covers setPostParameters()
     */
    public function testSetPostParametersNoVarException() : void
    {
        $parameters = [
            'help' => [],
            'version' => [],
            'default' => [],
            'variabile' => [],
            'datefrom' => [],
            'dateto' => [],
            'field' => [],
            'full' => []
        ];
        $filledValues = [
            'variabile' => ['30030'],
            'datefrom' => '30/12/2020',
            'dateto' => '31/12/2020',
            'field' => 'portata',
            'full' => '0'
        ];
        
        $this->expectException(\Exception::class);
        
        setPostParameters($parameters, $filledValues);
    }
    
    /**
     * @coversNothing
     */
    public function goCurlProvider() : array
    {
        $url = "http://localhost/telecontrollo/scarichi/github/src/index.php";
        $single = '1) 30030: Elaborazione dati Portata variabile 30030 dal 30/12/2019 al 31/12/2019 avvenuta con successo. Nessun file CSV senza zeri esportato per mancanza di dati.' . PHP_EOL;
        $multi = $single . '2) 30040: Elaborazione dati Portata variabile 30040 dal 30/12/2019 al 31/12/2019 avvenuta con successo. Nessun file CSV senza zeri esportato per mancanza di dati.' . PHP_EOL;
        
        $data = [
            'no post values' => [
                'post' => [],
                'url' => $url,
                'expected' => ''
            ],
            'single' => [
                'post' => [
                    [
                        'var' => '30030',
                        'datefrom' => '30/12/2019',
                        'dateto' => '31/12/2019',
                        'field' => 'portata',
                        'full' => '0'
                    ]
                ],
                'url' => $url,
                'expected' => $single
            ],
            'multi' => [
                'post' => [
                    [
                        'var' => '30030',
                        'datefrom' => '30/12/2019',
                        'dateto' => '31/12/2019',
                        'field' => 'portata',
                        'full' => '0'
                    ],
                    [
                        'var' => '30040',
                        'datefrom' => '30/12/2019',
                        'dateto' => '31/12/2019',
                        'field' => 'portata',
                        'full' => '0'
                    ]
                ],
                'url' => $url,
                'expected' => $multi
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers goCurl()
     * @dataProvider goCurlProvider
     */
    public function testGoCurlEquals(array $postParam, string $url, string $expected) : void
    {
        $actual = goCurl($postParam, $url);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers goCurl()
     */
    public function testGoCurlDataException() : void
    {
        $postParam = [];
        $url = 'pippo';
        
        $this->expectException(\Exception::class);
        
        goCurl($postParam, $url);
    }

    /**
     * @coversNothing
     */
    public function selectLastPrevDataProvider() : array
    {
        $data = [
            'manovra void' => [
                'db' => 'SSCP_data',
                'parametri' => [
                    'variabile' => '30028',
                    'tipo_dato' => '1',
                    'data_iniziale' => new \DateTime('2020-04-21 00:00:00')
                ],
                'dati' => [
                    'manovra' => []
                ],
                'categoria' => 'manovra',
                'expected' => [
                    'manovra' => [
                        '0' => [
                            'variabile' => 30028,
                            'valore' => 0,
                            'unita_misura' => 'cm',
                            'tipo_dato' => 1,
                            'data_e_ora' => new \DateTime('2020-04-21 00:00:00', new \DateTimeZone('Europe/Rome'))
                        ]
                    ]
                ]
            ],
            'manovra standard' => [
                'db' => 'SSCP_data',
                'parametri' => [
                    'variabile' => '30028',
                    'tipo_dato' => '1',
                    'data_iniziale' => new \DateTime('2020-04-21 00:00:00')
                ],
                'dati' => [
                    'manovra' => [
                        '0' => [
                            'variabile' => 30028,
                            'valore' => 0,
                            'unita_misura' => 'cm',
                            'tipo_dato' => 1,
                            'data_e_ora' => new \DateTime('2020-04-21 00:00:00', new \DateTimeZone('Europe/Rome'))
                        ]
                    ]
                ],
                'categoria' => 'manovra',
                'expected' => [
                    'manovra' => [
                        '0' => [
                            'variabile' => 30028,
                            'valore' => 0,
                            'unita_misura' => 'cm',
                            'tipo_dato' => 1,
                            'data_e_ora' => new \DateTime('2020-04-21 00:00:00', new \DateTimeZone('Europe/Rome'))
                        ]
                    ]
                ]
            ],
            'manovra void over' => [
                'db' => 'SSCP_data',
                'parametri' => [
                    'variabile' => '30028',
                    'tipo_dato' => '1',
                    'data_iniziale' => new \DateTime('1970-01-02 00:00:00')
                ],
                'dati' => [
                    'manovra' => []
                ],
                'categoria' => 'manovra',
                'expected' => [
                    'manovra' => []
                ]
            ],
            'no manovra' => [
                'db' => 'SSCP_data',
                'parametri' => [
                    'variabile' => '30028',
                    'tipo_dato' => '1',
                    'data_iniziale' => new \DateTime('2020-04-21 00:00:00')
                ],
                'dati' => [
                    'livello' => [
                        '0' => [
                            'variabile' => 30028,
                            'valore' => 0,
                            'unita_misura' => 'cm',
                            'tipo_dato' => 1,
                            'data_e_ora' => new \DateTime('2020-04-21 00:00:00', new \DateTimeZone('Europe/Rome'))
                        ]
                    ]
                ],
                'categoria' => 'manovra',
                'expected' => [
                    'livello' => [
                        '0' => [
                            'variabile' => 30028,
                            'valore' => 0,
                            'unita_misura' => 'cm',
                            'tipo_dato' => 1,
                            'data_e_ora' => new \DateTime('2020-04-21 00:00:00', new \DateTimeZone('Europe/Rome'))
                        ]
                    ]
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers selectLastPrevData()
     * @dataProvider selectLastPrevDataProvider
     */
    public function testSelectLastPrevDataEquals(string $db, array $parametri, array $dati, string $categoria, array $expected) : void
    {
        $actual = selectLastPrevData($db, $parametri, $dati, $categoria);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers selectLastPrevData()
     */
    public function testSelectLastPrevDataException() : void
    {
        $db = 'SSCP_data';
        $parametri = [
            'variabile' => null,
            'tipo dato' => '1',
            'data_iniziale' => new \DateTime('2020-04-21 00:00:00')
        ];
        $dati = [
            'manovra' => []
        ];
        $categoria = 'manovra';

        $this->expectException(\Exception::class);
        
        selectLastPrevData($db, $parametri, $dati, $categoria);
    }
    
    /**
     * @coversNothing
     */
    public function checkCurlResponseProvider() : array
    {
        $data = [
            'no response level 1' => [
                'response' => '',
                'debug' => 1,
                'expected' => 'Elaborazone fallita. Verificare il log degli errori (' . realpath(LOG_PATH) . '/' . ERROR_LOG . ').'
            ],
            'no response level 2' => [
                'response' => '',
                'debug' => 2,
                'expected' => 'Elaborazone fallita.'
            ],
            'response' => [
                'response' => '<b>pippo<b/>',
                'debug' => 1,
                'expected' => 'pippo'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers checkCurlResponse()
     * @dataProvider checkCurlResponseProvider
     */
    public function testCheckCurlResponseEquals(string $response, int $debug_level, string $expected) : void
    {
        $actual = checkCurlResponse($response, $debug_level);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers checkCurlResponse()
     */
    public function testCheckCurlResponseException() : void
    {
        $response = 'pippo';
        $debug_level = 3;
        
        $this->expectException(\Exception::class);
        
        checkCurlResponse($response, $debug_level);
    }
}
