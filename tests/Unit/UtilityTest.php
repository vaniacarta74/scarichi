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
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::getJsonArray
     */
    public function testGetJsonPathException() : void
    {
        $path = 'pippo';
        
        $this->expectException(\Exception::class);
        
        Utility::getJsonArray($path);
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
    
    /**
     * @coversNothing
     */
    public function checkPathProvider() : array
    {
        $data = [
            'readable' => [
                'path' => '/var/www/html/telecontrollo/scarichi/github/src/telegram.php',
                'mode' => 'r',
                'expected' => true
            ],
            'csv' => [
                'path' => '/var/www/html/telecontrollo/scarichi/github/tests/providers/test.csv',
                'mode' => 'r',
                'expected' => true
            ],
            'writable' => [
                'path' => '/var/www/html/telecontrollo/scarichi/github/src/telegram.php',
                'mode' => 'w',
                'expected' => true
            ],
            'readable + writable' => [
                'path' => '/var/www/html/telecontrollo/scarichi/github/src/telegram.php',
                'mode' => 'rw',
                'expected' => true
            ],
            'only path' => [
                'path' => '/var/www/html/telecontrollo/scarichi/github/src/telegram.php',
                'mode' => null,
                'expected' => true
            ],
            'not exist' => [
                'path' => '/var/www/html/telecontrollo/scarichi/github/src/telegramma.php',
                'mode' => null,
                'expected' => false
            ],
            'not path' => [
                'path' => 'pippo',
                'mode' => null,
                'expected' => false
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::checkPath
     * @dataProvider checkPathProvider
     */
    public function testCheckPathEquals(string $path, ?string $mode, bool $expected) : void
    {
        $actual = Utility::checkPath($path, $mode);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::checkPath
     */
    public function testCheckPathException() : void    {
        
        $path = '/var/www/html/telecontrollo/scarichi/github/src/telegram.php';
        $mode = 'wr';
        
        $this->expectException(\Exception::class);
        
        Utility::checkPath($path, $mode);
    }
    
    /**
     * @coversNothing
     */
    public function catchParamProvider() : array
    {
        $data = [
            'path standard' => [
                'array' => [
                    'telegram.php',
                    '/var/www/html/telecontrollo/scarichi/telegram.json'
                ],
                'key' => 1,
                'arrAssoc' => [
                    'path' => '/var/www/html/telecontrollo/scarichi/github/src/telegram.php'
                ],
                'keyAssoc' => 'path',
                'default' => BOTPATH,
                'expected' => '/var/www/html/telecontrollo/scarichi/telegram.json'
            ],
            'path argv' => [
                'array' => [
                    'telegram.php',
                    '/var/www/html/telecontrollo/scarichi/telegram.json'
                ],
                'key' => 1,
                'arrAssoc' => null,
                'keyAssoc' => null,
                'default' => BOTPATH,
                'expected' => '/var/www/html/telecontrollo/scarichi/telegram.json'
            ],
            'path request check' => [
                'array' => null,
                'key' => null,
                'arrAssoc' => [
                    'path' => '/var/www/html/telecontrollo/scarichi/github/src/telegram.php'
                ],
                'keyAssoc' => 'path',
                'default' => BOTPATH,
                'expected' => '/var/www/html/telecontrollo/scarichi/github/src/telegram.php'
            ],
            'generic default' => [
                'array' => null,
                'key' => null,
                'arrAssoc' => null,
                'keyAssoc' => null,
                'default' => 'pippo',
                'expected' => 'pippo'
            ],
        ];
        
        return $data;
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::catchParam
     * @dataProvider catchParamProvider
     */
    public function testCatchParamEquals(?array $array, ?int $key, ?array $arrAssoc, ?string $keyAssoc, string $default, string $expected) : void
    {
        $actual = Utility::catchParam($array, $key, $arrAssoc, $keyAssoc, $default);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::catchParam
     */
    public function testCatchParamRequestException() : void    {
        
        $array = null;
        $key = null;
        $arrAssoc = [
            'path' => '/var/www/html/telecontrollo/scarichi/telegram.json'
        ];
        $keyAssoc = 'url';
        $default = BOTPATH;
        
        $this->expectException(\Exception::class);
        
        Utility::catchParam($array, $key, $arrAssoc, $keyAssoc, $default);
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::catchParam
     */
    public function testCatchParamException() : void    {
        
        $array = [
            'telegram.php',
            '/var/www/html/telecontrollo/scarichi/telegram.json'
        ];
        $key = 2;
        $arrAssoc = null;
        $keyAssoc = null;
        $default = BOTPATH;
        
        $this->expectException(\Exception::class);
        
        Utility::catchParam($array, $key, $arrAssoc, $keyAssoc, $default);
    }
    
    /**
     * @coversNothing
     */
    public function checkParamProvider() : array
    {
        $data = [
            'path default' => [
                'default' => BOTPATH,
                'method' => 'Utility::checkPath',
                'params' => ['r'],
                'expected' => '/var/www/html/telecontrollo/scarichi/github/src/config/../../../telegram.json'
            ],            
            'path param' => [
                'default' => '/var/www/html/telecontrollo/scarichi/github/src/telegram.php',
                'method' => 'Utility::checkPath',
                'params' => ['r'],
                'expected' => '/var/www/html/telecontrollo/scarichi/github/src/telegram.php'
            ],
            'url default' => [
                'default' => BOTURL,
                'method' => 'Utility::checkUrl',
                'params' => null,
                'expected' => 'http://localhost/scarichi/telegram.php'
            ],
            'url param' => [
                'default' => 'http://localhost/scarichi/telegram.php?var=30030&datefrom=27/10/2020',
                'method' => 'Utility::checkUrl',
                'params' => null,
                'expected' => 'http://localhost/scarichi/telegram.php?var=30030&datefrom=27/10/2020'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::checkParam
     * @dataProvider checkParamProvider
     */
    public function testCheckParamEquals(string $default, string $checkMethod, ?array $methodParams, string $expected) : void
    {
        $actual = Utility::checkParam($default, $checkMethod, $methodParams);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::checkParam
     */
    public function testCheckParamPathException() : void    {
        
        $default = 'http://localhost/scarichi/telegram.php?var=30030&datefrom=27/10/2020';
        $checkMethod = 'Utility::checkPath';
        $methodParams = ['r'];
                
        $this->expectException(\Exception::class);
        
        Utility::checkParam($default, $checkMethod, $methodParams);
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::checkParam
     */
    public function testCheckParamUrlException() : void    {
        
        $default = '/var/www/html/telecontrollo/scarichi/github/src/telegram.php';
        $checkMethod = 'Utility::checkUrl';
        $methodParams = null;
        
        $this->expectException(\Exception::class);
        
        Utility::checkParam($default, $checkMethod, $methodParams);
    }
    
    /**
     * @coversNothing
     */
    public function checkUrlProvider() : array
    {
        $data = [
            'directory' => [
                'url' => 'http://localhost/sitpit/',
                'expected' => true
            ],
            'local' => [
                'url' => 'http://192.168.1.100/sitpit/',
                'expected' => true
            ],
            'ssl' => [
                'url' => 'https://192.168.1.100/sitpit/',
                'expected' => true
            ],
            'more dir' => [
                'url' => 'http://localhost/sitpit/telecontrollo/scarichi/',
                'expected' => true
            ],
            'php' => [
                'url' => 'http://localhost/sitpit/telecontrollo/scarichi/telegram.php',
                'expected' => true
            ],
            'html' => [
                'url' => 'http://localhost/scarichi/telegram.html',
                'expected' => true
            ],
            'json' => [
                'url' => 'http://localhost/scarichi/telegram.json',
                'expected' => true
            ],
            'php 1 param' => [
                'url' => 'http://localhost/sitpit/telecontrollo/scarichi/telegram.php?var=30030',
                'expected' => true
            ],
            'php 2 param' => [
                'url' => 'http://localhost/scarichi/telegram.php?var=30030&datefrom=01/01/2020',
                'expected' => true
            ],
            'php more param' => [
                'url' => 'http://localhost/scarichi/telegram.php?var=30030&datefrom=01/01/2020&dateto=21/01/2020',
                'expected' => true
            ],
            'url with url' => [
                'url' => 'http://localhost/scarichi/watchdog.php?url=http://localhost/scarichi/telegram.php',
                'expected' => true
            ],
            'no dir no ext' => [
                'url' => 'http://localhost/scarichi',
                'expected' => false
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::checkUrl
     * @dataProvider checkUrlProvider
     */
    public function testCheckUrlEquals(string $url, bool $expected) : void
    {
        $actual = Utility::checkUrl($url);
        
        $this->assertEquals($expected, $actual);
    }
}
