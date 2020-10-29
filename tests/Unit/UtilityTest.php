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
}
