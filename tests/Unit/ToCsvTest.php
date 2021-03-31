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
                'expected' => '{
                    "ok":true,
                    "response":{
                        "params":{
                            "var":"30030",
                            "datefrom":"01\/01\/2017 00:00:00",
                            "dateto":"02\/01\/2017 00:00:00",
                            "full":true,
                            "field":"volume"
                        },
                        "header":[
                            "Procedura esportazione CSV dati movimentazioni dighe",
                            "Elaborazione iniziata in data: |"
                        ],
                        "body":[
                            "Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2017<\/b> al <b>02\/01\/2017<\/b> avvenuta con successo in <b>| sec<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b>"
                        ],
                        "footer":[
                            "Elaborazione terminata in data: |",
                            "Tempo di elaborazione: | sec",
                            "Numero totale file csv esportati: |"
                        ]
                    }
                }'
            ],
            'no full and field' => [
                'var' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => null,
                'field' => null,
                'expected' => '{
                    "ok":true,
                    "response":{
                        "params":{
                            "var":"30030",
                            "datefrom":"01\/01\/2017 00:00:00",
                            "dateto":"02\/01\/2017 00:00:00",
                            "full":true,
                            "field":"volume"
                        },
                        "header":[
                            "Procedura esportazione CSV dati movimentazioni dighe",
                            "Elaborazione iniziata in data: |"
                        ],
                        "body":[
                            "Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2017<\/b> al <b>02\/01\/2017<\/b> avvenuta con successo in <b>| sec<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b>"
                        ],
                        "footer":[
                            "Elaborazione terminata in data: |",
                            "Tempo di elaborazione: | sec",
                            "Numero totale file csv esportati: |"
                        ]
                    }
                }'
            ],
            'only datefrom' => [
                'var' => '30030',
                'datefrom' => '01/05/2020',
                'dateto' => null,
                'full' => null,
                'field' => null,
                'expected' => '{
                    "ok":true,
                    "response":{
                        "params":{
                            "var":"30030",
                            "datefrom":"01\/05\/2020 00:00:00",
                            "dateto":"' . date('d') . '\/' . date('m') . '\/' . date('Y') . ' 00:00:00",
                            "full":true,
                            "field":"volume"
                        },
                        "header":[
                            "Procedura esportazione CSV dati movimentazioni dighe",
                            "Elaborazione iniziata in data: |"
                        ],
                        "body":[
                            "Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/05\/2020<\/b> al <b>' . date('d') . '\/' . date('m') . '\/' . date('Y') . '<\/b> avvenuta con successo in <b>| sec<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b>"
                        ],
                        "footer":[
                            "Elaborazione terminata in data: |",
                            "Tempo di elaborazione: | sec",
                            "Numero totale file csv esportati: |"
                        ]
                    }
                }'
            ],
            'only dateto' => [
                'var' => '30030',
                'datefrom' => null,
                'dateto' => '01/01/2017',
                'full' => null,
                'field' => null,
                'expected' => '{
                    "ok":true,
                    "response":{
                        "params":{
                            "var":"30030",
                            "datefrom":"31\/12\/2016 00:00:00",
                            "dateto":"01\/01\/2017 00:00:00",
                            "full":true,
                            "field":"volume"
                        },
                        "header":[
                            "Procedura esportazione CSV dati movimentazioni dighe",
                            "Elaborazione iniziata in data: |"
                        ],
                        "body":[
                            "Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>31\/12\/2016<\/b> al <b>01\/01\/2017<\/b> avvenuta con successo in <b>| sec<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b>"
                        ],
                        "footer":[
                            "Elaborazione terminata in data: |",
                            "Tempo di elaborazione: | sec",
                            "Numero totale file csv esportati: |"
                        ]
                    }
                }'
            ],
            'variable' => [
                'variable' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => null,
                'field' => null,
                'expected' => '{
                    "ok":true,
                    "response":{
                        "params":{
                            "var":"30030",
                            "datefrom":"01\/01\/2017 00:00:00",
                            "dateto":"02\/01\/2017 00:00:00",
                            "full":true,
                            "field":"volume"
                        },
                        "header":[
                            "Procedura esportazione CSV dati movimentazioni dighe",
                            "Elaborazione iniziata in data: |"
                        ],
                        "body":[
                            "Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2017<\/b> al <b>02\/01\/2017<\/b> avvenuta con successo in <b>| sec<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b>"
                        ],
                        "footer":[
                            "Elaborazione terminata in data: |",
                            "Tempo di elaborazione: | sec",
                            "Numero totale file csv esportati: |"
                        ]
                    }
                }'
            ],
            'variabile' => [
                'variabile' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => null,
                'field' => null,
                'expected' => '{
                    "ok":true,
                    "response":{
                        "params":{
                            "var":"30030",
                            "datefrom":"01\/01\/2017 00:00:00",
                            "dateto":"02\/01\/2017 00:00:00",
                            "full":true,
                            "field":"volume"
                        },
                        "header":[
                            "Procedura esportazione CSV dati movimentazioni dighe",
                            "Elaborazione iniziata in data: |"
                        ],
                        "body":[
                            "Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2017<\/b> al <b>02\/01\/2017<\/b> avvenuta con successo in <b>| sec<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b>"
                        ],
                        "footer":[
                            "Elaborazione terminata in data: |",
                            "Tempo di elaborazione: | sec",
                            "Numero totale file csv esportati: |"
                        ]
                    }
                }'
            ],
            'field other' => [
                'var' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => null,
                'field' => 'livello',
                'expected' => '{
                    "ok":true,
                    "response":{
                        "params":{
                            "var":"30030",
                            "datefrom":"01\/01\/2017 00:00:00",
                            "dateto":"02\/01\/2017 00:00:00",
                            "full":true,
                            "field":"livello"
                        },
                        "header":[
                            "Procedura esportazione CSV dati movimentazioni dighe",
                            "Elaborazione iniziata in data: |"
                        ],
                        "body":[
                            "Elaborazione dati <b>Livello<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2017<\/b> al <b>02\/01\/2017<\/b> avvenuta con successo in <b>| sec<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b>"
                        ],
                        "footer":[
                            "Elaborazione terminata in data: |",
                            "Tempo di elaborazione: | sec",
                            "Numero totale file csv esportati: |"
                        ]
                    }
                }'
            ],
            'full 0' => [
                'var' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => '0',
                'field' => null,
                'expected' => '{
                    "ok":true,
                    "response":{
                        "params":{
                            "var":"30030",
                            "datefrom":"01\/01\/2017 00:00:00",
                            "dateto":"02\/01\/2017 00:00:00",
                            "full":false,
                            "field":"volume"
                        },
                        "header":[
                            "Procedura esportazione CSV dati movimentazioni dighe",
                            "Elaborazione iniziata in data: |"
                        ],
                        "body":[
                            "Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2017<\/b> al <b>02\/01\/2017<\/b> avvenuta con successo in <b>| sec<\/b>. Nessun file CSV <b>senza zeri<\/b> esportato per mancanza di dati."
                        ],
                        "footer":[
                            "Elaborazione terminata in data: |",
                            "Tempo di elaborazione: | sec",
                            "Numero totale file csv esportati: |"
                        ]
                    }
                }'
            ],
            'full 1' => [
                'var' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'full' => '1',
                'field' => null,
                'expected' => '{
                    "ok":true,
                    "response":{
                        "params":{
                            "var":"30030",
                            "datefrom":"01\/01\/2017 00:00:00",
                            "dateto":"02\/01\/2017 00:00:00",
                            "full":true,
                            "field":"volume"
                        },
                        "header":[
                            "Procedura esportazione CSV dati movimentazioni dighe",
                            "Elaborazione iniziata in data: |"
                        ],
                        "body":[
                            "Elaborazione dati <b>Volume<\/b> variabile <b>30030<\/b> dal <b>01\/01\/2017<\/b> al <b>02\/01\/2017<\/b> avvenuta con successo in <b>| sec<\/b>. File CSV <b>full<\/b> esportati: <b>|<\/b>"
                        ],
                        "footer":[
                            "Elaborazione terminata in data: |",
                            "Tempo di elaborazione: | sec",
                            "Numero totale file csv esportati: |"
                        ]
                    }
                }'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group main
     * covers tocsv.php
     * @dataProvider toCsvProvider
     */
    public function testToCsvEquals(?string $var, ?string $dateFrom, ?string $dateTo, ?string $full, ?string $field, ?string $rawResponse) : void
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

        $actual = Curl::run(TOCSVURL, 'POST', $params);
        
        $response = json_encode(json_decode($rawResponse, true));
        
        $expecteds = explode('|', $response);
        
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);
        }
    }
}
