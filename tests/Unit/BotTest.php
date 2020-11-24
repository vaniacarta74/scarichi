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
        $json = Utility::getJsonArray(__DIR__ . '/../../../telegram.json');
        $bot = $json['bots'][0];
        
        $this->bot = new Bot($bot);
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
            ],
            'token error' => [
                'offset' => -20,
                'token' => 'TOKEN',
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
     * @covers \vaniacarta74\Scarichi\Bot::setAutorized
     */
    public function testSetAutorizedEquals() : void
    {
        $updates = [
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
                    'chat' => [
                        'id' => 6666
                    ]
                ]
            ]
            
        ];
        $chats = [
            7777,
            8888
        ];
        $expected = [
            'yes' => [
                999,
                1001
            ],
            'not' => [
                1000,
                1002
            ]
        ];
        
        Reflections::setProperty($this->bot, 'updates', $updates);
        Reflections::setProperty($this->bot, 'chats', $chats);
        Reflections::invokeMethod($this->bot, 'setAutorized');
        
        $actual = Reflections::getProperty($this->bot, 'autorized');
        
        $this->assertEquals($expected, $actual);         
    }
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::setCommands
     */
    public function testSetCommandsEquals() : void
    {
        $updates = [
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
            
        ];        
        $autorized = [
            'yes' => [
                999,
                1001
            ],
            'not' => [
                1000,
                1002
            ]
        ];        
        $expected = [
            'yes' => [
                999
            ],
            'not' => [
                1000
            ]
        ];
        
        Reflections::setProperty($this->bot, 'updates', $updates);
        Reflections::setProperty($this->bot, 'autorized', $autorized);
        Reflections::invokeMethod($this->bot, 'setCommands');
        
        $actual = Reflections::getProperty($this->bot, 'commands');
        
        $this->assertEquals($expected, $actual);         
    }
}
