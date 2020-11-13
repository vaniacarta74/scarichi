<?php
namespace vaniacarta74\Scarichi\tests\Unit;

use PHPUnit\Framework\TestCase;
use vaniacarta74\Scarichi\Curl;

class ToPbiTest extends TestCase
{
    
    /**
     * coversNothing
     */
    public function toPbiProvider() : array
    {
        $data = [
            'standard' => [
                'var' => '30030',
                'datefrom' => '01/02/2018',
                'dateto' => '02/02/2018',
                'full' => '1',
                'field' => 'volume',
                'expected' => [
                    'tipo_formula' => 'portata sfiorante',
                    'alias' => 'sfioro',
                    'scarico' => 1,
                    'mi' => 0.47,
                    'larghezza' => 40.5,
                    'quota' => 276.5,
                    'limite' => 942.67,
                    'variabile' => 30030,
                    'data_e_ora' => [
                        'date' => '2018-02-01 00:00:00.000000',
                        'timezone_type' => 3,
                        'timezone' => 'Europe/Rome'
                    ],
                    'tipo_dato' => 1,
                    'livello' => 264.35,
                    'delta' => 0,
                    'media livello' => 264.35,
                    'altezza' => -12.15,
                    'portata' => 0,
                    'volume' => 0
                ]
            ],
            'no full and field' => [
                'var' => '30030',
                'datefrom' => '01/02/2018',
                'dateto' => '02/02/2018',
                'full' => null,
                'field' => null,
                'expected' => [
                    'tipo_formula' => 'portata sfiorante',
                    'alias' => 'sfioro',
                    'scarico' => 1,
                    'mi' => 0.47,
                    'larghezza' => 40.5,
                    'quota' => 276.5,
                    'limite' => 942.67,
                    'variabile' => 30030,
                    'data_e_ora' => [
                        'date' => '2018-02-01 00:00:00.000000',
                        'timezone_type' => 3,
                        'timezone' => 'Europe/Rome'
                    ],
                    'tipo_dato' => 1,
                    'livello' => 264.35,
                    'delta' => 0,
                    'media livello' => 264.35,
                    'altezza' => -12.15,
                    'portata' => 0,
                    'volume' => 0
                ]
            ],
            'only datefrom' => [
                'var' => '30030',
                'datefrom' => '01/05/2020',
                'dateto' => null,
                'full' => null,
                'field' => null,
                'expected' => [
                    'tipo_formula' => 'portata sfiorante',
                    'alias' => 'sfioro',
                    'scarico' => 1,
                    'mi' => 0.47,
                    'larghezza' => 40.5,
                    'quota' => 276.5,
                    'limite' => 942.67,
                    'variabile' => 30030,
                    'data_e_ora' => [
                        'date' => '2020-05-01 00:00:00.000000',
                        'timezone_type' => 3,
                        'timezone' => 'Europe/Rome'
                    ],
                    'tipo_dato' => 1,
                    'livello' => 276.46,
                    'delta' => 0,
                    'media livello' => 276.46,
                    'altezza' => -0.04,
                    'portata' => 0,
                    'volume' => 0
                ]
            ],
            'only dateto' => [
                'var' => '30030',
                'datefrom' => null,
                'dateto' => '02/02/2018',
                'full' => null,
                'field' => null,
                'expected' => [
                    'tipo_formula' => 'portata sfiorante',
                    'alias' => 'sfioro',
                    'scarico' => 1,
                    'mi' => 0.47,
                    'larghezza' => 40.5,
                    'quota' => 276.5,
                    'limite' => 942.67,
                    'variabile' => 30030,
                    'data_e_ora' => [
                        'date' => '2018-02-01 00:00:00.000000',
                        'timezone_type' => 3,
                        'timezone' => 'Europe/Rome'
                    ],
                    'tipo_dato' => 1,
                    'livello' => 264.35,
                    'delta' => 0,
                    'media livello' => 264.35,
                    'altezza' => -12.15,
                    'portata' => 0,
                    'volume' => 0
                ]
            ],
            'variable' => [
                'variable' => '30030',
                'datefrom' => '01/02/2018',
                'dateto' => '02/02/2018',
                'full' => null,
                'field' => null,
                'expected' => [
                    'tipo_formula' => 'portata sfiorante',
                    'alias' => 'sfioro',
                    'scarico' => 1,
                    'mi' => 0.47,
                    'larghezza' => 40.5,
                    'quota' => 276.5,
                    'limite' => 942.67,
                    'variabile' => 30030,
                    'data_e_ora' => [
                        'date' => '2018-02-01 00:00:00.000000',
                        'timezone_type' => 3,
                        'timezone' => 'Europe/Rome'
                    ],
                    'tipo_dato' => 1,
                    'livello' => 264.35,
                    'delta' => 0,
                    'media livello' => 264.35,
                    'altezza' => -12.15,
                    'portata' => 0,
                    'volume' => 0
                ]
            ],
            'variabile' => [
                'variabile' => '30030',
                'datefrom' => '01/02/2018',
                'dateto' => '02/02/2018',
                'full' => null,
                'field' => null,
                'expected' => [
                    'tipo_formula' => 'portata sfiorante',
                    'alias' => 'sfioro',
                    'scarico' => 1,
                    'mi' => 0.47,
                    'larghezza' => 40.5,
                    'quota' => 276.5,
                    'limite' => 942.67,
                    'variabile' => 30030,
                    'data_e_ora' => [
                        'date' => '2018-02-01 00:00:00.000000',
                        'timezone_type' => 3,
                        'timezone' => 'Europe/Rome'
                    ],
                    'tipo_dato' => 1,
                    'livello' => 264.35,
                    'delta' => 0,
                    'media livello' => 264.35,
                    'altezza' => -12.15,
                    'portata' => 0,
                    'volume' => 0
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group main
     * covers topbi.php
     * @dataProvider toPbiProvider
     */
    public function testToPbiEquals(?string $var, ?string $dateFrom, ?string $dateTo, ?string $full, ?string $field, ?array $expected) : void
    {
        $url = 'http://localhost/scarichi/topbi.php';
        
        $paramsRaw = [
            'var' => $var,
            'datefrom' => $dateFrom,
            'dateto' => $dateTo,
            'full' => $full,
            'field' => $field
        ];
        
        $params = array_filter($paramsRaw, function ($value) {
            return !is_null($value) && $value !== '';
        });        

        $json = Curl::run($params, $url);
        
        $arrJson = json_decode($json, true);
        $actual = $arrJson[0];
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group main
     * covers topbi.php
     */
    public function testToPbiException() : void
    {
        $url = 'http://localhost/scarichi/topbi.php';
        
        $params = [
            'var' => '40000',
            'datefrom' => '01/01/2017',
            'dateto' => '02/01/2017',
            'full' => null,
            'field' => null
        ]; 
        
        $expected = [
            'File' => '/var/www/html/telecontrollo/scarichi/github/src/tools.php',
            'Linea' => 53,
            'Codice errore' => 0,
            'Messaggio di errore' => 'Variabile non analizzabile. Valori ammessi compresi fra 30000 e 39999'
        ];
                
        $json = Curl::run($params, $url);
        
        $arrJson = json_decode($json, true);
        $actual = $arrJson['Descrizione errore'];
        
        $this->assertEquals($expected, $actual);        
    }
}
