<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi\tests\Unit;

use PHPUnit\Framework\TestCase;
use vaniacarta74\Scarichi\Curl;

/**
 * Description of CurlTest
 *
 * @author Vania
 */
class CurlTest extends TestCase
{
    /**
     * @group curl
     * @coversNothing
     */
    public function runProvider()
    {
        $data = [
            'get no params' => [
                'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php',
                'method' => 'GET',
                'params' => null,
                'json' => null,                
                'expected' => '{
                    "ok": true,
                    "response": {
                        "method": "GET",
                        "params": []
                    }
                }'
            ],
            'get' => [
                'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30030&datefrom=01/01/2020',
                'method' => 'GET',
                'params' => null,
                'json' => null,                
                'expected' => '{
                    "ok": true,
                    "response": {
                        "method": "GET",
                        "params": {
                            "var": "30030",
                            "datefrom": "01/01/2020"
                        }
                    }
                }'
            ],
            'post json' => [
                'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?json=1',
                'method' => 'POST',
                'params' => [
                    "var" => 10230,
                    "type" => 2,
                    "date" => "01/01/2021",
                    "val" => 3.5
                ],
                'json' => true,
                'expected' => '{
                    "ok": true,
                    "response": {
                        "method": "POST",
                        "params": {
                            "var": 10230,
                            "type": 2,
                            "date": "01/01/2021",
                            "val": 3.5
                        }
                    }
                }'
            ],
            'post no json' => [
                'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?json=0',
                'method' => 'POST',
                'params' => [
                    "var" => 10230,
                    "type" => 2,
                    "date" => "01/01/2021",
                    "val" => 3.5
                ],
                'json' => null,
                'expected' => '{
                    "ok": true,
                    "response": {
                        "method": "POST",
                        "params": {
                            "var": "10230",
                            "type": "2",
                            "date": "01/01/2021",
                            "val": "3,5"
                        }
                    }
                }'
            ],
            'put' => [
                'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?val=1.9&date=02/01/2020',
                'method' => 'PUT',
                'params' => null,
                'json' => null,
                'expected' => '{
                    "ok": true,
                    "response": {
                        "method": "PUT",
                        "params": {
                            "date": "02/01/2020",
                            "val": "1.9"
                        }
                    }
                }'
            ],
            'patch' => [
                'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?val=0.3&date=03/01/2020',
                'method' => 'PATCH',
                'params' => null,
                'json' => null,
                'expected' => '{
                    "ok": true,
                    "response": {
                        "method": "PATCH",
                        "params": {
                            "date": "03/01/2020",
                            "val": "0.3"
                        }
                    }
                }'
            ],
            'delete' => [
                'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?id=999999',
                'method' => 'DELETE',
                'params' => null,
                'json' => null,
                'expected' => '{
                    "ok": true,
                    "response": {
                        "method": "DELETE",
                        "params": {
                            "id": "999999"
                        }
                    }
                }'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::run
     * @covers \vaniacarta74\Scarichi\Curl::exec
     * @dataProvider runProvider     
     */
    public function testRunEqualsJsonString($url, $method, $params, $json, $expected)
    {
        $actual = Curl::run($url, $method, $params, $json);
        
        $this->assertJsonStringEqualsJsonString($expected, $actual);
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::run
     */
    public function testRunPostContainsString() : void
    {
        $params = [
            'var' => '30030',
            'datefrom' => '30/12/2019',
            'dateto' => '31/12/2019',
            'field' => 'portata',
            'full' => '0'
        ];
        $url = 'http://' . LOCALHOST . '/scarichi/tocsv.php';
        $json = false;
        $response = 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>|</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.';
        $expecteds = explode('|', $response);
        
        $actual = Curl::run($url, 'POST', $params, $json);
        
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::run
     */
    public function testRunGetContainsString() : void
    {
        $url = 'http://' . LOCALHOST . '/scarichi/tocsv.php?var=30030&datefrom=30/12/2019&dateto=31/12/2019&field=portata&full=0';
        $response = 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>|</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.';
        $expecteds = explode('|', $response);
        
        $actual = Curl::run($url);
        
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::run
     */
    public function testRunJsonStringEqualsJsonFile() : void
    {
        $params = [
            'var' => '30030',
            'datefrom' => '23/04/2020',
            'dateto' => '24/04/2020',
            'field' => 'portata',
            'full' => '0'
        ];
        $url = 'http://' . LOCALHOST . '/scarichi/tojson.php';
        $json = true;
        $expected = __DIR__ . '/../providers/curlRunTest.json';
        
        $actual = Curl::run($url, 'POST', $params, $json);
        
        $this->assertJsonStringEqualsJsonFile($expected, $actual);
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::run
     */
    public function testRunPostException() : void
    {
        $params = [];
        $url = 'http://' . LOCALHOST . '/scarichi/tocsv.php';
        
        $this->expectException(\Exception::class);
        
        Curl::run($url, 'POST', $params);
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::run
     */
    public function testRunException()
    {
        
        $url = 'http://' . LOCALHOST . '/scarichi/tocsv.php';
        $method = 'POST';
        $params = [];
        
        $this->expectException(\Exception::class);
        
        Curl::run($url, $method, $params);
    }
    
    /**
     * @group curl
     * @coversNothing
     */
    public function setProvider()
    {
        $data = [
            'get no params' => [
                'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php',
                'method' => 'GET',
                'params' => null,
                'json' => null
            ],
            'get' => [
                'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30030&datefrom=01/01/2020',
                'method' => 'GET',
                'params' => null,
                'json' => null
            ],
            'post json' => [
                'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?json=1',
                'method' => 'POST',
                'params' => [
                    "var" => 10230,
                    "type" => 2,
                    "date" => "01/01/2021",
                    "val" => 3.5
                ],
                'json' => true
            ],
            'post no json' => [
                'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?json=0',
                'method' => 'POST',
                'params' => [
                    "var" => 10230,
                    "type" => 2,
                    "date" => "01/01/2021",
                    "val" => 3.5
                ],
                'json' => null
            ],
            'put' => [
                'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?val=1.9&date=02/01/2020',
                'method' => 'PUT',
                'params' => null,
                'json' => null
            ],
            'patch' => [
                'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?val=0.3&date=03/01/2020',
                'method' => 'PATCH',
                'params' => null,
                'json' => null
            ],
            'delete' => [
                'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?id=999999',
                'method' => 'DELETE',
                'params' => null,
                'json' => null
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::set
     * @dataProvider setProvider     
     */
    public function testSetIsResource($url, $method, $params, $json)
    {
        $actual = is_resource(Curl::set($url, $method, $params, $json));
        
        $this->assertTrue($actual);
    }   
        
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::set     
     */
    public function testSetPostIsResource()
    {
        $params = [
            'var' => '30030',
            'datefrom' => '30/12/2019',
            'dateto' => '31/12/2019',
            'field' => 'portata',
            'full' => '0'
        ];
        $url = 'http://' . LOCALHOST . '/scarichi/tocsv.php';
        
        $actual = Curl::set($url, 'POST', $params);
        
        $this->assertIsResource($actual);
        
        return $actual;
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::set     
     */
    public function testSetGetIsResource()
    {
        $url = 'http://' . LOCALHOST . '/scarichi/tocsv.php';
        
        $actual = Curl::set($url, 'GET');
        
        $this->assertIsResource($actual);
        
        return $actual;
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::set     
     */
    public function testSetIsJsonIsResource()
    {
        $params = [
            'var' => '30030',
            'datefrom' => '30/12/2019',
            'dateto' => '31/12/2019',
            'field' => 'portata',
            'full' => '0'
        ];
        $url = 'http://' . LOCALHOST . '/scarichi/tojson.php';
        
        $actual = Curl::set($url, 'POST', $params, true);
        
        $this->assertIsResource($actual);
        
        return $actual;
    }
    
    /**
     * @group curl
     * @coversNothing
     */
    public function setExceptionProvider()
    {
        $data = [            
            'wrong method' => [
                'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php',
                'method' => 'PIPPO',
                'params' => null,
                'json' => null
            ],
            'post no params' => [
                'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php',
                'method' => 'POST',
                'params' => null,
                'json' => null
            ],
            'post void params' => [
                'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php',
                'method' => 'POST',
                'params' => [],
                'json' => null
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::set
     * @dataProvider setExceptionProvider
     */
    public function testSetException($url, $method, $params, $json)
    {
        $this->expectException(\Exception::class);
        
        Curl::set($url, $method, $params, $json);
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::set
     */
    public function testSetPostException() : void
    {
        $params = [];
        $url = 'http://' . LOCALHOST . '/scarichi/tocsv.php';
        
        $this->expectException(\Exception::class);
        
        Curl::set($url, 'POST', $params);
    }   
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::exec
     * @depends testSetPostIsResource
     */
    public function testExecContainsString($ch) : void
    {
        $response = 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>|</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.';
        $expecteds = explode('|', $response);
        
        $actual = Curl::exec($ch);
        
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::exec
     */
    public function testExecNullException() : void
    {
        $ch = null;
        
        $this->expectException(\Exception::class);
        
        Curl::exec($ch);
    }
    
    /**
     * @coversNothing
     */
    public function multiSetProvider() : array
    {
        $url = TOCSVURL;
        
        $data = [
            'post no json no key' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => null
                    ]
                ]
            ],
            'post with key' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => '30030'
                    ]
                ]
            ],
            'post with key diff' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => 'pippo'
                    ]
                ]
            ],
            'post multi' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => '30030'
                    ],
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30040',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => 'pippo'
                    ]
                ]
            ],
            'post multi mixed' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => '30030'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?json=1',
                        'method' => 'POST',
                        'params' => [
                            'var' => '30040',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => true,
                        'key' => 'pippo'
                    ]
                ]
            ],
            'multi method' => [
                'setParams' => [
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30030&datefrom=01/01/2020',
                        'method' => 'GET',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var1'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php',
                        'method' => 'POST',
                        'params' => [
                            'var' => '30040',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => true,
                        'key' => 'var2'
                    ]
                ]
            ],
            'multi get' => [
                'setParams' => [
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30030&datefrom=01/01/2020',
                        'method' => 'GET',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var1'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30040&datefrom=01/01/2020',
                        'method' => 'GET',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var2'
                    ]
                ]
            ],
            'multi put patch' => [
                'setParams' => [
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30030&datefrom=01/01/2020',
                        'method' => 'PUT',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var1'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30040&datefrom=01/01/2020',
                        'method' => 'PATCH',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var2'
                    ]
                ]
            ],
            'multi delete' => [
                'setParams' => [
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?id=999998',
                        'method' => 'DELETE',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var1'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?id=999999',
                        'method' => 'DELETE',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var2'
                    ]
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::multiSet
     * @dataProvider multiSetProvider
     */
    public function testMultiSetIsResource(array $setParams) : void
    {
        $actuals = Curl::multiSet($setParams);
        
        foreach ($actuals as $actual) {
            $this->assertIsResource($actual);
        }
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::multiSet
     */
    public function testMultiSetException() : void
    {
        $setParams = [];
        
        $this->expectException(\Exception::class);
        
        Curl::multiSet($setParams);
    }
    
    /**
     * @coversNothing
     */
    public function runMultiSyncProvider() : array
    {
        $url = TOCSVURL;
        $single = 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>| sec</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.' . PHP_EOL;
        $multi = $single . 'Elaborazione dati <b>Portata</b> variabile <b>30040</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>| sec</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.' . PHP_EOL;
        
        $data = [
            'no callback' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'id' => '0',
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => 'id'
                    ]
                ],
                'callback' => null,
                'expected' => 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>|</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.'
            ],
            'no key' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'id' => '0',
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => null
                    ]
                ],
                'callback' => 'formatResponse',
                'expected' => 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>| sec</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.' . PHP_EOL
            ],
            'single post' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'id' => '0',
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => 'id'
                    ]
                ],
                'callback' => 'formatResponse',
                'expected' => $single
            ],
            'multi post' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'id' => '0',
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => '0'
                    ],
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'id' => '1',
                            'var' => '30040',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => 'id'
                    ]
                ],
                'callback' => 'formatResponse',
                'expected' => $multi
            ],
            'post with key diff' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => 'pippo'
                    ]
                ],
                'callback' => 'formatResponse',
                'expected' => $single
            ],
            'post multi' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => 'var'
                    ],
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30040',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => 'pippo'
                    ]
                ],
                'callback' => null,
                'expected' => $multi
            ],
            'post multi mixed' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => 'var'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?json=1',
                        'method' => 'POST',
                        'params' => [
                            'var' => '30040',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => true,
                        'key' => 'pippo'
                    ]
                ],
                'callback' => null,
                'expected' => $single . '{"ok":true,"response":{"method":"POST","params":{"var":"30040","datefrom":"30\/12\/2019","dateto":"31\/12\/2019","field":"portata","full":"0"}}}'
            ],
            'multi method' => [
                'setParams' => [
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30030&datefrom=01/01/2020',
                        'method' => 'GET',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var1'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?json=1',
                        'method' => 'POST',
                        'params' => [
                            'var' => '30040',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => true,
                        'key' => 'var2'
                    ]
                ],
                'callback' => null,
                'expected' => '{"ok":true,"response":{"method":"GET","params":{"var":"30030","datefrom":"01\/01\/2020"}}}' . PHP_EOL . '{"ok":true,"response":{"method":"POST","params":{"var":"30040","datefrom":"30\/12\/2019","dateto":"31\/12\/2019","field":"portata","full":"0"}}}'
            ],
            'multi get' => [
                'setParams' => [
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30030&datefrom=01/01/2020',
                        'method' => 'GET',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var1'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30040&datefrom=01/01/2020',
                        'method' => 'GET',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var2'
                    ]
                ],
                'callback' => null,
                'expected' => '{"ok":true,"response":{"method":"GET","params":{"var":"30030","datefrom":"01\/01\/2020"}}}' . PHP_EOL . '{"ok":true,"response":{"method":"GET","params":{"var":"30040","datefrom":"01\/01\/2020"}}}'
            ],
            'multi put patch' => [
                'setParams' => [
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30030&datefrom=01/01/2020',
                        'method' => 'PUT',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var1'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30040&datefrom=01/01/2020',
                        'method' => 'PATCH',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var2'
                    ]
                ],
                'callback' => null,
                'expected' => '{"ok":true,"response":{"method":"PUT","params":{"var":"30030","datefrom":"01\/01\/2020"}}}' . PHP_EOL . '{"ok":true,"response":{"method":"PATCH","params":{"var":"30040","datefrom":"01\/01\/2020"}}}'
            ],
            'multi delete' => [
                'setParams' => [
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?id=999998',
                        'method' => 'DELETE',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var1'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?id=999999',
                        'method' => 'DELETE',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var2'
                    ]
                ],
                'callback' => null,
                'expected' => '{"ok":true,"response":{"method":"DELETE","params":{"id":"999998"}}}' . PHP_EOL . '{"ok":true,"response":{"method":"DELETE","params":{"id":"999999"}}}'
            ]            
        ];
        
        return $data;
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::runMultiSync
     * @dataProvider runMultiSyncProvider
     */
    public function testRunMultiSyncEquals(array $setParams, ?string $funcName, string $response) : void
    {
        $expecteds = explode('|', $response);
        
        $actual = curl::runMultiSync($setParams, $funcName);
        
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @coversNothing
     */
    public function runMultiSyncEchoProvider() : array
    {
        $url = TOCSVURL;
        $single = '1) PID 0: Elaborazione dati Portata variabile 30030 dal 30/12/2019 al 31/12/2019 avvenuta con successo in | sec. Nessun file CSV senza zeri esportato per mancanza di dati.' . PHP_EOL;
        $multi = $single . '2) PID 1: Elaborazione dati Portata variabile 30040 dal 30/12/2019 al 31/12/2019 avvenuta con successo in | sec. Nessun file CSV senza zeri esportato per mancanza di dati.' . PHP_EOL;
        
        $data = [
            'no callback' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'id' => '0',
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => '0'
                    ]
                ],
                'callback' => null,
                'expected' => 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>|</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.'
            ],
            'no key' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'id' => '0',
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => null
                    ]
                ],
                'callback' => 'formatResponse',
                'expected' => '1) PID 0: Elaborazione dati Portata variabile 30030 dal 30/12/2019 al 31/12/2019 avvenuta con successo in | sec. Nessun file CSV senza zeri esportato per mancanza di dati.' . PHP_EOL
            ],
            'single post' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'id' => '0',
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => '0'
                    ]
                ],
                'callback' => 'formatResponse',
                'expected' => $single
            ],
            'multi post' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'id' => '0',
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => '0'
                    ],
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'id' => '1',
                            'var' => '30040',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => '1'
                    ]
                ],
                'callback' => 'formatResponse',
                'expected' => $multi
            ],
            'post with key diff' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => 'pippo'
                    ]
                ],
                'callback' => 'formatResponse',
                'expected' => '1) PID pippo: Elaborazione dati Portata variabile 30030 dal 30/12/2019 al 31/12/2019 avvenuta con successo in | sec. Nessun file CSV senza zeri esportato per mancanza di dati.' . PHP_EOL
            ],
            'post multi' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => '30030'
                    ],
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30040',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => 'pippo'
                    ]
                ],
                'callback' => 'formatResponse',
                'expected' => '1) PID 30030: Elaborazione dati Portata variabile 30030 dal 30/12/2019 al 31/12/2019 avvenuta con successo in | sec. Nessun file CSV senza zeri esportato per mancanza di dati.' . PHP_EOL . '2) PID pippo: Elaborazione dati Portata variabile 30040 dal 30/12/2019 al 31/12/2019 avvenuta con successo in | sec. Nessun file CSV senza zeri esportato per mancanza di dati.' . PHP_EOL
            ],
            'post multi mixed' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => 'var'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?json=1',
                        'method' => 'POST',
                        'params' => [
                            'var' => '30040',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => true,
                        'key' => 'pippo'
                    ]
                ],
                'callback' => null,
                'expected' => 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>| sec</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.{"ok":true,"response":{"method":"POST","params":{"var":"30040","datefrom":"30\/12\/2019","dateto":"31\/12\/2019","field":"portata","full":"0"}}}'
            ],
            'multi method' => [
                'setParams' => [
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30030&datefrom=01/01/2020',
                        'method' => 'GET',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var1'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?json=1',
                        'method' => 'POST',
                        'params' => [
                            'var' => '30040',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => true,
                        'key' => 'var2'
                    ]
                ],
                'callback' => null,
                'expected' => '{"ok":true,"response":{"method":"GET","params":{"var":"30030","datefrom":"01\/01\/2020"}}}{"ok":true,"response":{"method":"POST","params":{"var":"30040","datefrom":"30\/12\/2019","dateto":"31\/12\/2019","field":"portata","full":"0"}}}'
            ],
            'multi get' => [
                'setParams' => [
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30030&datefrom=01/01/2020',
                        'method' => 'GET',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var1'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30040&datefrom=01/01/2020',
                        'method' => 'GET',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var2'
                    ]
                ],
                'callback' => null,
                'expected' => '{"ok":true,"response":{"method":"GET","params":{"var":"30030","datefrom":"01\/01\/2020"}}}{"ok":true,"response":{"method":"GET","params":{"var":"30040","datefrom":"01\/01\/2020"}}}'
            ],
            'multi put patch' => [
                'setParams' => [
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30030&datefrom=01/01/2020',
                        'method' => 'PUT',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var1'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30040&datefrom=01/01/2020',
                        'method' => 'PATCH',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var2'
                    ]
                ],
                'callback' => null,
                'expected' => '{"ok":true,"response":{"method":"PUT","params":{"var":"30030","datefrom":"01\/01\/2020"}}}{"ok":true,"response":{"method":"PATCH","params":{"var":"30040","datefrom":"01\/01\/2020"}}}'
            ],
            'multi delete' => [
                'setParams' => [
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?id=999998',
                        'method' => 'DELETE',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var1'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?id=999999',
                        'method' => 'DELETE',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var2'
                    ]
                ],
                'callback' => null,
                'expected' => '{"ok":true,"response":{"method":"DELETE","params":{"id":"999998"}}}{"ok":true,"response":{"method":"DELETE","params":{"id":"999999"}}}'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::runMultiSync
     * @dataProvider runMultiSyncEchoProvider
     */
    public function testRunMultiSyncEchoOutputString(array $setParams, ?string $funcName, string $response) : void
    {
        $expecteds = explode('|', $response);
        
        curl::runMultiSync($setParams, $funcName);
        
        $actual = $this->getActualOutput();
        
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::runMultiSync
     */
    public function testRunMultiSyncDataException() : void
    {
        $setParams = [];
        $funcName = 'formatResponse';
        
        $this->expectException(\Exception::class);
        
        curl::runMultiSync($setParams, $funcName);
    }
    
    /**
     * @coversNothing
     */
    public function runMultiAsyncProvider() : array
    {
        $url = TOCSVURL;
        $single = 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>| sec</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.' . PHP_EOL;
        $multi = $single . 'Elaborazione dati <b>Portata</b> variabile <b>30040</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>| sec</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.' . PHP_EOL;
        
        $data = [
            'no callback' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'id' => '0',
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => 'id'
                    ]
                ],
                'callback' => null,
                'expected' => 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>|</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.'
            ],
            'no key' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'id' => '0',
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => null
                    ]
                ],
                'callback' => 'formatResponse',
                'expected' => 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>| sec</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.' . PHP_EOL
            ],
            'single post' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'id' => '0',
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => 'id'
                    ]
                ],
                'callback' => 'formatResponse',
                'expected' => $single
            ],
            'multi post' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'id' => '0',
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => '0'
                    ],
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'id' => '1',
                            'var' => '30040',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => '1'
                    ]
                ],
                'callback' => 'formatResponse',
                'expected' => $multi
            ],
            'post with key diff' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => 'pippo'
                    ]
                ],
                'callback' => 'formatResponse',
                'expected' => $single
            ],
            'post multi' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => 'var'
                    ],
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30040',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => 'pippo'
                    ]
                ],
                'callback' => null,
                'expected' => $multi
            ],
            'post multi mixed' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => 'var'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?json=1',
                        'method' => 'POST',
                        'params' => [
                            'var' => '30040',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => true,
                        'key' => 'pippo'
                    ]
                ],
                'callback' => null,
                'expected' => $single . '|{"ok":true,"response":{"method":"POST","params":{"var":"30040","datefrom":"30\/12\/2019","dateto":"31\/12\/2019","field":"portata","full":"0"}}}' . PHP_EOL
            ],
            'multi method' => [
                'setParams' => [
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30030&datefrom=01/01/2020',
                        'method' => 'GET',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var1'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?json=1',
                        'method' => 'POST',
                        'params' => [
                            'var' => '30040',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => true,
                        'key' => 'var2'
                    ]
                ],
                'callback' => null,
                'expected' => '{"ok":true,"response":{"method":"GET","params":{"var":"30030","datefrom":"01\/01\/2020"}}}' . PHP_EOL . '|{"ok":true,"response":{"method":"POST","params":{"var":"30040","datefrom":"30\/12\/2019","dateto":"31\/12\/2019","field":"portata","full":"0"}}}' . PHP_EOL
            ],
            'multi get' => [
                'setParams' => [
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30030&datefrom=01/01/2020',
                        'method' => 'GET',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var1'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30040&datefrom=01/01/2020',
                        'method' => 'GET',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var2'
                    ]
                ],
                'callback' => null,
                'expected' => '{"ok":true,"response":{"method":"GET","params":{"var":"30030","datefrom":"01\/01\/2020"}}}' . PHP_EOL . '|{"ok":true,"response":{"method":"GET","params":{"var":"30040","datefrom":"01\/01\/2020"}}}' . PHP_EOL
            ],
            'multi put patch' => [
                'setParams' => [
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30030&datefrom=01/01/2020',
                        'method' => 'PUT',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var1'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30040&datefrom=01/01/2020',
                        'method' => 'PATCH',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var2'
                    ]
                ],
                'callback' => null,
                'expected' => '{"ok":true,"response":{"method":"PUT","params":{"var":"30030","datefrom":"01\/01\/2020"}}}' . PHP_EOL . '|{"ok":true,"response":{"method":"PATCH","params":{"var":"30040","datefrom":"01\/01\/2020"}}}' . PHP_EOL
            ],
            'multi delete' => [
                'setParams' => [
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?id=999998',
                        'method' => 'DELETE',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var1'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?id=999999',
                        'method' => 'DELETE',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var2'
                    ]
                ],
                'callback' => null,
                'expected' => '{"ok":true,"response":{"method":"DELETE","params":{"id":"999998"}}}' . PHP_EOL . '|{"ok":true,"response":{"method":"DELETE","params":{"id":"999999"}}}' . PHP_EOL
            ] 
        ];
        
        return $data;
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::runMultiAsync
     * @dataProvider runMultiAsyncProvider
     */
    public function testRunMultiAsyncEquals(array $setParams, ?string $funcName, string $response) : void
    {
        $expecteds = explode('|', $response);
        
        $actual = curl::runMultiAsync($setParams, $funcName);
        
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @coversNothing
     */
    public function runMultiAsyncEchoProvider() : array
    {
        $url = TOCSVURL;
        $single = '1) PID 0: Elaborazione dati Portata variabile 30030 dal 30/12/2019 al 31/12/2019 avvenuta con successo in | sec. Nessun file CSV senza zeri esportato per mancanza di dati.' . PHP_EOL;
        $multi = $single . '2) PID 1: Elaborazione dati Portata variabile 30040 dal 30/12/2019 al 31/12/2019 avvenuta con successo in | sec. Nessun file CSV senza zeri esportato per mancanza di dati.' . PHP_EOL;
        
        $data = [
            'no callback' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'id' => '0',
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => '0'
                    ]
                ],
                'callback' => null,
                'expected' => 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>|</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.'
            ],
            'no key' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'id' => '0',
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => null
                    ]
                ],
                'callback' => 'formatResponse',
                'expected' => '1) PID 0: Elaborazione dati Portata variabile 30030 dal 30/12/2019 al 31/12/2019 avvenuta con successo in | sec. Nessun file CSV senza zeri esportato per mancanza di dati.' . PHP_EOL
            ],
            'single post' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'id' => '0',
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => '0'
                    ]
                ],
                'callback' => 'formatResponse',
                'expected' => $single
            ],
            'multi post' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'id' => '0',
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => '0'
                    ],
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'id' => '1',
                            'var' => '30040',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => '1'
                    ]
                ],
                'callback' => 'formatResponse',
                'expected' => $multi
            ],
            'post with key diff' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => 'pippo'
                    ]
                ],
                'callback' => 'formatResponse',
                'expected' => '1) PID pippo: Elaborazione dati Portata variabile 30030 dal 30/12/2019 al 31/12/2019 avvenuta con successo in | sec. Nessun file CSV senza zeri esportato per mancanza di dati.' . PHP_EOL
            ],
            'post multi' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => '30030'
                    ],
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30040',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => 'pippo'
                    ]
                ],
                'callback' => 'formatResponse',
                'expected' => '1) PID 30030: Elaborazione dati Portata variabile 30030 dal 30/12/2019 al 31/12/2019 avvenuta con successo in | sec. Nessun file CSV senza zeri esportato per mancanza di dati.' . PHP_EOL . '2) PID pippo: Elaborazione dati Portata variabile 30040 dal 30/12/2019 al 31/12/2019 avvenuta con successo in | sec. Nessun file CSV senza zeri esportato per mancanza di dati.' . PHP_EOL
            ],
            'post multi mixed' => [
                'setParams' => [
                    [
                        'url' => $url,
                        'method' => 'POST',
                        'params' => [
                            'var' => '30030',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => null,
                        'key' => 'var'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?json=1',
                        'method' => 'POST',
                        'params' => [
                            'var' => '30040',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => true,
                        'key' => 'pippo'
                    ]
                ],
                'callback' => null,
                'expected' => 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>| sec</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.|{"ok":true,"response":{"method":"POST","params":{"var":"30040","datefrom":"30\/12\/2019","dateto":"31\/12\/2019","field":"portata","full":"0"}}}'
            ],
            'multi method' => [
                'setParams' => [
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30030&datefrom=01/01/2020',
                        'method' => 'GET',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var1'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?json=1',
                        'method' => 'POST',
                        'params' => [
                            'var' => '30040',
                            'datefrom' => '30/12/2019',
                            'dateto' => '31/12/2019',
                            'field' => 'portata',
                            'full' => '0'
                        ],
                        'isJson' => true,
                        'key' => 'var2'
                    ]
                ],
                'callback' => null,
                'expected' => '{"ok":true,"response":{"method":"GET","params":{"var":"30030","datefrom":"01\/01\/2020"}}}|{"ok":true,"response":{"method":"POST","params":{"var":"30040","datefrom":"30\/12\/2019","dateto":"31\/12\/2019","field":"portata","full":"0"}}}'
            ],
            'multi get' => [
                'setParams' => [
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30030&datefrom=01/01/2020',
                        'method' => 'GET',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var1'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30040&datefrom=01/01/2020',
                        'method' => 'GET',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var2'
                    ]
                ],
                'callback' => null,
                'expected' => '{"ok":true,"response":{"method":"GET","params":{"var":"30030","datefrom":"01\/01\/2020"}}}|{"ok":true,"response":{"method":"GET","params":{"var":"30040","datefrom":"01\/01\/2020"}}}'
            ],
            'multi put patch' => [
                'setParams' => [
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30030&datefrom=01/01/2020',
                        'method' => 'PUT',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var1'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?var=30040&datefrom=01/01/2020',
                        'method' => 'PATCH',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var2'
                    ]
                ],
                'callback' => null,
                'expected' => '{"ok":true,"response":{"method":"PUT","params":{"var":"30030","datefrom":"01\/01\/2020"}}}|{"ok":true,"response":{"method":"PATCH","params":{"var":"30040","datefrom":"01\/01\/2020"}}}'
            ],
            'multi delete' => [
                'setParams' => [
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?id=999998',
                        'method' => 'DELETE',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var1'
                    ],
                    [
                        'url' => 'http://' . LOCALHOST . '/tests/providers/curlTest.php?id=999999',
                        'method' => 'DELETE',
                        'params' => [],
                        'isJson' => null,
                        'key' => 'var2'
                    ]
                ],
                'callback' => null,
                'expected' => '{"ok":true,"response":{"method":"DELETE","params":{"id":"999998"}}}|{"ok":true,"response":{"method":"DELETE","params":{"id":"999999"}}}'
            ] 
        ];
        
        return $data;
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::runMultiAsync
     * @dataProvider runMultiAsyncEchoProvider
     */
    public function testRunMultiAsyncEchoOutputString(array $setParams, ?string $funcName, string $response) : void
    {
        $expecteds = explode('|', $response);
        
        curl::runMultiAsync($setParams, $funcName);
        
        $actual = $this->getActualOutput();
        
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::runMultiAsync
     */
    public function testRunMultiAsyncDataException() : void
    {
        $setParams = [];
        $funcName = 'formatResponse';
        
        $this->expectException(\Exception::class);
        
        curl::runMultiAsync($setParams, $funcName);
    }
    
    
    
    
    
    
    
    
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::set2     
     */
    public function testSet2PostIsResource()
    {
        $params = [
            'var' => '30030',
            'datefrom' => '30/12/2019',
            'dateto' => '31/12/2019',
            'field' => 'portata',
            'full' => '0'
        ];
        $url = 'http://' . LOCALHOST . '/scarichi/tocsv.php';
        
        $actual = Curl::set2($url, $params);
        
        $this->assertIsResource($actual);
        
        return $actual;
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::set2     
     */
    public function testSet2GetIsResource()
    {
        $url = 'http://' . LOCALHOST . '/scarichi/tocsv.php';
        
        $actual = Curl::set2($url);
        
        $this->assertIsResource($actual);
        
        return $actual;
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::set2
     */
    public function testSet2Exception() : void
    {
        $params = [];
        $url = 'http://' . LOCALHOST . '/scarichi/tocsv.php';
        
        $this->expectException(\Exception::class);
        
        Curl::set2($url, $params);
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::setJson     
     */
    public function testSetJsonIsResource()
    {
        $params = [
            'var' => '30030',
            'datefrom' => '30/12/2019',
            'dateto' => '31/12/2019',
            'field' => 'portata',
            'full' => '0'
        ];
        $url = 'http://' . LOCALHOST . '/scarichi/tojson.php';
        
        $actual = Curl::setJson($url, $params);
        
        $this->assertIsResource($actual);
        
        return $actual;
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::setJson
     */
    public function testSetJsonException() : void
    {
        $params = [];
        $url = 'http://' . LOCALHOST . '/scarichi/tojson.php';
        
        $this->expectException(\Exception::class);
        
        Curl::setJson($url, $params);
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::exec2
     * @depends testSet2PostIsResource
     */
    public function testExec2ContainsString($ch) : void
    {
        $response = 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>|</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.';
        $expecteds = explode('|', $response);
        
        $actual = Curl::exec2($ch);
        
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::exec2
     */
    public function testExec2Exception() : void
    {
        $ch = null;
        
        $this->expectException(\Exception::class);
        
        Curl::exec2($ch);
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::run2
     */
    public function testRun2ContainsString() : void
    {
        $params = [
            'var' => '30030',
            'datefrom' => '30/12/2019',
            'dateto' => '31/12/2019',
            'field' => 'portata',
            'full' => '0'
        ];
        $url = 'http://' . LOCALHOST . '/scarichi/tocsv.php';
        $json = false;
        $response = 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>|</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.';
        $expecteds = explode('|', $response);
        
        $actual = Curl::run2($url, $params, $json);
        
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::run2
     */
    public function testRun2GetContainsString() : void
    {
        $url = 'http://' . LOCALHOST . '/scarichi/tocsv.php?var=30030&datefrom=30/12/2019&dateto=31/12/2019&field=portata&full=0';
        $response = 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>|</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.';
        $expecteds = explode('|', $response);
        
        $actual = Curl::run2($url);
        
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::run2
     */
    public function testRun2JsonStringEqualsJsonFile() : void
    {
        $params = [
            'var' => '30030',
            'datefrom' => '23/04/2020',
            'dateto' => '24/04/2020',
            'field' => 'portata',
            'full' => '0'
        ];
        $url = 'http://' . LOCALHOST . '/scarichi/tojson.php';
        $json = true;
        $expected = __DIR__ . '/../providers/curlRunTest.json';
        
        $actual = Curl::run2($url, $params, $json);
        
        $this->assertJsonStringEqualsJsonFile($expected, $actual);
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::run2
     */
    public function testRun2Exception() : void
    {
        $params = [];
        $url = 'http://' . LOCALHOST . '/scarichi/tocsv.php';
        
        $this->expectException(\Exception::class);
        
        Curl::run2($url, $params);
    }
    
    /**
     * @coversNothing
     */
    public function multiSet2Provider() : array
    {
        $url = TOCSVURL;
        
        $data = [
            'no key' => [
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
                'key' => null
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
                'key' => 'var'
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
                'key' => 'var'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::multiSet2
     * @dataProvider multiSet2Provider
     */
    public function testMultiSet2IsResource(array $postParam, string $url, ?string $key) : void
    {
        $actuals = Curl::multiSet2($url, $postParam, $key);
        
        foreach ($actuals as $actual) {
            $this->assertIsResource($actual);
        }
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::multiSet2
     */
    public function testMultiSet2KeyException() : void
    {
        $postParam = [
            [
                'var' => '30030',
                'datefrom' => '30/12/2019',
                'dateto' => '31/12/2019',
                'field' => 'portata',
                'full' => '0'
            ]
        ];
        $url = TOCSVURL;
        $key = 'pippo';
        
        $this->expectException(\Exception::class);
        
        Curl::multiSet2($url, $postParam, $key);
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::multiSet2
     */
    public function testMultiSet2DataException() : void
    {
        $postParam = [];
        $url = TOCSVURL;
        $key = 'var';
        
        $this->expectException(\Exception::class);
        
        Curl::multiSet2($url, $postParam, $key);
    }

    /**
     * @coversNothing
     */
    public function runMultiSync2Provider() : array
    {
        $url = TOCSVURL;
        $single = 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>| sec</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.' . PHP_EOL;
        $multi = $single . 'Elaborazione dati <b>Portata</b> variabile <b>30040</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>| sec</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.' . PHP_EOL;
        
        $data = [
            'no callback' => [
                'post' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '30/12/2019',
                        'dateto' => '31/12/2019',
                        'field' => 'portata',
                        'full' => '0'
                    ]
                ],
                'url' => $url,
                'key' => 'id',
                'callback' => null,
                'expected' => 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>|</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.'
            ],
            'no key' => [
                'post' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '30/12/2019',
                        'dateto' => '31/12/2019',
                        'field' => 'portata',
                        'full' => '0'
                    ]
                ],
                'url' => $url,
                'key' => null,
                'callback' => 'formatResponse',
                'expected' => 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>| sec</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.' . PHP_EOL
            ],
            'single post' => [
                'post' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '30/12/2019',
                        'dateto' => '31/12/2019',
                        'field' => 'portata',
                        'full' => '0'
                    ]
                ],
                'url' => $url,
                'key' => 'id',
                'callback' => 'formatResponse',
                'expected' => $single
            ],
            'multi post' => [
                'post' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '30/12/2019',
                        'dateto' => '31/12/2019',
                        'field' => 'portata',
                        'full' => '0'
                    ],
                    [
                        'id' => '1',
                        'var' => '30040',
                        'datefrom' => '30/12/2019',
                        'dateto' => '31/12/2019',
                        'field' => 'portata',
                        'full' => '0'
                    ]
                ],
                'url' => $url,
                'key' => 'id',
                'callback' => 'formatResponse',
                'expected' => $multi
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::runMultiSync2
     * @dataProvider runMultiSync2Provider
     */
    public function testRunMultiSync2Equals(array $postParam, string $url, ?string $key, ?string $funcName, string $response) : void
    {
        $expecteds = explode('|', $response);
        
        $actual = curl::runMultiSync2($url, $postParam, $key, $funcName);
        
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @coversNothing
     */
    public function runMultiSync2EchoProvider() : array
    {
        $url = TOCSVURL;
        $single = '1) PID 0: Elaborazione dati Portata variabile 30030 dal 30/12/2019 al 31/12/2019 avvenuta con successo in | sec. Nessun file CSV senza zeri esportato per mancanza di dati.' . PHP_EOL;
        $multi = $single . '2) PID 1: Elaborazione dati Portata variabile 30040 dal 30/12/2019 al 31/12/2019 avvenuta con successo in | sec. Nessun file CSV senza zeri esportato per mancanza di dati.' . PHP_EOL;
        
        $data = [
            'no callback' => [
                'post' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '30/12/2019',
                        'dateto' => '31/12/2019',
                        'field' => 'portata',
                        'full' => '0'
                    ]
                ],
                'url' => $url,
                'key' => 'id',
                'callback' => null,
                'expected' => 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>|</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.'
            ],
            'no key' => [
                'post' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '30/12/2019',
                        'dateto' => '31/12/2019',
                        'field' => 'portata',
                        'full' => '0'
                    ]
                ],
                'url' => $url,
                'key' => null,
                'callback' => 'formatResponse',
                'expected' => '1) PID 0: Elaborazione dati Portata variabile 30030 dal 30/12/2019 al 31/12/2019 avvenuta con successo in | sec. Nessun file CSV senza zeri esportato per mancanza di dati.' . PHP_EOL
            ],
            'single post' => [
                'post' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '30/12/2019',
                        'dateto' => '31/12/2019',
                        'field' => 'portata',
                        'full' => '0'
                    ]
                ],
                'url' => $url,
                'key' => 'id',
                'callback' => 'formatResponse',
                'expected' => $single
            ],
            'multi post' => [
                'post' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '30/12/2019',
                        'dateto' => '31/12/2019',
                        'field' => 'portata',
                        'full' => '0'
                    ],
                    [
                        'id' => '1',
                        'var' => '30040',
                        'datefrom' => '30/12/2019',
                        'dateto' => '31/12/2019',
                        'field' => 'portata',
                        'full' => '0'
                    ]
                ],
                'url' => $url,
                'key' => 'id',
                'callback' => 'formatResponse',
                'expected' => $multi
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::runMultiSync2
     * @dataProvider runMultiSync2EchoProvider
     */
    public function testRunMultiSync2EchoOutputString(array $postParam, string $url, ?string $key, ?string $funcName, string $response) : void
    {
        $expecteds = explode('|', $response);
        
        curl::runMultiSync2($url, $postParam, $key, $funcName);
        
        $actual = $this->getActualOutput();
        
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::runMultiSync2
     */
    public function testRunMultiSync2DataException() : void
    {
        $postParam = [];
        $url = TOCSVURL;
        $key = 'id';
        $funcName = 'formatResponse';
        
        $this->expectException(\Exception::class);
        
        curl::runMultiSync2($url, $postParam, $key, $funcName);
    }
    
    /**
     * @coversNothing
     */
    public function runMultiAsync2Provider() : array
    {
        $url = TOCSVURL;
        $single = 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>| sec</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.' . PHP_EOL;
        $multi = $single . 'Elaborazione dati <b>Portata</b> variabile <b>30040</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>| sec</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.' . PHP_EOL;
        
        $data = [
            'no callback' => [
                'post' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '30/12/2019',
                        'dateto' => '31/12/2019',
                        'field' => 'portata',
                        'full' => '0'
                    ]
                ],
                'url' => $url,
                'key' => 'id',
                'callback' => null,
                'expected' => 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>|</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.'
            ],
            'no key' => [
                'post' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '30/12/2019',
                        'dateto' => '31/12/2019',
                        'field' => 'portata',
                        'full' => '0'
                    ]
                ],
                'url' => $url,
                'key' => null,
                'callback' => 'formatResponse',
                'expected' => 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>| sec</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.' . PHP_EOL
            ],
            'single post' => [
                'post' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '30/12/2019',
                        'dateto' => '31/12/2019',
                        'field' => 'portata',
                        'full' => '0'
                    ]
                ],
                'url' => $url,
                'key' => 'id',
                'callback' => 'formatResponse',
                'expected' => $single
            ],
            'multi post' => [
                'post' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '30/12/2019',
                        'dateto' => '31/12/2019',
                        'field' => 'portata',
                        'full' => '0'
                    ],
                    [
                        'id' => '1',
                        'var' => '30040',
                        'datefrom' => '30/12/2019',
                        'dateto' => '31/12/2019',
                        'field' => 'portata',
                        'full' => '0'
                    ]
                ],
                'url' => $url,
                'key' => 'id',
                'callback' => 'formatResponse',
                'expected' => $multi
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::runMultiAsync2
     * @dataProvider runMultiAsync2Provider
     */
    public function testRunMultiAsync2Equals(array $postParam, string $url, ?string $key, ?string $funcName, string $response) : void
    {
        $expecteds = explode('|', $response);
        
        $actual = curl::runMultiAsync2($url, $postParam, $key, $funcName);
        
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @coversNothing
     */
    public function runMultiAsync2EchoProvider() : array
    {
        $url = TOCSVURL;
        $single = '1) PID 0: Elaborazione dati Portata variabile 30030 dal 30/12/2019 al 31/12/2019 avvenuta con successo in | sec. Nessun file CSV senza zeri esportato per mancanza di dati.' . PHP_EOL;
        $multi = $single . '2) PID 1: Elaborazione dati Portata variabile 30040 dal 30/12/2019 al 31/12/2019 avvenuta con successo in | sec. Nessun file CSV senza zeri esportato per mancanza di dati.' . PHP_EOL;
        
        $data = [
            'no callback' => [
                'post' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '30/12/2019',
                        'dateto' => '31/12/2019',
                        'field' => 'portata',
                        'full' => '0'
                    ]
                ],
                'url' => $url,
                'key' => 'id',
                'callback' => null,
                'expected' => 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>|</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.'
            ],
            'no key' => [
                'post' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '30/12/2019',
                        'dateto' => '31/12/2019',
                        'field' => 'portata',
                        'full' => '0'
                    ]
                ],
                'url' => $url,
                'key' => null,
                'callback' => 'formatResponse',
                'expected' => '1) PID 0: Elaborazione dati Portata variabile 30030 dal 30/12/2019 al 31/12/2019 avvenuta con successo in | sec. Nessun file CSV senza zeri esportato per mancanza di dati.' . PHP_EOL
            ],
            'single post' => [
                'post' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '30/12/2019',
                        'dateto' => '31/12/2019',
                        'field' => 'portata',
                        'full' => '0'
                    ]
                ],
                'url' => $url,
                'key' => 'id',
                'callback' => 'formatResponse',
                'expected' => $single
            ],
            'multi post' => [
                'post' => [
                    [
                        'id' => '0',
                        'var' => '30030',
                        'datefrom' => '30/12/2019',
                        'dateto' => '31/12/2019',
                        'field' => 'portata',
                        'full' => '0'
                    ],
                    [
                        'id' => '1',
                        'var' => '30040',
                        'datefrom' => '30/12/2019',
                        'dateto' => '31/12/2019',
                        'field' => 'portata',
                        'full' => '0'
                    ]
                ],
                'url' => $url,
                'key' => 'id',
                'callback' => 'formatResponse',
                'expected' => $multi
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::runMultiAsync2
     * @dataProvider runMultiAsync2EchoProvider
     */
    public function testRunMultiAsync2EchoOutputString(array $postParam, string $url, ?string $key, ?string $funcName, string $response) : void
    {
        $expecteds = explode('|', $response);
        
        curl::runMultiAsync2($url, $postParam, $key, $funcName);
        
        $actual = $this->getActualOutput();
        
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::runMultiAsync2
     */
    public function testRunMultiAsync2DataException() : void
    {
        $postParam = [];
        $url = TOCSVURL;
        $key = 'id';
        $funcName = 'formatResponse';
        
        $this->expectException(\Exception::class);
        
        curl::runMultiAsync2($url, $postParam, $funcName);
    }
}
