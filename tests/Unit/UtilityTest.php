<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi\tests\Unit;

use PHPUnit\Framework\TestCase;
use vaniacarta74\Scarichi\Utility;
use function vaniacarta74\Scarichi\formulaPortataSfioro as formulaPortataSfioro;

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
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::benchmark
     */
    public function testBenchmarkOraEquals() : void
    {
        $dateTimeOra = new \DateTime('NOW', new \DateTimeZone('Europe/Rome'));
        $dateTimeOra->sub(new \DateInterval('PT2H'));
        $date = $dateTimeOra->format('Y-m-d H:i:s.u');
        
        $actual = Utility::benchmark($date);
        
        $this->assertRegExp('/^([1-9]\s(ora)[,]\s([1-5]?[0-9])\s(min)\s[e]\s([1-5]?[0-9])\s(sec))$/', $actual);
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::benchmark
     */
    public function testBenchmarkMinEquals() : void
    {
        $dateTimeMin = new \DateTime('NOW', new \DateTimeZone('Europe/Rome'));
        $dateTimeMin->sub(new \DateInterval('PT30M'));
        $date = $dateTimeMin->format('Y-m-d H:i:s.u');
        
        $actual = Utility::benchmark($date);
        
        $this->assertRegExp('/^(([1-9]|[1-5][0-9])\s(min)\s[e]\s([1-5]?[0-9])\s(sec))$/', $actual);
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::benchmark
     */
    public function testBenchmarkSecEquals() : void
    {
        $dateTimeSec = new \DateTime('NOW', new \DateTimeZone('Europe/Rome'));
        $dateTimeSec->sub(new \DateInterval('PT10S'));
        $date = $dateTimeSec->format('Y-m-d H:i:s.u');
        
        $actual = Utility::benchmark($date);
        
        $this->assertRegExp('/^(([1-5]?[0-9])[,][0-9]{3}\s(sec))$/', $actual);
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
     * @covers \vaniacarta74\Scarichi\Utility::callback
     */
    public function testCallbackEqualsWithDelta() : void  
    {        
        $funzione = 'formulaPortataSfioro';
        
        $formule = [
            'tipo_formula' => 'portata sfiorante',
            'alias' => 'sfioro',
            'mi' => 0.47,
            'larghezza' => 0.387,
            'limite' => 800
        ];
        $parametri = [
            'altezza' => 10
        ];
        $campi = [
            'altezza'
        ];
        
        $expected = 25.478;
        
        $actual = Utility::callback($funzione, array($formule, $parametri, $campi));
        
        $this->assertEqualsWithDelta($expected, $actual, 0.001);
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::callback
     */
    public function testCallbackException() : void    {
        
        $funzione = 'pippo';
        
        $this->expectException(\Exception::class);
        
        Utility::callback($funzione, array(1,2,3));
    }
    
    /**
     * @coversNothing
     */
    public function checkDateProvider() : array
    {
        $data = [
            'standard' => [
                'date' => '01/01/2020',
                'small' => true,
                'expected' => true
            ],
            'over' => [
                'date' => '01/01/2080',
                'small' => true,
                'expected' => false
            ],
            'error' => [
                'date' => '32/01/2020',
                'small' => true,
                'expected' => false
            ],
            'not over' => [
                'date' => '01/01/2080',
                'small' => false,
                'expected' => true
            ],
            'small null' => [
                'date' => '01/01/2020',
                'small' => null,
                'expected' => true
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::checkDate
     * @dataProvider checkDateProvider
     */
    public function testCheckDateEquals(string $date, ?bool $isSmall, bool $expected) : void
    {
        $actual = Utility::checkDate($date, $isSmall);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::checkDate
     */
    public function testCheckDateException() : void    {
        
        $date = '2020-01-01';
        $isSmall = true;
        
        $this->expectException(\Exception::class);
        
        Utility::checkDate($date, $isSmall);
    }
}
