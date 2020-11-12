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
    
    public static function sendMessage(string $message) : string
    {
        try {
            $url = self::$urlBot . '/sendMessage';            
            $params = [
                'chat_id' => self::$chatId, 
                'text' => $message, 
                'parse_mode' => 'HTML'
            ];            
            $response = Utility::cUrl($params, $url);
            
            return $response;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }

    public static function secureSend(string $message) : bool
    {
        try {
            $response = self::sendMessage($message);
            $arrJson = json_decode($response, true);            
            $isOk = $arrJson['ok'] === true ? true : false;
            
            return $isOk;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
}
