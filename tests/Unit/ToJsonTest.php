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
                'provider' => 'toJsonStandard.json'
            ],
            'no full and field' => [
                'var' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => null,
                'field' => null,
                'provider' => 'toJsonStandard.json'
            ],
            'only datefrom' => [
                'var' => '30030',
                'datefrom' => '29/05/2020',
                'dateto' => null,
                'full' => null,
                'field' => null,
                'provider' => 'toJsonDatefrom.json'
            ],
            'only dateto' => [
                'var' => '30030',
                'datefrom' => null,
                'dateto' => '02/01/2017',
                'full' => null,
                'field' => null,
                'provider' => 'toJsonStandard.json'
            ],
            'variable' => [
                'variable' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => null,
                'field' => null,
                'provider' => 'toJsonStandard.json'
            ],
            'field other' => [
                'var' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => null,
                'field' => 'livello',
                'provider' => 'toJsonOther.json'
            ],
            'full 0' => [
                'var' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => '0',
                'field' => 'livello',
                'provider' => 'toJsonNoFull.json'
            ],
            'full 1' => [
                'var' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => '1',
                'field' => null,
                'provider' => 'toJsonStandard.json'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group main
     * covers tojson.php
     * @dataProvider toJsonProvider
     */
    public function testToJsonJsonStringEqualsJsonFile(?string $var, ?string $dateFrom, ?string $dateTo, ?string $full, ?string $field, string $provider) : void
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

        $expected = __DIR__ . '/../providers/' . $provider;

        $actual = Curl::run($params, $url);
        
        $this->assertJsonStringEqualsJsonFile($expected, $actual);             
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
        
        $expected = __DIR__ . '/../providers/toJsonException.json';
                
        $actual = Curl::run($params, $url);
        
        $this->assertJsonStringEqualsJsonFile($expected, $actual);        
    }
}
