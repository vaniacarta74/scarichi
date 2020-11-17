<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi;

/**
 * Description of Bot
 *
 * @author Vania
 */
class Bot {
    
    private static $urlBot = 'https://api.telegram.org/bot' . TOKEN;
    private static $chatId = CHATID;
    
    public static function sendMessage(string $message, ?string $chatId = null) : string
    {
        try {
            $url = self::$urlBot . '/sendMessage';            
            $params = [
                'chat_id' => $chatId ?? self::$chatId, 
                'text' => $message, 
                'parse_mode' => 'HTML'
            ];            
            $response = Curl::run($params, $url);
            
            return $response;
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        //@codeCoverageIgnoreEnd
    }

    public static function secureSend(string $message, ?string $chatId = null) : bool
    {
        try {
            $chat_id = $chatId ?? self::$chatId;
            $response = self::sendMessage($message, $chat_id);
            $arrJson = json_decode($response, true);            
            $isOk = $arrJson['ok'] ?? false;
            
            return $isOk;
        //@codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        //@codeCoverageIgnoreEnd
    }
}
