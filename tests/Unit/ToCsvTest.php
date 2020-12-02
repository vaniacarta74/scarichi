<?php
namespace vaniacarta74\Scarichi\tests\Unit;

use PHPUnit\Framework\TestCase;
use vaniacarta74\Scarichi\Curl;

class ToCsvTest extends TestCase
{
    
    /**
     * coversNothing
     */
    public function toCsvProvider() : array
    {
        $data = [
            'standard' => [
                'var' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => '1',
                'field' => 'volume',
                'expected' => 'Elaborazione dati <b>Volume</b> variabile <b>30030</b> dal <b>01/01/2017</b> al <b>02/01/2017</b> avvenuta con successo in <b>| sec</b>. File CSV <b>full</b> esportati.'
            ],
            'no full and field' => [
                'var' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => null,
                'field' => null,
                'expected' => 'Elaborazione dati <b>Volume</b> variabile <b>30030</b> dal <b>01/01/2017</b> al <b>02/01/2017</b> avvenuta con successo in <b>| sec</b>. File CSV <b>full</b> esportati.'
            ],
            'only datefrom' => [
                'var' => '30030',
                'datefrom' => '01/05/2020',
                'dateto' => null,
                'full' => null,
                'field' => null,
                'expected' => 'Elaborazione dati <b>Volume</b> variabile <b>30030</b> dal <b>01/05/2020</b> al <b>' . date('d/m/Y') . '</b> avvenuta con successo in <b>| sec</b>. File CSV <b>full</b> esportati.'
            ],
            'only dateto' => [
                'var' => '30030',
                'datefrom' => null,
                'dateto' => '01/01/2017',
                'full' => null,
                'field' => null,
                'expected' => 'Elaborazione dati <b>Volume</b> variabile <b>30030</b> dal <b>31/12/2016</b> al <b>01/01/2017</b> avvenuta con successo in <b>| sec</b>. File CSV <b>full</b> esportati.'
            ],
            'variable' => [
                'variable' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => null,
                'field' => null,
                'expected' => 'Elaborazione dati <b>Volume</b> variabile <b>30030</b> dal <b>01/01/2017</b> al <b>02/01/2017</b> avvenuta con successo in <b>| sec</b>. File CSV <b>full</b> esportati.'
            ],
            'variabile' => [
                'variabile' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => null,
                'field' => null,
                'expected' => 'Elaborazione dati <b>Volume</b> variabile <b>30030</b> dal <b>01/01/2017</b> al <b>02/01/2017</b> avvenuta con successo in <b>| sec</b>. File CSV <b>full</b> esportati.'
            ],
            'field other' => [
                'var' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => null,
                'field' => 'livello',
                'expected' => 'Elaborazione dati <b>Livello</b> variabile <b>30030</b> dal <b>01/01/2017</b> al <b>02/01/2017</b> avvenuta con successo in <b>| sec</b>. File CSV <b>full</b> esportati.'
            ],
            'full 0' => [
                'var' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => '0',
                'field' => null,
                'expected' => 'Elaborazione dati <b>Volume</b> variabile <b>30030</b> dal <b>01/01/2017</b> al <b>02/01/2017</b> avvenuta con successo in <b>| sec</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.'
            ],
            'full 1' => [
                'var' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => '1',
                'field' => null,
                'expected' => 'Elaborazione dati <b>Volume</b> variabile <b>30030</b> dal <b>01/01/2017</b> al <b>02/01/2017</b> avvenuta con successo in <b>| sec</b>. File CSV <b>full</b> esportati.'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group main
     * covers tocsv.php
     * @dataProvider toCsvProvider
     */
    public function testToCsvEquals(?string $var, ?string $dateFrom, ?string $dateTo, ?string $full, ?string $field, ?string $response) : void
    {
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

        $actual = Curl::run(URL, $params);
        
        $expecteds = explode('|', $response);
        
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);
        }
    }
}
