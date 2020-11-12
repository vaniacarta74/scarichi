<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi\tests\Unit;

use PHPUnit\Framework\TestCase;
use vaniacarta74\Scarichi\Utility;

/**
 * Description of UtilityTest
 *
 * @author Vania
 */
class UtilityTest extends TestCase
{
    
    /**
     * @coversNothing
     */
    public function getJsonArrayIsStringProvider() : array
    {
        $path1 = __DIR__ . '/../../composer.json';
        $path2 = __DIR__ . '/../../src/config/config.json';
        
        $data = [
            'level0.0' => [
                'path' => $path1,
                'keys' => null,
                'deepKey' => 'version'
            ],
            'level1.0' => [
                'path' => $path1,
                'keys' => ['support'],
                'deepKey' => 'source'
            ],
            'level1.1' => [
                'path' => $path1,
                'keys' => ['keywords'],
                'deepKey' => '1'
            ],
            'level2.0' => [
                'path' => $path1,
                'keys' => ['autoload','files'],
                'deepKey' => '0'
            ],
            'level2.1' => [
                'path' => $path1,
                'keys' => ['authors','0'],
                'deepKey' => 'name'
            ],
            'level3.0' => [
                'path' => $path2,
                'keys' => ['parameters','field','descriptions'],
                'deepKey' => '0'
            ],
            'level4.0' => [
                'path' => $path2,
                'keys' => ['parameters','field','options','costants'],
                'deepKey' => '0'
            ]
        ];
        
        return $data;
    }

