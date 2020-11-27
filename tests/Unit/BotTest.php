<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi\tests\Unit;

use PHPUnit\Framework\TestCase;
use vaniacarta74\Scarichi\Bot;
use vaniacarta74\Scarichi\Utility;
use vaniacarta74\Scarichi\tests\classes\Reflections;

/**
 * Description of CurlTest
 *
 * @author Vania
 */
class BotTest extends TestCase
{    
    private $bot;
    
    protected function setUp() : void
    {
        $json = Utility::getJsonArray(BOTPATH);
        $bot = $json['bots'][0];
        
        $this->bot = new Bot($bot);
    }
    
    protected function tearDown() : void
    {
        $this->bot = null;
    }
    
    /**
     * @group bot
     * @coversNothing
     */
    public function getUpdatesProvider() : array
    {
        $data = [
            'default param' => [
                'offset' => 0,
                'token' => TOKEN,
                'expected' => true
            ],
            'negative' => [
                'offset' => -20,
                'token' => TOKEN,
                'expected' => true
            ],
            'token error' => [
                'offset' => -20,
                'token' => 'TOKEN',
                'expected' => false
            ]            
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::getUpdates
     * @dataProvider getUpdatesProvider
     */
    public function testGetUpdatesEquals(int $offset, string $token, bool $expected) : void
    {
        $json = Bot::getUpdates($offset, $token);
        $arrJson = json_decode($json, true);
        $actual = $arrJson['ok'];
        
        $this->assertEquals($expected, $actual);          
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::getUpdates
     */
    public function testGetUpdatesReflectorEquals() : void
    {
        $offset = Reflections::getProperty($this->bot, 'start');
        $token = Reflections::getProperty($this->bot, 'token');
        $expected = true;
        
        $json = Bot::getUpdates($offset, $token);
        $arrJson = json_decode($json, true);
        $actual = $arrJson['ok'];
        
        $this->assertEquals($expected, $actual);          
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::getUpdates
     */
    public function testGetUpdatesException() : void    
    {
        $offset = 0;
        $token = TOKEN;
        
        Reflections::setStaticProperty(get_class($this->bot), 'url', 'pippo');
        
        $this->expectException(\Exception::class);
        
        Bot::getUpdates($offset, $token);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::secureUpdate
     */
    public function testSecureUpdateUsleepEquals() : void
    {
        $offset = 0;
        $token = TOKEN;
        $expected = [];
        
        $actual = Bot::secureUpdate($offset, $token);
        
        $this->assertEquals($expected, $actual);          
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::secureUpdate
     */
    public function testSecureUpdateMoreEquals() : void
    {
        $offset = 0;
        $token = 'token';
        $expected = [
            '0' => [
                "update_id" => 949891731,
                "message" => [
                    "message_id" => 1242,
                    "from" => [
                        "id" => 474912563,
                        "is_bot" => false,
                        "first_name" => "Vania",
                        "last_name" => "Carta",
                        "username" => "vaniacarta",
                        "language_code" => "it" 
                    ],
                    "chat" => [
                        "id" => 474912563,
                        "first_name" => "Vania",
                        "last_name" => "Carta",
                        "username" => "vaniacarta",
                        "type" => "private"
                    ],
                    "date" => 1606407576,
                    "text" => "/volume 30040 20/01/2020 27/01/2020",
                    "entities" => [
                        [
                            "offset" => 0,
                            "length" => 7,
                            "type" => "bot_command"
                        ]
                    ]
                ]
            ]
        ];
        
        Reflections::setStaticProperty(get_class($this->bot), 'url', 'http://localhost/tests/providers/');
        
        $actual = Bot::secureUpdate($offset, $token);
        
        $this->assertEquals($expected, $actual);          
    }
    
    /**
     * @group bot
     * @coversNothing
     */
    public function secureUpdateProvider() : array
    {
        $data = [
            'no param' => [
                'offset' => null,
                'token' => null,
                'expected' => []
            ],
            'default param' => [
                'offset' => 0,
                'token' => TOKEN,
                'expected' => []
            ],
            'negative' => [
                'offset' => -20,
                'token' => TOKEN,
                'expected' => []
            ]           
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::secureUpdate
     * @dataProvider secureUpdateProvider
     */
    public function testSecureUpdateEquals(?int $offset, ?string $token, array $expected) : void
    {
        Reflections::setStaticProperty(get_class($this->bot), 'url', 'https://api.telegram.org/bot');
        
        $actual = Bot::secureUpdate($offset, $token);
        
        $this->assertEquals($expected, $actual);          
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::secureUpdate
     */
    public function testSecureUpdateReflectorEquals() : void
    {
        $offset = Reflections::getProperty($this->bot, 'start');
        $token = Reflections::getProperty($this->bot, 'token');
        $expected = [];
        
        $actual = Bot::secureUpdate($offset, $token);
        
        $this->assertEquals($expected, $actual);          
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::secureUpdate
     */
    public function testSecureUpdateError() : void
    {
        $offset = 0;
        $token = 'TOKEN';
            
        $this->expectException(\Error::class);
        
        $actual = Bot::secureUpdate($offset, $token);
    }
        
    /**
     * @group bot
     * @coversNothing
     */
    public function sendMessageProvider() : array
    {
        $data = [
            'chat_id' => [
                'message' => 'Test metodo Bot::sendMesssage con chat_id',
                'chatId' => '474912563',
                'token' => TOKEN,
                'expected' => '{
                    "ok": true,
                    "result": {
                        "message_id": *,
                        "from": {
                            "id": 1494758588,
                            "is_bot": true,
                            "first_name": "BotScarichi",
                            "username": "ScarichiBot"
                        },
                        "chat": {
                            "id": 474912563,
                            "first_name": "Vania",
                            "last_name": "Carta",
                            "username": "vaniacarta",
                            "type": "private"
                        },
                        "date": *,
                        "text": "Test+metodo+Bot::sendMesssage+con+chat_id"
                    }
                }'
            ],
            'no chat_id' => [
                'message' => 'Test metodo Bot::sendMesssage senza chat_id',
                'chatId' => CHATID,
                'token' => TOKEN,
                'expected' => '{
                    "ok": true,
                    "result": {
                        "message_id": *,
                        "from": {
                            "id": 1494758588,
                            "is_bot": true,
                            "first_name": "BotScarichi",
                            "username": "ScarichiBot"
                        },
                        "chat": {
                            "id": 474912563,
                            "first_name": "Vania",
                            "last_name": "Carta",
                            "username": "vaniacarta",
                            "type": "private"
                        },
                        "date": *,
                        "text": "Test+metodo+Bot::sendMesssage+senza+chat_id"
                    }
                }'
            ],
            'chat_id error' => [
                'message' => 'Test metodo Bot::sendMesssage con chat_id errato',
                'chatId' => 'pippo',
                'token' => TOKEN,
                'expected' => '{
                    "ok": false,
                    "error_code": 400,
                    "description": "Bad+Request:+chat+not+found"
                }'
            ]           
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::sendMessage
     * @dataProvider sendMessageProvider
     */
    public function testSendMessageContainsString(string $message, string $chatId, string $token, string $notFormatted) : void
    {
        $noSpace = preg_replace('/[\s]/', '', $notFormatted);
        $withSpace = preg_replace('/[+]/', ' ', $noSpace);
        $expecteds = explode('*', $withSpace);
        
        $actual = Bot::sendMessage($message, $chatId, $token);
        
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);            
        }
    }   
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::sendMessage
     */
    public function testSendMessageException() : void
    {
        $message = '';
        $chatId = CHATID;
        $token = TOKEN; 
        
        $this->expectException(\Exception::class);
        
        Bot::sendMessage($message, $chatId, $token);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::secureSend
     */
    public function secureSendProvider() : array
    {
        $data = [
            'chat_id' => [
                'message' => 'Test metodo Bot::secureSend con chat_id',
                'chatId' => '474912563',
                'token' => null,
                'expected' => true
            ],
            'no chat_id' => [
                'message' => 'Test metodo Bot::secureSend senza chat_id',
                'chatId' => null,
                'token' => null,
                'expected' => true
            ],
            'chat_id error' => [
                'message' => 'Test metodo Bot::secureSend con chat_id inesistente',
                'chatId' => 'pippo',
                'token' => null,
                'expected' => false
            ]           
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::secureSend
     * @dataProvider secureSendProvider
     */
    public function testSecureSendEquals(string $message, ?string $chatId, ?string $token, bool $expected) : void
    {
        $actual = Bot::secureSend($message, $chatId, $token);
        
        $this->assertEquals($expected, $actual); 
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::secureSend
     */
    public function testSecureSendException() : void
    {
        $message = '';
        $chatId = CHATID;
        $token = TOKEN; 
        
        $this->expectException(\Exception::class);
        
        Bot::secureSend($message, $chatId, $token);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::arrayShift
     */
    public function testArrayShiftEquals() : void    
    {
        $results = [
            [
                'paperino',
                'zio paperone',
                'nipoti' => [
                    'qui',
                    'quo',
                    'qua'
                ]
            ],
            [
                'topolinia' => [
                    'topolino',
                    'pippo'
                ]
            ]
        ];
        
        $expected = [
            [
                'topolinia' => [
                    'topolino',
                    'pippo'
                ]
            ]
        ];
        
        $actual = Bot::arrayShift($results);
        
        $this->assertEquals($expected, $actual); 
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::arrayShift
     */
    public function testArrayShiftException() : void
    {
        $results = [
            'paperopoli' => [
                'paperino',
                'zio paperone',
                'nipoti' => [
                    'qui',
                    'quo',
                    'qua'
                ]
            ],
            [
                'topolinia' => [
                    'topolino',
                    'pippo'
                ]
            ]
        ];
        
        $this->expectException(\Exception::class);
        
        Bot::arrayShift($results);
    }
        
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::updateOffset
     */
    public function testUpdateOffsetVoidUpdatesEquals() : void
    {
        $start = 999;
        $updates = [];
        $expected = 999;
        
        Reflections::setProperty($this->bot, 'start', $start);
        Reflections::setProperty($this->bot, 'updates', $updates);
        Reflections::invokeMethod($this->bot, 'updateOffset');
        
        $actual = Reflections::getProperty($this->bot, 'end');
        
        $this->assertEquals($expected, $actual);          
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::updateOffset
     */
    public function testUpdateOffsetEquals() : void
    {
        $start = 998;
        $updates = [
            [
                'update_id' => 999,
                'message' => []
            ],
            [
                'update_id' => 1000,
                'message' => []
            ]
        ];
        $expected = 1000;
        
        Reflections::setProperty($this->bot, 'start', $start);
        Reflections::setProperty($this->bot, 'updates', $updates);
        Reflections::invokeMethod($this->bot, 'updateOffset');
        
        $actual = Reflections::getProperty($this->bot, 'end');
        
        $this->assertEquals($expected, $actual);         
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::updateOffset
     */
    public function testUpdateOffsetException() : void
    {
        $start = 67895678;
                
        $updates = [
            [
                'message' => [
                    'chat' => [
                        'id' => 7777
                    ]
                ]
            ]                
        ];        
        
        $this->expectException(\Exception::class);
        
        Reflections::setProperty($this->bot, 'start', $start);
        Reflections::setProperty($this->bot, 'updates', $updates);
        Reflections::invokeMethod($this->bot, 'updateOffset');
    }
    
    /**
     * @group bot
     * @coversNothing
     */
    public function setAutorizedProvider() : array
    {
        $data = [
            'standard' => [
                'updates' => [
                    [
                        'update_id' => 999,
                        'message' => [
                            'chat' => [
                                'id' => 7777
                            ]
                        ]
                    ],
                    [
                        'update_id' => 1000,
                        'message' => [
                            'chat' => [
                                'id' => 9999
                            ]
                        ]
                    ],                    
                    [
                        'update_id' => 1001,
                        'message' => [
                            'chat' => [
                                'id' => 8888
                            ]
                        ]
                    ],
                    [
                        'update_id' => 1002,
                        'message' => [
                            'chat' => []
                        ]
                    ],
                    [
                        'update_id' => 1003,
                        'message' => [
                            'chat' => [
                                'id' => 6666
                            ]
                        ]
                    ]
                ],
                'chats' => [
                    7777,
                    8888
                ],
                'expecteds' => [
                    'yes' => [
                        999,
                        1001
                    ],
                    'not' => [
                        1000,
                        1003
                    ],
                    'undef' => [
                        1002
                    ]
                ]
            ],
            'no chat' => [
                'updates' => [
                    [
                        'update_id' => 999,
                        'message' => [
                            'chat' => [
                                'id' => 7777
                            ]
                        ]
                    ]
                ],
                'chats' => [],
                'expecteds' => [
                    'yes' => [],
                    'not' => [
                        999
                    ],
                    'undef' => []
                ]
            ],
            'no updates' => [
                'updates' => [],
                'chats' => [
                    7777,
                    8888
                ],
                'expecteds' => [
                    'yes' => [],
                    'not' => [],
                    'undef' => []
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::setAutorized
     * @dataProvider setAutorizedProvider
     */
    public function testSetAutorizedEquals(array $updates, array $chats, array $expected) : void
    {
        Reflections::setProperty($this->bot, 'updates', $updates);
        Reflections::setProperty($this->bot, 'chats', $chats);
        Reflections::invokeMethod($this->bot, 'setAutorized');
        
        $actual = Reflections::getProperty($this->bot, 'autorized');
        
        $this->assertEquals($expected, $actual);         
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::setAutorized
     */
    public function testSetAutorizedException() : void
    {
        $updates = [
            [
                'message' => [
                    'chat' => [
                        'id' => 7777
                    ]
                ]
            ]                
        ];        
        $chats = [
            7777,
            8888
        ];
        
        $this->expectException(\Exception::class);
        
        Reflections::setProperty($this->bot, 'updates', $updates);
        Reflections::setProperty($this->bot, 'chats', $chats);
        Reflections::invokeMethod($this->bot, 'setAutorized');
    }
    
    /**
     * @group bot
     * @coversNothing
     */
    public function setCommandsProvider() : array
    {
        $data = [
            'standard' => [
                'updates' => [
                    [
                        'update_id' => 999,
                        'message' => [
                            'chat' => [
                                'id' => 7777
                            ],
                            'entities' => [
                                [
                                    'type' => 'bot_command'
                                ]
                            ]
                        ]
                    ],
                    [
                        'update_id' => 1000,
                        'message' => [
                            'chat' => [
                                'id' => 9999
                            ],
                            'entities' => [
                                [
                                    'type' => 'bot_command'
                                ]
                            ]
                        ]
                    ],
                    [
                        'update_id' => 1001,
                        'message' => [
                            'chat' => [
                                'id' => 8888
                            ],
                            'entities' => [
                                [
                                    'type' => 'canal'
                                ]
                            ]
                        ]
                    ],
                    [
                        'update_id' => 1002,
                        'message' => [
                            'chat' => [
                                'id' => 6666
                            ]
                        ]
                    ]
                ],
                'autorized' => [
                    'yes' => [
                        999,
                        1001
                    ],
                    'not' => [
                        1000,
                        1002
                    ]
                ],
                'expecteds' => [
                    'yes' => [
                        999
                    ],
                    'not' => [
                        1000
                    ],
                    'undef' => [
                        1001,
                        1002
                    ]
                ]
            ],
            'undef autorized' => [
                'updates' => [
                    [
                        'update_id' => 999,
                        'message' => [                           
                            'entities' => [
                                [
                                    'type' => 'bot_command'
                                ]
                            ]
                        ]
                    ]
                ],
                'autorized' => [
                    'yes' => [],
                    'not' => [],
                    'undef' => [
                        999
                    ]
                ],
                'expecteds' => [
                    'yes' => [],
                    'not' => [],
                    'undef' => [
                        999
                    ]
                ]
            ],
            'no updates' => [
                'updates' => [],
                'autorized' => [
                    'yes' => [],
                    'not' => [],
                    'undef' => []
                ],
                'expecteds' => [
                    'yes' => [],
                    'not' => [],
                    'undef' => []
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::setCommands
     * @dataProvider setCommandsProvider
     */
    public function testSetCommandsEquals(array $updates, array $autorized, array $expected) : void
    {
        Reflections::setProperty($this->bot, 'updates', $updates);
        Reflections::setProperty($this->bot, 'autorized', $autorized);
        Reflections::invokeMethod($this->bot, 'setCommands');
        
        $actual = Reflections::getProperty($this->bot, 'commands');
        
        $this->assertEquals($expected, $actual);         
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::setCommands
     */
    public function testSetCommandsException() : void
    {
        $updates = [
            [
                'message' => [
                    'chat' => [
                        'id' => 7777
                    ]
                ]
            ]                
        ];        
        $autorized = [
            'yes' => [],
            'not' => [],
            'undef' => []
        ];
        
        $this->expectException(\Exception::class);
        
        Reflections::setProperty($this->bot, 'updates', $updates);
        Reflections::setProperty($this->bot, 'chats', $autorized);
        Reflections::invokeMethod($this->bot, 'setCommands');
    }
    
    /**
     * @group bot
     * @coversNothing
     */
    public function sendRepliesProvider() : array
    {
        $data = [
            'rejections sended' => [
                'autorized' => false,
                'updates' => [
                    [
                        'update_id' => 1000,
                        'message' => [
                            'chat' => [
                                'id' => 474912563
                            ],
                            'entities' => [
                                [
                                    'type' => 'bot_command'
                                ]
                            ]
                        ]
                    ]
                ],
                'commands' => [
                    'yes' => [],
                    'not' => [
                        1000,
                    ]
                ],
                'token' => TOKEN,
                'expected' => true
            ],
            'rejections null to send' => [
                'autorized' => false,
                'updates' => [
                    [
                        'update_id' => 1000,
                        'message' => [
                            'chat' => [
                                'id' => 474912563
                            ],
                            'entities' => [
                                [
                                    'type' => 'bot_command'
                                ]
                            ]
                        ]
                    ]
                ],
                'commands' => [
                    'yes' => [],
                    'not' => []
                ],
                'token' => TOKEN,
                'expected' => true
            ],
            'rejections not sended' => [
                'autorized' => false,
                'updates' => [
                    [
                        'update_id' => 1000,
                        'message' => [
                            'chat' => [
                                'id' => 9999
                            ],
                            'entities' => [
                                [
                                    'type' => 'bot_command'
                                ]
                            ]
                        ]
                    ]
                ],
                'commands' => [
                    'yes' => [],
                    'not' => [
                        1000,
                    ]
                ],
                'token' => TOKEN,
                'expected' => false
            ],
            'rejections bad update' => [
                'autorized' => false,
                'updates' => [
                    [
                        'update_id' => 1002,
                        'pluto' => [
                            'pippo' => [
                                'paperino' => 9999
                            ],
                            'entities' => [
                                [
                                    'type' => 'bot_command'
                                ]
                            ]
                        ]
                    ],
                    [
                        'update_id' => 1003,
                        'message' => [
                            'chat' => [
                                'id' => 474912563
                            ],
                            'entities' => [
                                [
                                    'type' => 'bot_command'
                                ]
                            ]
                        ]
                    ]
                ],
                'commands' => [
                    'yes' => [],
                    'not' => [
                        1002,
                        1003
                    ]
                ],
                'token' => TOKEN,
                'expected' => false
            ],
            'replies sended' => [
                'autorized' => true,
                'updates' => [
                    [
                        'update_id' => 1000,
                        'message' => [
                            'chat' => [
                                'id' => 474912563
                            ],
                            'text' => '/volume 30030 20/01/2020 27/01/2020',
                            'entities' => [
                                [
                                    'type' => 'bot_command'
                                ]
                            ]
                        ]
                    ]
                ],
                'commands' => [
                    'yes' => [
                        1000
                    ],
                    'not' => []
                ],
                'token' => TOKEN,
                'expected' => true
            ],
            'replies not sended' => [
                'autorized' => true,
                'updates' => [
                    [
                        'update_id' => 1000,
                        'message' => [
                            'chat' => [
                                'id' => 9999
                            ],
                            'text' => '/volume 30030 20/01/2020 27/01/2020',
                            'entities' => [
                                [
                                    'type' => 'bot_command'
                                ]
                            ]
                        ]
                    ]
                ],
                'commands' => [
                    'yes' => [
                        1000
                    ],
                    'not' => []
                ],
                'token' => TOKEN,
                'expected' => false
            ],
            'replies no chat id' => [
                'autorized' => true,
                'updates' => [
                    [
                        'update_id' => 1000,
                        'pippo' => [
                            'chat' => [
                                'id' => 474912563
                            ],
                            'text' => '/volume 30030 20/01/2020 27/01/2020',
                            'entities' => [
                                [
                                    'type' => 'bot_command'
                                ]
                            ]
                        ]
                    ]
                ],
                'commands' => [
                    'yes' => [
                        1000
                    ],
                    'not' => []
                ],
                'token' => TOKEN,
                'expected' => false
            ],
            'replies no text' => [
                'autorized' => true,
                'updates' => [
                    [
                        'update_id' => 1000,
                        'message' => [
                            'chat' => [
                                'id' => 474912563
                            ],
                            'entities' => [
                                [
                                    'type' => 'bot_command'
                                ]
                            ]
                        ]
                    ]
                ],
                'commands' => [
                    'yes' => [
                        1000
                    ],
                    'not' => []
                ],
                'token' => TOKEN,
                'expected' => false
            ],
            'replies bad command' => [
                'autorized' => true,
                'updates' => [
                    [
                        'update_id' => 1000,
                        'message' => [
                            'chat' => [
                                'id' => 474912563
                            ],
                            'text' => '/volume',
                            'entities' => [
                                [
                                    'type' => 'bot_command'
                                ]
                            ]
                        ]
                    ]
                ],
                'commands' => [
                    'yes' => [
                        1000
                    ],
                    'not' => []
                ],
                'token' => TOKEN,
                'expected' => false
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::sendReplies
     * @dataProvider sendRepliesProvider
     */
    public function testSendRepliesEquals(bool $autorized, array $updates, array $commands, string $token, bool $expected) : void
    {
        Reflections::setProperty($this->bot, 'updates', $updates);
        Reflections::setProperty($this->bot, 'commands', $commands);
        Reflections::setProperty($this->bot, 'token', $token);
        $actual = Reflections::invokeMethod($this->bot, 'sendReplies', array($autorized));
        
        $this->assertEquals($expected, $actual);         
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::sendReplies
     */
    public function testSendRepliesException() : void
    {
        $autorized = false;
        $updates = [
            [
                'message' => [
                    'chat' => [
                        'id' => 7777
                    ]
                ]
            ],
            [
                'update_id' => 1002,
                'message' => [
                    'chat' => [
                        'id' => 6666
                    ]
                ]
            ]
        ];        
        $commands = [
            'yes' => [],
            'not' => [
                1002
            ],
            'undef' => []
        ];
        $token = TOKEN;
        
        $this->expectException(\Exception::class);
        
        Reflections::setProperty($this->bot, 'updates', $updates);
        Reflections::setProperty($this->bot, 'commands', $commands);
        Reflections::setProperty($this->bot, 'token', $token);
        Reflections::invokeMethod($this->bot, 'sendReplies', array($autorized));
    }
    
    /**
     * @group bot
     * @coversNothing
     */
    public function constructorProvider() : array
    {
        $data = [
            'standard' => [
                'args' => [
                    'token' => TOKEN,
                    'offset' => 0,
                    'chats' => [
                        99908766,
                        98047382
                    ]
                ],    
                'expecteds' => [
                    'token' => TOKEN,
                    'start' => 0,
                    'end' => 0,
                    'chats' => [
                        99908766,
                        98047382
                    ],
                    'updates' => [],
                    'autorized' => [
                        'yes' => [],
                        'not' => [],
                        'undef' => []                        
                    ],
                    'commands'=> [
                        'yes' => [],
                        'not' => [],
                        'undef' => []
                    ]
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::__construct
     * @dataProvider constructorProvider
     */
    public function testConstructorEquals(array $args, array $expected) : void
    {
        Reflections::invokeConstructor($this->bot, array($args));
        
        $actual['token'] = Reflections::getProperty($this->bot, 'token');
        $actual['start'] = Reflections::getProperty($this->bot, 'start');
        $actual['end'] = Reflections::getProperty($this->bot, 'end');
        $actual['chats'] = Reflections::getProperty($this->bot, 'chats');
        $actual['updates'] = Reflections::getProperty($this->bot, 'updates');
        $actual['autorized'] = Reflections::getProperty($this->bot, 'autorized');
        $actual['commands'] = Reflections::getProperty($this->bot, 'commands');
        
        $this->assertEquals($expected, $actual);         
    }
    
    /**
     * @group bot
     * @coversNothing
     */
    public function constructorExceptionProvider() : array
    {
        $data = [
            'token' => [
                'args' => [
                    'token' => 23456789,
                    'offset' => 0,
                    'chats' => []
                ]
            ],
            'offset' => [
                'args' => [
                    'token' => TOKEN,
                    'offset' => 'pippo',
                    'chats' => []
                ]
            ],
            'chats' => [
                'args' => [
                    'token' => TOKEN,
                    'offset' => 0,
                    'chats' => 45673456
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::__construct
     * @dataProvider constructorExceptionProvider
     */
    public function testConstructorException(array $args) : void
    {
        $this->expectException(\Exception::class);
        
        Reflections::invokeConstructor($this->bot, array($args));
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::getProperties
     */
    public function testGetPropertiesEquals() : void
    {
        $expected = [
            'token' => Reflections::getProperty($this->bot, 'token'), 
            'offset' => Reflections::getProperty($this->bot, 'end'),
            'chats' => Reflections::getProperty($this->bot, 'chats')
        ];
        
        $actual = $this->bot->getProperties();
        
        $this->assertEquals($expected, $actual); 
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::getProperties
     */
    public function testGetPropertiesException() : void
    {
        Reflections::setProperty($this->bot, 'token', null);
        
        $this->expectException(\Exception::class);
        
        $actual = $this->bot->getProperties();
    }
    
    /**
     * @coversNothing
     */
    public function checkStructProvider() : array
    {
        $update = [
            'update_id' => 1002,
            'message' => [
                'chat' => [
                    'id' => 6666
                ]
            ]
        ];
        
        $data = [
            'true' => [
                'master' => $update,
                'keys' => ['message','chat','id'],
                'expected' => true
            ],
            'false' => [
                'master' => $update,
                'keys' => ['parameters'],
                'expected' => false
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::checkStruct
     * @dataProvider checkStructProvider
     */
    public function testCheckStructEquals($master, $keys, $expected) : void
    {
        $actual = Bot::checkStruct($master, $keys);
        
        $this->assertEquals($expected, $actual);
    }
}
