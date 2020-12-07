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
     * @coversNothing
     */
    public function replyMessageProvider() : array
    {
        $data = [
            'chat_id' => [
                'message' => 'Test metodo Bot::sendMesssage con chat_id',
                'messageId' => '2500',
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
                'messageId' => '2500',                
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
                'messageId' => '2500',
                'chatId' => 'pippo',
                'token' => TOKEN,
                'expected' => '{
                    "ok": false,
                    "error_code": 400,
                    "description": "Bad+Request:+chat+not+found"
                }'
            ],
            'message_id error' => [
                'message' => 'Test metodo Bot::sendMesssage con chat_id errato',
                'messageId' => '999999',
                'chatId' => CHATID,
                'token' => TOKEN,
                'expected' => '{
                    "ok": false,
                    "error_code": 400,
                    "description": "Bad+Request:+reply+message+not+found"
                }'
            ]           
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::replyMessage
     * @dataProvider replyMessageProvider
     */
    public function testReplyMessageContainsString(string $message, string $messageId, string $chatId, string $token, string $notFormatted) : void
    {
        $noSpace = preg_replace('/[\s]/', '', $notFormatted);
        $withSpace = preg_replace('/[+]/', ' ', $noSpace);
        $expecteds = explode('*', $withSpace);
        
        $actual = Bot::replyMessage($message, $messageId, $chatId, $token);
        
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);            
        }
    }   
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::replyMessage
     */
    public function testReplyMessageException() : void
    {
        $message = '';
        $messageId = '2500';
        $chatId = CHATID;
        $token = TOKEN; 
        
        $this->expectException(\Exception::class);
        
        Bot::replyMessage($message, $messageId, $chatId, $token);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::secureSend
     */
    public function secureSendProvider() : array
    {
        $data = [
            'message_id' => [
                'message' => 'Test metodo Bot::secureSend con message_id',
                'messageId' => '2500',
                'chatId' => '474912563',
                'token' => null,
                'expected' => true
            ],
            'chat_id' => [
                'message' => 'Test metodo Bot::secureSend con chat_id',
                'messageId' => null,
                'chatId' => '474912563',
                'token' => null,
                'expected' => true
            ],
            'no chat_id' => [
                'message' => 'Test metodo Bot::secureSend senza chat_id',
                'messageId' => null,
                'chatId' => null,
                'token' => null,
                'expected' => true
            ],
            'chat_id error' => [
                'message' => 'Test metodo Bot::secureSend con chat_id inesistente',
                'messageId' => null,
                'chatId' => 'pippo',
                'token' => null,
                'expected' => false
            ],
            'message_id safe error' => [
                'message' => 'Test metodo Bot::secureSend con message_id inesistente',
                'messageId' => '9999999',
                'chatId' => '474912563',
                'token' => null,
                'expected' => true
            ]           
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::secureSend
     * @dataProvider secureSendProvider
     */
    public function testSecureSendEquals(string $message, ?string $messageId, ?string $chatId, ?string $token, bool $expected) : void
    {
        $actual = Bot::secureSend($message, $chatId, $messageId, $token);
        
        $this->assertEquals($expected, $actual); 
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::secureSend
     */
    public function testSecureSendException() : void
    {
        $message = '';
        $messageId = null;
        $chatId = CHATID;
        $token = TOKEN; 
        
        $this->expectException(\Exception::class);
        
        Bot::secureSend($message, $chatId, $messageId, $token);
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
                            'message_id' => 2500,
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
                            'message_id' => 2500,
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
                            'message_id' => 2500,
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
                            'message_id' => 2500,
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
                            'message_id' => 2500,
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
                            'message_id' => 2500,
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
                            'message_id' => 2500,
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
                            'message_id' => 2500,
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
                'expected' => true
            ],
            'replies void command' => [
                'autorized' => true,
                'updates' => [
                    [
                        'update_id' => 1000,
                        'message' => [
                            'message_id' => 2500,
                            'chat' => [
                                'id' => 474912563
                            ],
                            'text' => '',
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
                    'botName' => 'BotScarichi',
                    'token' => TOKEN,
                    'offset' => 0,
                    'commands' => [
                        'volume'
                    ],
                    'chats' => [
                        99908766,
                        98047382
                    ]
                ],    
                'expecteds' => [
                    'botName' => 'BotScarichi',
                    'token' => TOKEN,
                    'start' => 0,
                    'end' => 0,
                    'botCommands' => [
                        'volume'
                    ],
                    'methods' => [
                        'selector' => '',
                        'tester' => '',
                        'runner' => '',
                        'messager' => ''
                    ],
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
        
        $actual['botName'] = Reflections::getProperty($this->bot, 'botName');
        $actual['token'] = Reflections::getProperty($this->bot, 'token');
        $actual['start'] = Reflections::getProperty($this->bot, 'start');
        $actual['end'] = Reflections::getProperty($this->bot, 'end');
        $actual['botCommands'] = Reflections::getProperty($this->bot, 'botCommands');
        $actual['methods'] = Reflections::getProperty($this->bot, 'methods');
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
            'botName' => [
                'args' => [
                    'botName' => 12345,
                    'token' => 23456789,
                    'offset' => 0,
                    'commands' => [
                        'volume'
                    ],
                    'chats' => []
                ]
            ],
            'token' => [
                'args' => [
                    'botName' => 'BotScarichi',
                    'token' => 23456789,
                    'offset' => 0,
                    'commands' => [
                        'volume'
                    ],
                    'chats' => []
                ]
            ],
            'offset' => [
                'args' => [
                    'botName' => 'BotScarichi',
                    'token' => TOKEN,
                    'offset' => 'pippo',
                    'commands' => [
                        'volume'
                    ],
                    'chats' => []
                ]
            ],
            'command' => [
                'args' => [
                    'botName' => 'BotScarichi',
                    'token' => TOKEN,
                    'offset' => 0,
                    'commands' => 'pippo',                    
                    'chats' => []
                ]
            ],
            'chats' => [
                'args' => [
                    'botName' => 'BotScarichi',
                    'token' => TOKEN,
                    'offset' => 0,
                    'commands' => [
                        'volume'
                    ],
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
            'botName' => Reflections::getProperty($this->bot, 'botName'),
            'token' => Reflections::getProperty($this->bot, 'token'), 
            'offset' => Reflections::getProperty($this->bot, 'end'),
            'commands' => Reflections::getProperty($this->bot, 'botCommands'),
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
    
    /**
     * @coversNothing
     */
    public function reportNotAllowedProvider() : array
    {
        $data = [
            'true' => [
                'updates' => [
                    'update_id' => 1000,
                    'message' => [
                        'message_id' => 2500,
                        'chat' => [
                            'id' => 6666
                        ]
                    ]
                ],
                'keys' => ['message','chat','id'],
                'expected' => ''
            ],
            'false' => [
                'updates' => [
                    'update_id' => 1000,
                    'inline_message' => [
                        'message_id' => 2500,
                        'chat' => [
                            'id' => 6666
                        ]
                    ]
                ],
                'keys' => ['message','chat','id'],
                'expected' => "<b>Notifica:</b> Struttura updates id <b>1000</b> non compatibile. Struttura message->chat->id non presente.<br/>"
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::reportNotAllowed
     * @dataProvider reportNotAllowedProvider
     */
    public function testReportNotAllowedOutputEquals($update, $keys, $expected) : void
    {
        $this->expectOutputString($expected);
        
        Bot::reportNotAllowed($update, $keys);
    }
            
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::reportNotAllowed
     */
    public function testReportNotAllowedException() : void
    {
        $update = [];
        $keys = ['message','chat','id'];
        
        $this->expectException(\Exception::class);
        
        Bot::reportNotAllowed($update, $keys);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::getChatId
     */
    public function testGetChatIdEquals() : void
    {
        $update = [
            'update_id' => 1002,
            'message' => [
                'chat' => [
                    'id' => 6666
                ]
            ]
        ];
        $expected = 6666;
        
        $actual = Reflections::invokeMethod($this->bot, 'getChatId', array($update));
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::getChatId
     */
    public function testGetChatIdException() : void
    {
        $update = [];
        
        $this->expectException(\Exception::class);
        
        Reflections::invokeMethod($this->bot, 'getChatId', array($update));
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::getMessageId
     */
    public function testGetMessageIdEquals() : void
    {
        $update = [
            'update_id' => 1002,
            'message' => [
                'message_id' => 2500
            ]
        ];
        $expected = 2500;
        
        $actual = Reflections::invokeMethod($this->bot, 'getMessageId', array($update));
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::getMessageId
     */
    public function testGetMessageIdException() : void
    {
        $update = [];
        
        $this->expectException(\Exception::class);
        
        Reflections::invokeMethod($this->bot, 'getMessageId', array($update));
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::getText
     */
    public function testGetTextEquals() : void
    {
        $update = [
            'update_id' => 1002,
            'message' => [
                'chat' => [
                    'id' => 6666
                ],
                'text' => '/volume'
            ]
        ];
        $expected = '/volume';
        
        $actual = Reflections::invokeMethod($this->bot, 'getText', array($update));
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::getText
     */
    public function testGetTextException() : void
    {
        $update = [];
        
        $this->expectException(\Exception::class);
        
        Reflections::invokeMethod($this->bot, 'getText', array($update));
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::getEntityType
     */
    public function testGetEntityTypeEquals() : void
    {
        $update = [
            'update_id' => 1002,
            'message' => [
                'chat' => [
                    'id' => 6666
                ],
                'text' => '/volume',
                'entities' => [
                    [
                        'type' => 'bot_command'
                    ]
                ]
            ]
        ];
        $expected = 'bot_command';
        
        $actual = Reflections::invokeMethod($this->bot, 'getEntityType', array($update));
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::getEntityType
     */
    public function testGetEntityTypeException() : void
    {
        $update = [];
        
        $this->expectException(\Exception::class);
        
        Reflections::invokeMethod($this->bot, 'getEntityType', array($update));
    }
    
    /**
     * @coversNothing
     */
    public function prepareReplyProvider() : array
    {
        $update = [
            'update_id' => 1002,
            'message' => [
                'chat' => [
                    'id' => 6666
                ],
                'text' => '/volume 30030 20/01/2020 27/01/2020',
                'entities' => [
                    [
                        'type' => 'bot_command'
                    ]
                ]
            ]
        ];
        
        $data = [
            'autorized true' => [
                'update' => $update,
                'autorized' => true,
                'expected' => 'Il volume movimentato da <b>30030</b>' . PHP_EOL . 'dal <i>20/01/2020</i> alle <i>00:00:00</i>' . PHP_EOL . 'al <i>26/01/2020</i> alle <i>23:30:00</i>' . PHP_EOL . 'é di <b>17.058.327,860 mc</b>.'
            ],
            'autorized false' => [
                'update' => $update,
                'autorized' => false,
                'expected' => 'Non sei autorizzato ad usare i comandi di BotScarichi.'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::prepareReply
     * @dataProvider prepareReplyProvider
     */
    public function testPrepareReplyEquals(array $update, bool $isAutorized, string $expected) : void
    {
        $actual = Reflections::invokeMethod($this->bot, 'prepareReply', array($update, $isAutorized));
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::prepareReply
     */
    public function testPrepareReplyException() : void
    {
        $update = [];
        $isAutorized = true;
        
        $this->expectException(\Exception::class);
        
        Reflections::invokeMethod($this->bot, 'prepareReply', array($update, $isAutorized));
    }
    
    /**
     * @coversNothing
     */
    public function sendReplyProvider() : array
    {
        $update = [
            'update_id' => 1002,
            'message' => [
                'message_id' => 2500,
                'chat' => [
                    'id' => 474912563
                ]
            ]
        ];
        
        $data = [
            'standard' => [
                'update' => $update,
                'message' => 'Test metodo Bot::sendReply',
                'expected' => true
            ],
            'no message' => [
                'update' => $update,
                'message' => '',
                'expected' => false
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::sendReply
     * @dataProvider sendReplyProvider
     */
    public function testSendReplyEquals(array $update, ?string $message, bool $expected) : void
    {
        $actual = Reflections::invokeMethod($this->bot, 'sendReply', array($update, $message));
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::sendReply
     */
    public function testSendReplyException() : void
    {
        $update = [];
        $message = 'pippo';
        
        $this->expectException(\Exception::class);
        
        Reflections::invokeMethod($this->bot, 'sendReply', array($update, $message));
    }
    
    /**
     * @coversNothing
     */
    public function getCommandReplyProvider() : array
    {
        $data = [
            'command no exist' => [
                'text' => '/variabile',
                'expected' => 'Comando inesistente.'
            ],
            'volume no param' => [
                'text' => '/volume',
                'expected' => 'Parametri non definiti (es. <i>/volume 30030 01/01/2020 02/01/2020</i>).'
            ],
            'volume param no suff' => [
                'text' => '/volume 30030',
                'expected' => 'Numero parametri non sufficiente:' . PHP_EOL . 'inserire l\'id della variabile e almeno una data.'
            ],
            'volume 2 param error' => [
                'text' => '/volume pippo pluto',
                'expected' => 'Il primo parametro deve essere l\'id della variabile:' . PHP_EOL . 'inserire un numero compreso fra 30000 e 39999.' . PHP_EOL . 'Il secondo parametro deve essere una data valida:' . PHP_EOL . 'utilizzare il formato dd/mm/yyyy.'
            ],
            'volume 3 param error' => [
                'text' => '/volume pippo pluto paperino',
                'expected' => 'Il primo parametro deve essere l\'id della variabile:' . PHP_EOL . 'inserire un numero compreso fra 30000 e 39999.' . PHP_EOL . 'Il secondo parametro deve essere una data valida:' . PHP_EOL . 'utilizzare il formato dd/mm/yyyy.' . PHP_EOL . 'Il terzo parametro deve essere una data valida:' . PHP_EOL . 'utilizzare il formato dd/mm/yyyy.'
            ],
            'volume var error' => [
                'text' => '/volume 40000 01/01/2020',
                'expected' => 'Il primo parametro deve essere l\'id della variabile:' . PHP_EOL . 'inserire un numero compreso fra 30000 e 39999.'
            ],
            'volume datefrom error' => [
                'text' => '/volume 30030 32/01/2020',
                'expected' => 'Il secondo parametro deve essere una data valida:' . PHP_EOL . 'utilizzare il formato dd/mm/yyyy.'
            ],
            'volume dateto error' => [
                'text' => '/volume 30030 01/01/2020 32/01/2020',
                'expected' => 'Il terzo parametro deve essere una data valida:' . PHP_EOL . 'utilizzare il formato dd/mm/yyyy.'
            ],
            'volume dateto now' => [
                'text' => '/volume 30030 28/05/2020',
                'expected' => 'Il volume movimentato da <b>30030</b>' . PHP_EOL . 'dal <i>28/05/2020</i> alle <i>00:00:00</i>' . PHP_EOL . 'al <i>29/05/2020</i> alle <i>22:00:00</i>' . PHP_EOL . 'é di <b>209.120,610 mc</b>.'
            ],
            'volume standard' => [
                'text' => '/volume 30030 20/01/2020 27/01/2020',
                'expected' => 'Il volume movimentato da <b>30030</b>' . PHP_EOL . 'dal <i>20/01/2020</i> alle <i>00:00:00</i>' . PHP_EOL . 'al <i>26/01/2020</i> alle <i>23:30:00</i>' . PHP_EOL . 'é di <b>17.058.327,860 mc</b>.'
            ],
            'volume data out range' => [
                'text' => '/volume 30030 20/01/2020 27/01/2099',
                'expected' => 'Il terzo parametro deve essere una data valida:' . PHP_EOL . 'utilizzare il formato dd/mm/yyyy.'
            ],
            'volume no data' => [
                'text' => '/volume 30030 20/01/2079 05/06/2079',
                'expected' => 'Non ci sono dati per il periodo selezionato.'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::getCommandReply
     * @dataProvider getCommandReplyProvider
     */
    public function testGetCommandReplyEquals(string $text, string $expected) : void
    {
        $actual = Reflections::invokeMethod($this->bot, 'getCommandReply', array($text));
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::getCommandReply
     */
    public function testGetCommandReplyException() : void
    {
        $text = '';
        
        $this->expectException(\Exception::class);
        
        Reflections::invokeMethod($this->bot, 'getCommandReply', array($text));
    }
    
    /**
     * @coversNothing
     */
    public function parseCommandProvider() : array
    {
        $data = [
            'command no exist' => [
                'text' => '/variabile',
                'expected' => [
                    'ok' => false,
                    'error' => 'Comando inesistente.'
                ]
            ],
            'volume no param' => [
                'text' => '/volume',
                'expected' => [
                    'ok' => true,
                    'params' => []
                ]
            ],
            'volume param no suff' => [
                'text' => '/volume 30030',
                'expected' => [
                    'ok' => true,
                    'params' => [
                        '30030'
                    ]
                ]
            ],
            'volume 2 param' => [
                'text' => '/volume 30030 28/05/2020',
                'expected' => [
                    'ok' => true,
                    'params' => [
                        '30030',
                        '28/05/2020'
                    ]
                ]
            ],
            'volume standard' => [
                'text' => '/volume 30030 20/01/2020 27/01/2020',
                'expected' => [
                    'ok' => true,
                    'params' => [
                        '30030',
                        '20/01/2020',
                        '27/01/2020'
                    ]
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::parseCommand
     * @dataProvider parseCommandProvider
     */
    public function testParseCommandEquals(string $text, array $expected) : void
    {
        $actual = Reflections::invokeMethod($this->bot, 'parseCommand', array($text));
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::parseCommand
     */
    public function testParseCommandException() : void
    {
        $text = '';
        
        $this->expectException(\Exception::class);
        
        Reflections::invokeMethod($this->bot, 'parseCommand', array($text));
    }
    
    /**
     * @coversNothing
     */
    public function selectorVolumeProvider() : array
    {
        $data = [
            'no param' => [
                'params' => [],
                'expected' => [
                    'testNames' => [],
                    'errors' => [
                        'Parametri non definiti (es. <i>/volume 30030 01/01/2020 02/01/2020</i>).'
                    ]
                ]
            ],
            '1 param' => [
                'params' => [
                    '30030'
                ],
                'expected' => [
                    'testNames' => [],
                    'errors' => [
                        'Numero parametri non sufficiente:' . PHP_EOL . 'inserire l\'id della variabile e almeno una data.'
                    ]
                ]
            ],
            'default 2' => [
                'params' => [
                    '30030',
                    '27/01/2020'
                ],
                'expected' => [
                    'testNames' => [
                        'variabile',
                        'datefrom',
                        'dateto'
                    ],
                    'errors' => []
                ]
            ],
            'default 3' => [
                'params' => [
                    '30030',
                    '27/01/2020',
                    '28/01/2020'
                ],
                'expected' => [
                    'testNames' => [
                        'variabile',
                        'datefrom',
                        'dateto'
                    ],
                    'errors' => []
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::selectorVolume
     * @dataProvider selectorVolumeProvider
     */
    public function testSelectorVolumeEquals(array $params, array $expected) : void
    {
        $actual = Reflections::invokeMethod($this->bot, 'selectorVolume', array($params));
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function providerTesterVolume() : array
    {
        $dateTime = new \DateTime('NOW');
        $now = $dateTime->format('d/m/Y');
        
        $data = [
            'no param' => [
                'tests' => [
                    'testNames' => [],
                    'errors' => [
                        'Parametri non definiti (es. <i>/volume 30030 01/01/2020 02/01/2020</i>).'
                    ]
                ],
                'params' => [],
                'expected' => [
                    'ok' => false,
                    'errors' => [
                        'Parametri non definiti (es. <i>/volume 30030 01/01/2020 02/01/2020</i>).'
                    ]
                ]
            ],
            '1 param' => [                
                'tests' => [
                    'testNames' => [],
                    'errors' => [
                        'Numero parametri non sufficiente:' . PHP_EOL . 'inserire l\'id della variabile e almeno una data.'
                    ]
                ],
                'params' => [
                    '30030'
                ],
                'expected' => [
                    'ok' => false,
                    'errors' => [
                        'Numero parametri non sufficiente:' . PHP_EOL . 'inserire l\'id della variabile e almeno una data.'
                    ]
                ]
            ],
            'default 2' => [
                'tests' => [
                    'testNames' => [
                        'variabile',
                        'datefrom',
                        'dateto'
                    ],
                    'errors' => []
                ],
                'params' => [
                    '30030',
                    '27/01/2020'
                ],
                'expected' => [
                    'ok' => true,
                    'params' => [
                        '30030',
                        '27/01/2020',
                        $now
                    ]
                ]
            ],
            'default 3' => [
                'tests' => [
                    'testNames' => [
                        'variabile',
                        'datefrom',
                        'dateto'
                    ],
                    'errors' => []
                ],
                'params' => [
                    '30030',
                    '27/01/2020',
                    '28/01/2020'
                ],
                'expected' => [
                    'ok' => true,
                    'params' => [
                        '30030',
                        '27/01/2020',
                        '28/01/2020'
                    ]
                ]
            ],
            'errors' => [
                'tests' => [
                    'testNames' => [
                        'variabile',
                        'datefrom',
                        'dateto'
                    ],
                    'errors' => []
                ],
                'params' => [
                    '40000',
                    '32/01/2020',
                    '28/01/2080'
                ],
                'expected' => [
                    'ok' => false,
                    'errors' => [
                        'Il primo parametro deve essere l\'id della variabile:' . PHP_EOL . 'inserire un numero compreso fra 30000 e 39999.',
                        'Il secondo parametro deve essere una data valida:' . PHP_EOL . 'utilizzare il formato dd/mm/yyyy.',
                        'Il terzo parametro deve essere una data valida:' . PHP_EOL . 'utilizzare il formato dd/mm/yyyy.'
                    ]
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::testerVolume
     * @dataProvider providerTesterVolume
     */
    public function testTesterVolumeEquals(array $tests, array $params, array $expected) : void
    {
        $actual = Reflections::invokeMethod($this->bot, 'testerVolume', array($tests, $params));
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::testerVolume
     */
    public function testTesterVolumeException() : void
    {
        $tests = [
            'testNames' => [],
            'errors' => []
        ];
        $params = [
            '30030',
            '27/01/2020',
            '28/01/2020'            
        ];
        
        $this->expectException(\Exception::class);
        
        Reflections::invokeMethod($this->bot, 'testerVolume', array($tests, $params));
    }
    
    /**
     * @coversNothing
     */
    public function providerTestVariabile() : array
    {
        $data = [
            'standard' => [                
                'params' => [
                    '30030',
                    '01/01/2020',
                    '02/01/2020'
                ],
                'expected' => [
                    'ok' => true,
                    'param' => '30030'
                ]
            ],
            'out of range' => [                
                'params' => [
                    '40000',
                    '01/01/2020',
                    '02/01/2020'
                ],
                'expected' => [
                    'ok' => false,
                    'error' => 'Il primo parametro deve essere l\'id della variabile:' . PHP_EOL . 'inserire un numero compreso fra 30000 e 39999.'
                ]
            ],
            'no number' => [                
                'params' => [
                    'pippo',
                    '01/01/2020',
                    '02/01/2020'
                ],
                'expected' => [
                    'ok' => false,
                    'error' => 'Il primo parametro deve essere l\'id della variabile:' . PHP_EOL . 'inserire un numero compreso fra 30000 e 39999.'
                ]
            ]
            
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::testVariabile
     * @dataProvider providerTestVariabile
     */
    public function testTestVariabileEquals(array $params, array $expected) : void
    {
        $actual = Bot::testVariabile($params);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::testVariabile
     */
    public function testTestVariabileException() : void
    {
        $params = [];
        
        $this->expectException(\Exception::class);
        
        Bot::testVariabile($params);
    }
    
    /**
     * @coversNothing
     */
    public function providerTestDatefrom() : array
    {
        $data = [
            'standard' => [                
                'params' => [
                    '30030',
                    '01/01/2020',
                    '02/01/2020'
                ],
                'expected' => [
                    'ok' => true,
                    'param' => '01/01/2020'
                ]
            ],
            'only 2 params' => [                
                'params' => [
                    '30030',
                    '01/01/2020'
                ],
                'expected' => [
                    'ok' => true,
                    'param' => '01/01/2020'
                ]
            ],
            'out of range' => [                
                'params' => [
                    '30030',
                    '01/01/2080',
                    '02/01/2020'
                ],
                'expected' => [
                    'ok' => false,
                    'error' => 'Il secondo parametro deve essere una data valida:' . PHP_EOL . 'utilizzare il formato dd/mm/yyyy.'
                ]
            ],
            'data error' => [                
                'params' => [
                    '30030',
                    '32/01/2020',
                    '02/01/2020'
                ],
                'expected' => [
                    'ok' => false,
                    'error' => 'Il secondo parametro deve essere una data valida:' . PHP_EOL . 'utilizzare il formato dd/mm/yyyy.'
                ]
            ],
            'format error' => [                
                'params' => [
                    '30030',
                    '2020-01-01',
                    '02/01/2020'
                ],
                'expected' => [
                    'ok' => false,
                    'error' => 'Il secondo parametro deve essere una data valida:' . PHP_EOL . 'utilizzare il formato dd/mm/yyyy.'
                ]
            ]
            
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::testDatefrom
     * @dataProvider providerTestDatefrom
     */
    public function testTestDatefromEquals(array $params, array $expected) : void
    {
        $actual = Bot::testDatefrom($params);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::testDatefrom
     */
    public function testTestDatefromException() : void
    {
        $params = [
            '30030'
        ];
        
        $this->expectException(\Exception::class);
        
        Bot::testDatefrom($params);
    }
    
    /**
     * @coversNothing
     */
    public function providerTestDateto() : array
    {
        $dateTime = new \DateTime('NOW');
        $now = $dateTime->format('d/m/Y');
        
        $data = [
            'standard' => [                
                'params' => [
                    '30030',
                    '01/01/2020',
                    '02/01/2020'
                ],
                'expected' => [
                    'ok' => true,
                    'param' => '02/01/2020'
                ]
            ],
            'only 2 params' => [                
                'params' => [
                    '30030',
                    '01/01/2020'
                ],
                'expected' => [
                    'ok' => true,
                    'param' => $now
                ]
            ],
            'out of range' => [                
                'params' => [
                    '30030',
                    '01/01/2020',
                    '02/01/2080'
                ],
                'expected' => [
                    'ok' => false,
                    'error' => 'Il terzo parametro deve essere una data valida:' . PHP_EOL . 'utilizzare il formato dd/mm/yyyy.'
                ]
            ],
            'data error' => [                
                'params' => [
                    '30030',
                    '01/01/2020',
                    '32/01/2020'
                ],
                'expected' => [
                    'ok' => false,
                    'error' => 'Il terzo parametro deve essere una data valida:' . PHP_EOL . 'utilizzare il formato dd/mm/yyyy.'
                ]
            ],
            'format error' => [                
                'params' => [
                    '30030',
                    '01/01/2020',
                    '2020-01-01'
                ],
                'expected' => [
                    'ok' => false,
                    'error' => 'Il terzo parametro deve essere una data valida:' . PHP_EOL . 'utilizzare il formato dd/mm/yyyy.'
                ]
            ]
            
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::testDateto
     * @dataProvider providerTestDateto
     */
    public function testTestDatetoEquals(array $params, array $expected) : void
    {
        $actual = Bot::testDateto($params);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::testDateto
     */
    public function testTestDatetoException() : void
    {
        $params = [
            '30030'
        ];
        
        $this->expectException(\Exception::class);
        
        Bot::testDateto($params);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::setMethods
     */
    public function testSetMethodsEquals() : void
    {
        $botCommand = 'volume';
        $expected = [
            'selector' => 'Bot::selectorVolume',
            'tester' => 'Bot::testerVolume',
            'runner' => 'Bot::runnerVolume',
            'messager' => 'Bot::messagerVolume'
        ];
        
        Reflections::invokeMethod($this->bot, 'setMethods', array($botCommand));
        
        $actual = Reflections::getProperty($this->bot, 'methods');
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::setMethods
     */
    public function testSetMethodsException() : void
    {
        $botCommand = 'pippo';
        
        $this->expectException(\Exception::class);
        
        Reflections::invokeMethod($this->bot, 'setMethods', array($botCommand));
    }
    
    /**
     * @coversNothing
     */
    public function runnerVolumeProvider() : array
    {
        $data = [            
            'standard' => [
                'variabile' => '30030',
                'datefrom' => '01/01/2017',
                'dateto' => '02/01/2017',
                'provider' => __DIR__ . '/../providers/runnerVolumeTest.json'
            ]        
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::runnerVolume
     * @dataProvider runnerVolumeProvider
     */
    public function testRunnerVolumeEquals(string $variabile, string $datefrom, string $dateto, string $provider) : void
    {
        $expected = Utility::getJsonArray($provider);
        
        $actual = Bot::runnerVolume($variabile, $datefrom, $dateto);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function messagerVolumeProvider() : array
    {
        $data = [            
            'standard' => [
                'response' => [
                    [
                        'variabile' => 30030,
                        'valore' => 0,
                        'data_e_ora' => '01/01/2017 00:00:00',                
                        'tipo_dato' => 1
                    ],
                    [
                        'variabile' => 30030,
                        'valore' => 17058327.861,
                        'data_e_ora' => '02/01/2017 23:30:00',                
                        'tipo_dato' => 1
                    ]
                ],
                'expected' => 'Il volume movimentato da <b>30030</b>' . PHP_EOL . 'dal <i>01/01/2017</i> alle <i>00:00:00</i>' . PHP_EOL . 'al <i>02/01/2017</i> alle <i>23:30:00</i>' . PHP_EOL . 'é di <b>17.058.327,861 mc</b>.'
            ],
            'integer' => [
                'response' => [
                    [
                        'variabile' => 30030,
                        'valore' => 0,
                        'data_e_ora' => '01/01/2017 00:00:00',                
                        'tipo_dato' => 1
                    ],
                    [
                        'variabile' => 30030,
                        'valore' => 17058327,
                        'data_e_ora' => '02/01/2017 23:30:00',                
                        'tipo_dato' => 1
                    ]
                ],
                'expected' => 'Il volume movimentato da <b>30030</b>' . PHP_EOL . 'dal <i>01/01/2017</i> alle <i>00:00:00</i>' . PHP_EOL . 'al <i>02/01/2017</i> alle <i>23:30:00</i>' . PHP_EOL . 'é di <b>17.058.327 mc</b>.'
            ],
            'decimal zero' => [
                'response' => [
                    [
                        'variabile' => 30030,
                        'valore' => 0,
                        'data_e_ora' => '01/01/2017 00:00:00',                
                        'tipo_dato' => 1
                    ],
                    [
                        'variabile' => 30030,
                        'valore' => 17058327.800,
                        'data_e_ora' => '02/01/2017 23:30:00',                
                        'tipo_dato' => 1
                    ]
                ],
                'expected' => 'Il volume movimentato da <b>30030</b>' . PHP_EOL . 'dal <i>01/01/2017</i> alle <i>00:00:00</i>' . PHP_EOL . 'al <i>02/01/2017</i> alle <i>23:30:00</i>' . PHP_EOL . 'é di <b>17.058.327,800 mc</b>.'
            ],
            'void' => [
                'response' => [],
                'expected' => 'Non ci sono dati per il periodo selezionato.'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::messagerVolume
     * @dataProvider messagerVolumeProvider
     */
    public function testMessagerVolumeEquals(array $response, string $expected) : void
    {
        $actual = Bot::messagerVolume($response);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @coversNothing
     */
    public function extractVariabileProvider() : array
    {
        $data = [            
            'standard' => [
                'record' => [
                    'variabile' => '30030',
                    'valore' => '0',
                    'data_e_ora' => '01/01/2017 00:00:00',                
                    'tipo_dato' => '1'
                ],
                'expected' => '30030'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::extractVariabile
     * @dataProvider extractVariabileProvider
     */
    public function testExtractVariabileEquals(array $record, string $expected) : void
    {
        $actual = Bot::extractVariabile($record);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::extractVariabile
     */
    public function testExtractVariabileException() : void
    {
        $record = [
            'var' => '30030',
            'valore' => '0',
            'data_e_ora' => '01/01/2017 00:00:00',                
            'tipo_dato' => '1'
        ];
        
        $this->expectException(\Exception::class);
        
        Bot::extractVariabile($record);
    }
    
    /**
     * @coversNothing
     */
    public function extractDataOraProvider() : array
    {
        $data = [            
            'standard' => [
                'record' => [
                    'variabile' => '30030',
                    'valore' => '0',
                    'data_e_ora' => '01/01/2017 00:00:00',                
                    'tipo_dato' => '1'
                ],
                'expected' => [
                    'data' => '01/01/2017',
                    'ora' => '00:00:00'
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::extractDataOra
     * @dataProvider extractDataOraProvider
     */
    public function testExtractDataOraEquals(array $record, array $expected) : void
    {
        $actual = Bot::extractDataOra($record);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::extractDataOra
     */
    public function testExtractDataOraException() : void
    {
        $record = [
            'variabile' => '30030',
            'valore' => '0',
            'data' => '01/01/2017 00:00:00',                
            'tipo_dato' => '1'
        ];
        
        $this->expectException(\Exception::class);
        
        Bot::extractDataOra($record);
    }
    
    /**
     * @coversNothing
     */
    public function extractValoreProvider() : array
    {
        $data = [            
            'zero' => [
                'record' => [
                    'variabile' => '30030',
                    'valore' => '0',
                    'data_e_ora' => '01/01/2017 00:00:00',                
                    'tipo_dato' => '1'
                ],
                'expected' => '0'
            ],
            'integer not 1000' => [
                'record' => [
                    'variabile' => '30030',
                    'valore' => '30',
                    'data_e_ora' => '01/01/2017 00:00:00',                
                    'tipo_dato' => '1'
                ],
                'expected' => '30'
            ],
            'integer 1000' => [
                'record' => [
                    'variabile' => '30030',
                    'valore' => '1030',
                    'data_e_ora' => '01/01/2017 00:00:00',                
                    'tipo_dato' => '1'
                ],
                'expected' => '1.030'
            ],
            'decimal 2 zeri' => [
                'record' => [
                    'variabile' => '30030',
                    'valore' => '1030,100',
                    'data_e_ora' => '01/01/2017 00:00:00',                
                    'tipo_dato' => '1'
                ],
                'expected' => '1.030,100'
            ],
            'decimal 1 zero' => [
                'record' => [
                    'variabile' => '30030',
                    'valore' => '1030,150',
                    'data_e_ora' => '01/01/2017 00:00:00',                
                    'tipo_dato' => '1'
                ],
                'expected' => '1.030,150'
            ],
            'decimal 1' => [
                'record' => [
                    'variabile' => '30030',
                    'valore' => '1030,1',
                    'data_e_ora' => '01/01/2017 00:00:00',                
                    'tipo_dato' => '1'
                ],
                'expected' => '1.030,100'
            ],
            'decimal 10' => [
                'record' => [
                    'variabile' => '30030',
                    'valore' => '1030,15',
                    'data_e_ora' => '01/01/2017 00:00:00',                
                    'tipo_dato' => '1'
                ],
                'expected' => '1.030,150'
            ],
            'decimal standard' => [
                'record' => [
                    'variabile' => '30030',
                    'valore' => '1030,153',
                    'data_e_ora' => '01/01/2017 00:00:00',                
                    'tipo_dato' => '1'
                ],
                'expected' => '1.030,153'
            ]            
        ];
        
        return $data;
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::extractValore
     * @dataProvider extractValoreProvider
     */
    public function testExtractValoreEquals(array $record, string $expected) : void
    {
        $actual = Bot::extractValore($record);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::extractValore
     */
    public function testExtractValoreException() : void
    {
        $record = [
            'variabile' => '30030',
            'val' => '0',
            'data' => '01/01/2017 00:00:00',                
            'tipo_dato' => '1'
        ];
        
        $this->expectException(\Exception::class);
        
        Bot::extractValore($record);
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::run
     */
    public function testRunEquals() : void
    {
        $expected = true;
        $actual = $this->bot->run();
        
        $this->assertEquals($expected, $actual);
    }
}
