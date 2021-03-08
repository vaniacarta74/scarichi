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
            'error default' => [
                'array' => null,
                'key' => null,
                'arrAssoc' => [
                    'path' => '/var/www/html/telecontrollo/scarichi/github/src/telegram.php'
                ],
                'keyAssoc' => 'url',
                'default' => 'pippo',
                'expected' => 'pippo'
            ]
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
    public function testCatchParamException() : void    {
        
        $array = [
            'telegram.php',
            '/var/www/html/telecontrollo/scarichi/telegram.json'
        ];
        $key = 2;
        $arrAssoc = null;
        $keyAssoc = null;
        $default = null;
        
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
                'expected' => 'http://' . LOCALHOST . '/scarichi/telegram.php'
            ],
            'url param' => [
                'default' => 'http://' . LOCALHOST . '/scarichi/telegram.php?var=30030&datefrom=27/10/2020',
                'method' => 'Utility::checkUrl',
                'params' => null,
                'expected' => 'http://' . LOCALHOST . '/scarichi/telegram.php?var=30030&datefrom=27/10/2020'
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
        
        $default = 'http://' . LOCALHOST . '/scarichi/telegram.php?var=30030&datefrom=27/10/2020';
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
                'url' => 'http://' . LOCALHOST . '/sitpit/',
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
                'url' => 'http://' . LOCALHOST . '/sitpit/telecontrollo/scarichi/',
                'expected' => true
            ],
            'php' => [
                'url' => 'http://' . LOCALHOST . '/sitpit/telecontrollo/scarichi/telegram.php',
                'expected' => true
            ],
            'html' => [
                'url' => 'http://' . LOCALHOST . '/scarichi/telegram.html',
                'expected' => true
            ],
            'json' => [
                'url' => 'http://' . LOCALHOST . '/scarichi/telegram.json',
                'expected' => true
            ],
            'php 1 param' => [
                'url' => 'http://' . LOCALHOST . '/sitpit/telecontrollo/scarichi/telegram.php?var=30030',
                'expected' => true
            ],
            'php 2 param' => [
                'url' => 'http://' . LOCALHOST . '/scarichi/telegram.php?var=30030&datefrom=01/01/2020',
                'expected' => true
            ],
            'php more param' => [
                'url' => 'http://' . LOCALHOST . '/scarichi/telegram.php?var=30030&datefrom=01/01/2020&dateto=21/01/2020',
                'expected' => true
            ],
            'url with url' => [
                'url' => 'http://' . LOCALHOST . '/scarichi/watchdog.php?url=http://' . LOCALHOST . '/scarichi/telegram.php',
                'expected' => true
            ],
            'no dir no ext' => [
                'url' => 'http://' . LOCALHOST . '/scarichi',
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
    
    /**
     * @coversNothing
     */
    public function countTagsProvider() : array
    {
        $data = [
            'standard' => [
                'html' => '<b>Test</b> di <b>funzione</b> sendTelegram(<b>Standard</b>)',
                'tag' => null,
                'expected' => 3
            ],
            'standard explicit' => [
                'html' => '<html><body><p><b>Test</b> di <b>funzione</b> sendTelegram(<b>Standard</b>)</p></body></html>',
                'tag' => null,
                'expected' => 3
            ],
            'standard mixed' => [
                'html' => '<b>Test</b> di <i>funzione</i> sendTelegram(<b>Standard</b>)',
                'tag' => null,
                'expected' => 3
            ],
            'defined tag' => [
                'html' => '<b>Test</b> di <i>funzione</i> sendTelegram(<b>Standard</b>)',
                'tag' => 'b',
                'expected' => 2
            ],
            'all tags' => [
                'html' => '<b>Test</b> di <b>funzione</b> sendTelegram(<b>Standard</b>)',
                'tag' => '*',
                'expected' => 5
            ],
            'all tags explicit' => [
                'html' => '<html><body><b>Test</b> di <b>funzione</b> sendTelegram(<b>Standard</b>)</body></html>',
                'tag' => '*',
                'expected' => 5
            ],            
            'all tags crnl' => [
                'html' => '<b>Test</b> di <b>funzione</b>' . PHP_EOL  . 'sendTelegram(<b>Standard</b>)',
                'tag' => '*',
                'expected' => 5
            ],
            'void html' => [
                'html' => '',
                'tag' => null,
                'expected' => 0
            ],
            'no tag close' => [
                'html' => '<b>Test</b> di <b>fun',
                'tag' => null,
                'expected' => 2
            ],
            'no tag open' => [
                'html' => 'ione</b> sendTelegram(<b>Standard</b>)',
                'tag' => null,
                'expected' => 0
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::countTags
     * @dataProvider countTagsProvider
     */
    public function testCountTagsEquals(string $html, ?string $tag = null, int $expected) : void
    {
        $actual = Utility::countTags($html, $tag);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function checkTagsProvider() : array
    {
        $data = [
            'standard' => [
                'html' => '<b>Test</b> di <b>funzione</b> sendTelegram(<b>Standard</b>)',
                'expected' => true
            ],
            'standard explicit' => [
                'html' => '<html><body><p><b>Test</b> di <b>funzione</b> sendTelegram(<b>Standard</b>)</p></body></html>',
                'expected' => true
            ],
            'standard mixed' => [
                'html' => '<b>Test</b> di <i>funzione</i> sendTelegram(<b>Standard</b>)',
                'expected' => true
            ],            
            'all tags crnl' => [
                'html' => '<b>Test</b> di <b>funzione</b>' . PHP_EOL  . 'sendTelegram(<b>Standard</b>)',
                'expected' => true
            ],
            'void html' => [
                'html' => '',
                'expected' => true
            ],
            'no tag close' => [
                'html' => '<b>Test</b> di <b>fun',
                'expected' => false
            ],
            'no tag open' => [
                'html' => 'ione</b> sendTelegram(<b>Standard</b>)',
                'expected' => false
            ],
            'inverted' => [
                'html' => '</b>Test<b> di </i>funzione<i> sendTelegram(</b>Standard<b>)',
                'expected' => false
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::checkTags
     * @dataProvider checkTagsProvider
     */
    public function testCheckTagsEquals(string $html, bool $expected) : void
    {
        $actual = Utility::checkTags($html);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function areAllZeroProvider() : array
    {
        $data = [
            'standard' => [
                'tags' => [
                    'b' => 0,
                    'strong' => 0,
                    'i' => 0,
                    'html' => 0,
                    'body' => 0,
                    'p' => 0,
                    'u' => 0,
                    'ins' => 0,
                    's' => 0,
                    'a' => 0,
                    'code' => 0,
                    'pre' => 0
                ],
                'expected' => true
            ],
            'failed' => [
                'tags' => [
                    'b' => 1,
                    'strong' => 0,
                    'i' => 0,
                    'html' => 0,
                    'body' => 0,
                    'p' => 0,
                    'u' => 0,
                    'ins' => 0,
                    's' => 0,
                    'a' => 0,
                    'code' => 0,
                    'pre' => 0
                ],
                'expected' => false
            ],
            'inverted' => [
                'tags' => [
                    'b' => -999999,
                    'strong' => 0,
                    'i' => 0,
                    'html' => 0,
                    'body' => 0,
                    'p' => 0,
                    'u' => 0,
                    'ins' => 0,
                    's' => 0,
                    'a' => 0,
                    'code' => 0,
                    'pre' => 0
                ],
                'expected' => false
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::areAllZero
     * @dataProvider areAllZeroProvider
     */
    public function testAreAllZeroEquals(array $tags, bool $expected) : void
    {
        $actual = Utility::areAllZero($tags);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function getTagsProvider() : array
    {
        $data = [
            'standard' => [
                'html' => '<b>Test</b> di <b>funzione</b> sendTelegram(<b>Standard</b>)',
                'expected' => [
                    'b' => 0
                ]
            ],
            'standard explicit' => [
                'html' => '<html><body><p><b>Test</b> di <b>funzione</b> sendTelegram(<b>Standard</b>)</p></body></html>',
                'expected' => [
                    'html' => 0,
                    'body' => 0,
                    'p' => 0,
                    'b' => 0
                ]
            ],
            'standard mixed' => [
                'html' => '<b>Test</b> di <i>funzione</i> sendTelegram(<b>Standard</b>)',
                'expected' => [
                    'b' => 0,
                    'i' => 0
                ]
            ],            
            'all tags crnl' => [
                'html' => '<b>Test</b> di <b>funzione</b>' . PHP_EOL  . 'sendTelegram(<b>Standard</b>)',
                'expected' => [
                    'b' => 0
                ]
            ],
            'void html' => [
                'html' => '',
                'expected' => []
            ],
            'no tag close' => [
                'html' => '<b>Test</b> di <b>fun',
                'expected' => [
                    'b' => 1
                ]
            ],
            'no tag open' => [
                'html' => 'ione</b> sendTelegram(<b>Standard</b>)',
                'expected' => [
                    'b' => -999999
                ]
            ],
            'inverted' => [
                'html' => '</b>Test<b> di </i>funzione<i> sendTelegram(</b>Standard<b>)',
                'expected' => [
                    'b' => -999998,
                    'i' => -999998
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::getTags
     * @dataProvider getTagsProvider
     */
    public function testGetTagsEquals(string $html, array $expected) : void
    {
        $actual = Utility::getTags($html);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function purgeHtmlProvider() : array
    {
        $data = [
            'standard' => [
                'html' => '<b>Test</b> di <b>funzione</b> sendTelegram(<b>Standard</b>)',
                'admittedTags' => ADMITTEDTAGS,
                'expected' => '<b>Test</b> di <b>funzione</b> sendTelegram(<b>Standard</b>)'
            ],
            'standard explicit' => [
                'html' => '<html><body><p><b>Test</b> di <b>funzione</b> sendTelegram(<b>Standard</b>)</p></body></html>',
                'admittedTags' => ADMITTEDTAGS,
                'expected' => '<b>Test</b> di <b>funzione</b> sendTelegram(<b>Standard</b>)'
            ],
            'standard mixed' => [
                'html' => '<b>Test</b> di <i>funzione</i> sendTelegram(<b>Standard</b>)',
                'admittedTags' => ADMITTEDTAGS,
                'expected' => '<b>Test</b> di <i>funzione</i> sendTelegram(<b>Standard</b>)'
            ],            
            'all tags crnl' => [
                'html' => '<b>Test</b> di <b>funzione</b>' . PHP_EOL  . 'sendTelegram(<b>Standard</b>)',
                'admittedTags' => ADMITTEDTAGS,
                'expected' => '<b>Test</b> di <b>funzione</b>' . PHP_EOL  . 'sendTelegram(<b>Standard</b>)'
            ],
            'void html' => [
                'html' => '',
                'admittedTags' => ADMITTEDTAGS,
                'expected' => ''
            ],
            'no tag close' => [
                'html' => '<b>Test</b> di <b>fun',
                'admittedTags' => ADMITTEDTAGS,
                'expected' => 'Test di fun'
            ],
            'no tag open' => [
                'html' => 'ione</b> sendTelegram(<b>Standard</b>)',
                'admittedTags' => ADMITTEDTAGS,
                'expected' => 'ione sendTelegram(Standard)'
            ],
            'inverted' => [
                'html' => '</b>Test<b> di </i>funzione<i> sendTelegram(</b>Standard<b>)',
                'admittedTags' => ADMITTEDTAGS,
                'expected' => 'Test di funzione sendTelegram(Standard)'
            ],
            'wrong html' => [
                'html' => '<\b>Test metodo <b>Bot::secureSend con message_id inesistente',
                'admittedTags' => ADMITTEDTAGS,
                'expected' => 'Test metodo Bot::secureSend con message_id inesistente'
            ],
            'mixed' => [
                'html' => '<b>Test</b> di </i>funzione<i> sendTelegram(<b>Standard</b>)',
                'admittedTags' => ADMITTEDTAGS,
                'expected' => '<b>Test</b> di funzione sendTelegram(<b>Standard</b>)'
            ],
            'link stripped' => [
                'html' => '<b>Test</b> di <a href="http://www.pippo.com"funzione<i> sendTelegram(<b>Standard</b>)',
                'admittedTags' => ADMITTEDTAGS,
                'expected' => '<b>Test</b> di  sendTelegram(<b>Standard</b>)'
            ],
            'link purged' => [
                'html' => '<b>Test</b> di <a href="http://www.pippo.com">funzione<i> sendTelegram(<b>Standard</b>)',
                'admittedTags' => ADMITTEDTAGS,
                'expected' => '<b>Test</b> di funzione sendTelegram(<b>Standard</b>)'
            ],
            'link double' => [
                'html' => '<b>Test</b> di <a href="http://www.pippo.com">funzione<a> sendTelegram(<b>Standard</b>)',
                'admittedTags' => ADMITTEDTAGS,
                'expected' => '<b>Test</b> di funzione sendTelegram(<b>Standard</b>)'
            ],
            'link break 1' => [
                'html' => '<b>Test</b> di <a href="http://www.pippo.com">funzione',
                'admittedTags' => ADMITTEDTAGS,
                'expected' => '<b>Test</b> di funzione'
            ],
            'link break 2' => [
                'html' => 'href="http://www.pippo.com">funzione</a> sendTelegram(<b>Standard</b>)',
                'admittedTags' => ADMITTEDTAGS,
                'expected' => 'href="http://www.pippo.com">funzione sendTelegram(<b>Standard</b>)'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::purgeHtml
     * @dataProvider purgeHtmlProvider
     */
    public function testPurgeHtmlEquals(string $html, array $admittedTags, string $expected) : void
    {
        $actual = Utility::purgeHtml($html, $admittedTags);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function checkHtmlProvider() : array
    {
        $data = [
            'standard' => [
                'html' => '<b>Test</b> di <b>funzione</b> sendTelegram(<b>Standard</b>)',
                'expected' => true
            ],
            'error' => [
                'html' => '</b>Test</b> di <b>funzione</b> sendTelegram(<b>Standard</b>)',
                'expected' => false
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group utility
     * @covers \vaniacarta74\Scarichi\Utility::checkHtml
     * @dataProvider checkHtmlProvider
     */
    public function testCheckHtmlEquals(string $html, bool $expected) : void
    {
        $actual = Utility::checkHtml($html);
        
        $this->assertEquals($expected, $actual);
    }
}
