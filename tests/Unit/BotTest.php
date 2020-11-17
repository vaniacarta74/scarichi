<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi\tests\Unit;

use PHPUnit\Framework\TestCase;
use vaniacarta74\Scarichi\Bot;

/**
 * Description of CurlTest
 *
 * @author Vania
 */
class BotTest extends TestCase
{
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::sendMessage
     */
    public function sendMessageProvider() : array
    {
        $data = [
            'chat_id' => [
                'message' => 'Test metodo sendMesssage() con chat_id',
                'chatId' => '474912563',
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
                        "text": "Test+metodo+sendMesssage()+con+chat_id"
                    }
                }'
            ],
            'no chat_id' => [
                'message' => 'Test metodo sendMesssage() senza chat_id',
                'chatId' => null,
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
                        "text": "Test+metodo+sendMesssage()+senza+chat_id"
                    }
                }'
            ],
            'chat_id error' => [
                'message' => 'Test metodo sendMesssage() con chat_id errato',
                'chatId' => 'pippo',
                'expected' => '{
                    "ok": false,
                    "error_code": 400,
                    "description": "Bad+Request:+chat+not+found"
                }'
            ],
            'no message' => [
                'message' => '',
                'chatId' => null,
                'expected' => '{
                    "ok": false,
                    "error_code": 400,
                    "description": "Bad+Request:+message+text+is+empty"
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
    public function testSendMessageContainsString(string $message, ?string $chatId, string $notFormatted) : void
    {
        $noSpace = preg_replace('/[\s]/', '', $notFormatted);
        $withSpace = preg_replace('/[+]/', ' ', $noSpace);
        $expecteds = explode('*', $withSpace);
        
        $actual = Bot::sendMessage($message, $chatId);
        
        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);            
        }
    }   
    
    /**
     * @group bot
     * @covers \vaniacarta74\Scarichi\Bot::secureSend
     */
    public function secureSendProvider() : array
    {
        $data = [
            'chat_id' => [
                'message' => 'Test metodo secureSend() con chat_id',
                'chatId' => '474912563',
                'expected' => true
            ],
            'no chat_id' => [
                'message' => 'Test metodo secureSend() senza chat_id',
                'chatId' => null,
                'expected' => true
            ],
            'chat_id error' => [
                'message' => 'Test metodo secureSend() con chat_id inesistente',
                'chatId' => 'pippo',
                'expected' => false
            ],
            'no message' => [
                'message' => '',
                'chatId' => null,
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
    public function testSecureSendEquals(string $message, ?string $chatId, bool $expected) : void
    {
        $actual = Bot::secureSend($message, $chatId);
        
        $this->assertEquals($expected, $actual); 
    }
}
