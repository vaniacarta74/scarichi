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
                'datefrom' => '29/05/2020',
                'dateto' => '30/05/2020',
                'provider' => 'toPbiStandard.json'
            ],            
            'only datefrom' => [
                'var' => '30030',
                'datefrom' => '29/05/2020',
                'dateto' => null,
                'provider' => 'toPbiStandard.json'
            ],
            'only dateto' => [
                'var' => '30030',
                'datefrom' => null,
                'dateto' => '30/05/2020',
                'provider' => 'toPbiStandard.json'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group main
     * covers topbi.php
     * @dataProvider toPbiProvider
     */
    public function testToPbiJsonStringEqualsJsonFile(?string $var, ?string $dateFrom, ?string $dateTo, string $provider) : void
    {
        $url = 'http://localhost/scarichi/topbi.php';
        
        $paramsRaw = [
            'var' => $var,
            'datefrom' => $dateFrom,
            'dateto' => $dateTo,
        ];
        
        $params = array_filter($paramsRaw, function ($value) {
            return !is_null($value) && $value !== '';
        });        

        $expected = __DIR__ . '/../providers/' . $provider;

        $actual = Curl::run($url, $params);
        
        $this->assertJsonStringEqualsJsonFile($expected, $actual);
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
        ]; 
        
        $expected = __DIR__ . '/../providers/toPbiException.json';

        $actual = Curl::run($url, $params);
        
        $this->assertJsonStringEqualsJsonFile($expected, $actual);       
    }
}
