<?php
namespace vaniacarta74\Scarichi\tests\Unit;

use PHPUnit\Framework\TestCase;
use vaniacarta74\Scarichi\Curl;

class ToJsonTest extends TestCase
{
    
    /**
     * coversNothing
     */
    public function toJsonProvider() : array
    {
        $data = [
            'standard' => [
                'var' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => '1',
                'field' => 'volume',
                'expected' => [
                    'variabile' => '30030',
                    'data_e_ora' => '01/01/2017 00:00:00',
                    'tipo_dato' => '1',
                    'valore' => '0'
                ]
            ],
            'no full and field' => [
                'var' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => null,
                'field' => null,
                'expected' => [
                    'variabile' => '30030',
                    'data_e_ora' => '01/01/2017 00:00:00',
                    'tipo_dato' => '1',
                    'valore' => '0'
                ]
            ],
            'only datefrom' => [
                'var' => '30030',
                'datefrom' => '01/05/2020',
                'dateto' => null,
                'full' => null,
                'field' => null,
                'expected' => [
                    'variabile' => '30030',
                    'data_e_ora' => '01/05/2020 00:00:00',
                    'tipo_dato' => '1',
                    'valore' => '0'
                ]
            ],
            'only dateto' => [
                'var' => '30030',
                'datefrom' => null,
                'dateto' => '01/01/2017',
                'full' => null,
                'field' => null,
                'expected' => [
                    'variabile' => '30030',
                    'data_e_ora' => '31/12/2016 00:00:00',
                    'tipo_dato' => '1',
                    'valore' => '0'
                ]
            ],
            'variable' => [
                'variable' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => null,
                'field' => null,
                'expected' => [
                    'variabile' => '30030',
                    'data_e_ora' => '01/01/2017 00:00:00',
                    'tipo_dato' => '1',
                    'valore' => '0'
                ]
            ],
            'variabile' => [
                'variabile' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => null,
                'field' => null,
                'expected' => [
                    'variabile' => '30030',
                    'data_e_ora' => '01/01/2017 00:00:00',
                    'tipo_dato' => '1',
                    'valore' => '0'
                ]
            ],
            'field other' => [
                'var' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => null,
                'field' => 'livello',
                'expected' => [
                    'variabile' => '30030',
                    'data_e_ora' => '01/01/2017 00:00:00',
                    'tipo_dato' => '1',
                    'valore' => '269,109'
                ]
            ],
            'full 0' => [
                'var' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => '0',
                'field' => 'livello',
                'expected' => [
                    'variabile' => '30030',
                    'data_e_ora' => '01/01/2017 00:00:00',
                    'tipo_dato' => '1',
                    'valore' => '269,109'
                ]
            ],
            'full 1' => [
                'var' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => '1',
                'field' => null,
                'expected' => [
                    'variabile' => '30030',
                    'data_e_ora' => '01/01/2017 00:00:00',
                    'tipo_dato' => '1',
                    'valore' => '0'
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group main
     * covers tojson.php
     * @dataProvider toJsonProvider
     */
    public function testToJsonEquals(?string $var, ?string $dateFrom, ?string $dateTo, ?string $full, ?string $field, ?array $expected) : void
    {
        $url = 'http://localhost/scarichi/tojson.php';
        
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
     * covers tojson.php
     */
    public function testToJsonException() : void
    {
        $url = 'http://localhost/scarichi/tojson.php';
        
        $params = [
            'var' => '30030',
            'datefrom' => '01/01/2017',
            'dateto' => '02/01/2017',
            'full' => '1',
            'field' => 'pippo'
        ]; 
        
        $expected = [
            'File' => '/var/www/html/telecontrollo/scarichi/github/src/tools.php',
            'Linea' => 80,
            'Codice errore' => 0,
            'Messaggio di errore' => 'Nome campo non supportato. Valori ammessi: livello, livello valle, manovra, media livello, media livello valle, altezza, portata, delta, volume o L, LV, P, ML, MLV, H, Q, D, V'
        ];
                
        $json = Curl::run($params, $url);
        
        $arrJson = json_decode($json, true);
        $actual = $arrJson['Descrizione errore'];
        
        $this->assertEquals($expected, $actual);        
    }
}
