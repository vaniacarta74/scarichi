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
        $url = 'http://localhost/scarichi/tocsv.php';
        
        $actual = Curl::set($url, $params);
        
        $this->assertIsResource($actual);
        
        return $actual;
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::set     
     */
    public function testSetGetIsResource()
    {
        $url = 'http://localhost/scarichi/tocsv.php';
        
        $actual = Curl::set($url);
        
        $this->assertIsResource($actual);
        
        return $actual;
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::set
     */
    public function testSetException() : void
    {
        $params = [];
        $url = 'http://localhost/scarichi/tocsv.php';
        
        $this->expectException(\Exception::class);
        
        Curl::set($url, $params);
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
        $url = 'http://localhost/scarichi/tojson.php';
        
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
        $url = 'http://localhost/scarichi/tojson.php';
        
        $this->expectException(\Exception::class);
        
        Curl::setJson($url, $params);
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
    public function testExecException() : void
    {
        $ch = null;
        
        $this->expectException(\Exception::class);
        
        Curl::exec($ch);
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::run
     */
    public function testRunContainsString() : void
    {
        $params = [
            'var' => '30030',
            'datefrom' => '30/12/2019',
            'dateto' => '31/12/2019',
            'field' => 'portata',
            'full' => '0'
        ];
        $url = 'http://localhost/scarichi/tocsv.php';
        $json = false;
        $response = 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>|</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.';
        $expecteds = explode('|', $response);
        
        $actual = Curl::run($url, $params, $json);
        
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
        $url = 'http://localhost/scarichi/tocsv.php?var=30030&datefrom=30/12/2019&dateto=31/12/2019&field=portata&full=0';
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
        $url = 'http://localhost/scarichi/tojson.php';
        $json = true;
        $expected = __DIR__ . '/../providers/curlRunTest.json';
        
        $actual = Curl::run($url, $params, $json);
        
        $this->assertJsonStringEqualsJsonFile($expected, $actual);
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::run
     */
    public function testRunException() : void
    {
        $params = [];
        $url = 'http://localhost/scarichi/tocsv.php';
        
        $this->expectException(\Exception::class);
        
        Curl::run($url, $params);
    }
    
    /**
     * @coversNothing
     */
    public function multiSetProvider() : array
    {
        $url = URL;
        
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
     * @covers \vaniacarta74\Scarichi\Curl::multiSet
     * @dataProvider multiSetProvider
     */
    public function testMultiSetIsResource(array $postParam, string $url, ?string $key) : void
    {
        $actuals = Curl::multiSet($url, $postParam, $key);
        
        foreach ($actuals as $actual) {
            $this->assertIsResource($actual);
        }
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::multiSet
     */
    public function testMultiSetKeyException() : void
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
        $url = URL;
        $key = 'pippo';
        
        $this->expectException(\Exception::class);
        
        Curl::multiSet($url, $postParam, $key);
    }
    
    /**
     * @group curl
     * @covers \vaniacarta74\Scarichi\Curl::multiSet
     */
    public function testMultiSetDataException() : void
    {
        $postParam = [];
        $url = URL;
        $key = 'var';
        
        $this->expectException(\Exception::class);
        
        Curl::multiSet($url, $postParam, $key);
    }

    /**
     * @coversNothing
     */
    public function runMultiSyncProvider() : array
    {
        $url = URL;
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
     * @covers \vaniacarta74\Scarichi\Curl::runMultiSync
     * @dataProvider runMultiSyncProvider
     */
    public function testRunMultiSyncEquals(array $postParam, string $url, ?string $key, ?string $funcName, string $response) : void
    {
        $expecteds = explode('|', $response);
        
        $actual = curl::runMultiSync($url, $postParam, $key, $funcName);
        
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
        $postParam = [];
        $url = URL;
        $key = 'id';
        $funcName = 'formatResponse';
        
        $this->expectException(\Exception::class);
        
        curl::runMultiSync($url, $postParam, $key, $funcName);
    }
    
    /**
     * @coversNothing
     */
    public function runMultiAsyncProvider() : array
    {
        $url = URL;
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
     * @covers \vaniacarta74\Scarichi\Curl::runMultiAsync
     * @dataProvider runMultiAsyncProvider
     */
    public function testRunMultiAsyncOutputString(array $postParam, string $url, ?string $key, ?string $funcName, string $response) : void
    {
        $expecteds = explode('|', $response);
        
        curl::runMultiAsync($url, $postParam, $key, $funcName);
        
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
        $postParam = [];
        $url = URL;
        $key = 'id';
        $funcName = 'formatResponse';
        
        $this->expectException(\Exception::class);
        
        curl::runMultiAsync($url, $postParam, $funcName);
    }
}