    /**
     * @coversNothing
     */
    public function getJsonArrayIsArrayProvider() : array
    {
        $path1 = __DIR__ . '/../../composer.json';
        $path2 = __DIR__ . '/../../src/config/config.json';
        
        $data = [
            'only path' => [
                'path' => $path1,
                'keys' => null,
                'deepKey' => null
            ],
            'level0' => [
                'path' => $path1,
                'keys' => ['support'],
                'deepKey' => null
            ],
            'level1' => [
                'path' => $path1,
                'keys' => ['autoload','files'],
                'deepKey' => null
            ],
            'level2' => [
                'path' => $path2,
                'keys' => ['parameters','field','descriptions'],
                'deepKey' => null
            ],
            'level3' => [
                'path' => $path2,
                'keys' => ['parameters','field','options','costants'],
                'deepKey' => null
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::getJsonArray
     * @dataProvider getJsonArrayIsStringProvider
     */
    public function testGetJsonArrayIsString(string $path, ?array $keys, ?string $deepKey) : void
    {
        $actual = Utility::getJsonArray($path, $keys, $deepKey);
        
        $this->assertIsString($actual[0]);
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::getJsonArray
     * @dataProvider getJsonArrayIsArrayProvider
     */
    public function testGetJsonArrayIsArray(string $path, ?array $keys, ?string $deepKey) : void
    {
        $actual = Utility::getJsonArray($path, $keys, $deepKey);
        
        $this->assertIsArray($actual);
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::getJsonArray
     */
    public function testGetJsonArrayException() : void
    {
        $path = __DIR__ . '/../../composer.json';
        $deepKey = 'pippo';
        
        $this->expectException(\Exception::class);
        
        Utility::getJsonArray($path, null, $deepKey);
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::getJsonArray
     */
    public function testGetJsonArrayDeepKeyException() : void
    {
        $path = __DIR__ . '/../../composer.json';
        $keys = ['autoload'];
        $deepKey = 'files';
        
        $this->expectException(\Exception::class);
        
        Utility::getJsonArray($path, $keys, $deepKey);
    }

    /**
     * @coversNothing
     */
    public function getSubArrayProvider() : array
    {
        $array = [
            'parameters' => [
                'field' => [
                    'options' => [
                        'costants' => ['V','L','M']
                    ]
                ],
                'help' => [
                    'name' => 'help'
                ]
            ]
        ];
        
        $data = [
            'level1' => [
                'master' => $array,
                'keys' => ['parameters'],
                'expected' => [
                    'field' => [
                        'options' => [
                            'costants' => ['V','L','M']
                        ]
                    ],
                    'help' => [
                        'name' => 'help'
                    ]
                ]
            ],
            'level2' => [
                'master' => $array,
                'keys' => ['parameters','field'],
                'expected' => [
                    'options' => [
                        'costants' => ['V','L','M']
                    ]
                ]
            ],
            'level3' => [
                'master' => $array,
                'keys' => ['parameters','field','options'],
                'expected' => [
                    'costants' => ['V','L','M']
                ]
            ],
            'level4' => [
                'master' => $array,
                'keys' => ['parameters','field','options','costants'],
                'expected' => ['V','L','M']
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::getSubArray
     * @dataProvider getSubArrayProvider
     */
    public function testGetSubArrayEquals($master, $keys, $expected) : void
    {
        $actual = Utility::getSubArray($master, $keys);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::getSubArray
     */
    public function testGetSubArrayException() : void
    {
        $master = [
            'parameters' => [
                'field' => [
                    'options' => [
                        'costants' => ['V','L','M']
                    ]
                ],
                'help' => [
                    'name' => 'help'
                ]
            ]
        ];
        $keys = ['autoload'];
        
        $this->expectException(\Exception::class);
        
        Utility::getSubArray($master, $keys);
    }
    
    /**
     * @coversNothing
     */
    public function benchmarkProvider() : array
    {
        $dateTimeSec = new \DateTime();
        $dateTimeMin = new \DateTime();
        $dateTimeOra = new \DateTime();
        $dateTimeSec->sub(new \DateInterval('PT30S'));
        $dateTimeMin->sub(new \DateInterval('PT30M'));
        $dateTimeOra->sub(new \DateInterval('PT2H'));
                
        $data = [
            'ore' => [
                'data' => $dateTimeOra->format('Y-m-d H:i:s.u'),
                'expected' => '2 ora, 0 min e 22 sec'
            ],
            'minuti' => [
                'data' => $dateTimeMin->format('Y-m-d H:i:s.u'),
                'expected' => '30 min e 22 sec'
            ],
            'secondi' => [
                'data' => $dateTimeSec->format('Y-m-d H:i:s.u'),
                'expected' => '52,527 sec'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::benchmark
     * @dataProvider benchmarkProvider
     */
    public function testBenchmarkEquals($date, $expected) : void
    {
        $actual = Utility::benchmark($date);
        
        $this->assertRegExp('/^(([1-5]?[0-9])[,][0-9]{3}\s(sec))|(([1-9]|[1-5][0-9])\s(min)\s[e]\s([1-5]?[0-9])\s(sec))|([1-9]\s(ora)[,]\s([1-5]?[0-9])\s(min)\s[e]\s([1-5]?[0-9])\s(sec))$/', $actual);
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::benchmark
     */
    public function testBenchmarkException() : void
    {
        $date = '31/02/2020';
        
        $this->expectException(\Exception::class);
        
        Utility::benchmark($date);
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::cUrlSet     
     */
    public function testCurlSetIsResource()
    {
        $params = [
            'var' => '30030',
            'datefrom' => '30/12/2019',
            'dateto' => '31/12/2019',
            'field' => 'portata',
            'full' => '0'
        ];
        $url = URL;
        
        $actual = Utility::cUrlSet($params, $url);
        
        $this->assertIsResource($actual);
        
        return $actual;
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::cUrlSet
     */
    public function testCurlSetException() : void
    {
        $params = [];
        $url = URL;
        
        $this->expectException(\Exception::class);
        
        Utility::cUrlSet($params, $url);
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::cUrlExec
     * @depends testCurlSetIsResource
     */
    public function testCurlExecContainsString($ch) : void
    {
        $response = 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>|</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.';
        $expecteds = explode('|', $response);
        
        $actual = Utility::cUrlExec($ch);
        
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::cUrlExec
     */
    public function testCurlExecException() : void
    {
        $ch = null;
        
        $this->expectException(\Exception::class);
        
        Utility::cUrlExec($ch);
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::cUrl
     */
    public function testCurlContainsString() : void
    {
        $params = [
            'var' => '30030',
            'datefrom' => '30/12/2019',
            'dateto' => '31/12/2019',
            'field' => 'portata',
            'full' => '0'
        ];
        $url = URL;
        $response = 'Elaborazione dati <b>Portata</b> variabile <b>30030</b> dal <b>30/12/2019</b> al <b>31/12/2019</b> avvenuta con successo in <b>|</b>. Nessun file CSV <b>senza zeri</b> esportato per mancanza di dati.';
        $expecteds = explode('|', $response);
        
        $actual = Utility::cUrl($params, $url);
        
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::cUrl
     */
    public function testCurlException() : void
    {
        $params = [];
        $url = URL;
        
        $this->expectException(\Exception::class);
        
        Utility::cUrl($params, $url);
    }
}
