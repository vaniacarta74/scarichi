<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi;

use vaniacarta74\Scarichi\Error;
use vaniacarta74\Scarichi\Utility;

/**
 * Description of Bot
 *
 * @author Vania
 */
class Bot
{
    
    private static $url = 'https://api.telegram.org/bot';
    private static $defaultBot = TOKEN;   
    private static $defaultChatId = CHATID;
    private static $tagLimit = TAGLIMIT;
    private static $msgLimit = MSGLIMIT;
    private static $admittedTags = ADMITTEDTAGS;
    private static $allowed = ['message','channel_post'];
    private $botName;
    private $userName;
    private $token;
    private $start;
    private $end;
    private $botCommands;
    private $methods;
    private $chats;
    private $updates;
    private $autorized;
    private $commands;
    
    public function __construct(array $bot)
    {
        try {
            if (array_key_exists('botName', $bot) && is_string($bot['botName'])) {
                $this->botName = $bot['botName'];
            } else {
                throw new \Exception('BotName non presente o errato');
            }
            if (array_key_exists('userName', $bot) && is_string($bot['userName'])) {
                $this->userName = $bot['userName'];
            } else {
                throw new \Exception('UserName non presente o errato');
            }
            if (array_key_exists('token', $bot) && is_string($bot['token'])) {
                $this->token = $bot['token'];
            } else {
                throw new \Exception('Token non presente o errato');
            }
            if (array_key_exists('offset', $bot) && is_int($bot['offset'])) {
                $this->start = $bot['offset'];
                $this->end = $bot['offset'];
            } else {
                throw new \Exception('Offset non presente o errato');
            }
            if (array_key_exists('commands', $bot) && is_array($bot['commands'])) {
                $this->botCommands = $bot['commands'];
            } else {
                throw new \Exception('Commands non presente o errato');
            }
            if (array_key_exists('chats', $bot) && is_array($bot['chats'])) {
                $this->chats = $bot['chats'];
            } else {
                throw new \Exception('Chats non presente o errato');
            }            
            $this->updates = [];
            $this->autorized = ['yes' => [],'not' => [],'undef' => []];
            $this->commands = ['yes' => [],'not' => [],'undef' => []];
            $this->methods = ['selector' => '','tester' => '','runner' => '','messager' => ''];
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    } 
    
    public function run() : bool
    {
        try {
            $this->updates = self::secureUpdate($this->start, $this->token);
            $this->updateOffset();
            $this->setAutorized();
            $this->setCommands();
            $isNotAuthOk = $this->sendReplies(false);
            $isAuthOk = $this->sendReplies(true);
            return ($isNotAuthOk && $isAuthOk);        
        // @codeCoverageIgnoreStart    
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        // @codeCoverageIgnoreEnd
    }
    
    public static function getUpdates(int $offset, string $token) : string
    {
        try {
            $url = self::$url . $token . '/getUpdates'; 
            $params = [
                'offset' => $offset,
                //'allowed_updates' => self::$allowed
            ];
            $response = Curl::run($url, $params, true);
            if (!$response) {
                throw new \Exception('Server Telegram non raggiungibile');
            }
            return $response;            
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    public static function secureUpdate(?int $offset = null, ?string $token = null) : array
    {
        try {
            $n = 5;
            $delay = 10000;
            $updates = [];
            $tokenBot = $token ?? self::$defaultBot;
            $offsetBot = $offset ?? 0;
            for ($i = 1; $i <= $n; $i++) {
                try {
                    $response = Bot::getUpdates($offsetBot, $tokenBot);
                    $arrJson = json_decode($response, true);
                    if (array_key_exists('result', $arrJson)) {
                        if(count($arrJson['result']) > 1) {            
                            $updates = self::arrayShift($arrJson['result']);
                        }
                    } else {
                        throw new \Error('Risposta server Telegram inattesa');
                    }
                    break;
                } catch (\Exception $e) {
                    usleep($delay);
                    continue;
                } catch (\Error $e) {
                    throw $e;
                }
            }            
            return $updates;            
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    public static function arrayShift(array $results) : array
    {
        try {
            if (count(array_filter(array_keys($results), 'is_string')) > 0) {
                throw new \Exception('Array associativo');
            }
            $updates = [];
            foreach ($results as $key => $result) {
                if ($key > 0) {
                    $updates[] = $result;
                }
            }
            return $updates;            
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    public static function sendMessage(string $message, string $chatId, string $token) : string
    {
        try {
            if ($message === '') {
                throw new \Exception('Non è possibile inviare messaggi vuoti');
            }
            $url = self::$url . $token . '/sendMessage';            
            $params = [
                'chat_id' => $chatId, 
                'text' => $message, 
                'parse_mode' => 'HTML'
            ];            
            $response = Curl::run($url, $params, true);
            
            return $response;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    public static function replyMessage(string $message, string $messageId, string $chatId, string $token) : string
    {
        try {
            if ($message === '') {
                throw new \Exception('Non è possibile inviare messaggi vuoti');
            }
            $url = self::$url . $token . '/sendMessage';            
            $params = [
                'chat_id' => $chatId,
                'reply_to_message_id' => $messageId,
                'text' => $message, 
                'parse_mode' => 'HTML'
            ];            
            $response = Curl::run($url, $params, true);
            
            return $response;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    public static function checkAndSend(string $message, ?int $tagLimit = null, ?int $msgLimit = null, ?string $nut = null, ?string $chatId = null, ?string $messageId = null, ?string $token = null) : bool
    {
        try {            
            $nutBot = $nut ?? ' ';
            $nMaxTag = $tagLimit ?? self::$tagLimit;
            $limit = $msgLimit ?? self::$msgLimit;
            $tokenBot = $token ?? self::$defaultBot;
            $chat_id = $chatId ?? self::$defaultChatId;
            if (strlen($message) === 0) {
                $responses[] = false;
            } elseif (strlen($message) > $limit || Utility::countTags($message) > $nMaxTag) {
                $responses = self::breakAndSend($message, $nMaxTag, $limit, $nutBot, $chat_id, $messageId, $tokenBot);
            } else {
                $responses[] = self::secureSend($message, $chatId, $messageId, $tokenBot);
            }
            $isOk = self::checkIsOk($responses);
            return $isOk;
        // @codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        // @codeCoverageIgnoreEnd
    }
    
    public static function breakAndSend(string $message, int $tagLimit, int $limit, string $nut, string $chatId, ?string $messageId, ?string $token) : array
    {
        try { 
            if ($message === '') {
                throw new \Exception('Non è possibile spezzare messaggi vuoti');
            }
            $msgParts = explode($nut, $message);
            $msgToSend = '';
            $responses = [];
            $i = 0;
            foreach ($msgParts as $part) {
                if (strlen($msgToSend) === 0 && $i === 0) {
                    $msgToSend = $part;
                } elseif ((strlen($msgToSend) === 0 && $i > 0) || strlen($msgToSend) > $limit || Utility::countTags($msgToSend) > $tagLimit) {
                    $responses[] = false;
                    $msgToSend = $part;
                } elseif (strlen($msgToSend) === $limit || Utility::countTags($msgToSend) === $tagLimit || (strlen($msgToSend) < $limit && strlen($msgToSend . $nut . $part) > $limit) || (Utility::countTags($msgToSend) < $tagLimit && Utility::countTags($msgToSend . $nut . $part) > $tagLimit)) {
                    $responses[] = self::secureSend($msgToSend, $chatId, $messageId, $token);
                    $msgToSend = $part;
                } else {
                    $msgToSend .= $nut . $part;
                }
                $i++;
            }
            if (strlen($msgToSend) > 0 && strlen($msgToSend) <= $limit && Utility::countTags($msgToSend) <= $tagLimit) {
                $responses[] = self::secureSend($msgToSend, $chatId, $messageId, $token);
            } elseif (strlen($msgToSend) > $limit || Utility::countTags($msgToSend) > $tagLimit) {
                $responses[] = false;
            } else {
                $responses[] = true;
            }
            return $responses;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    public static function checkIsOk(array $responses) : bool
    {
        try {
            $isOk = true;
            if (count($responses) > 0) {
                foreach ($responses as $response) {
                    if (!$response) {
                        $isOk = false;
                        break;
                    }
                }
            } else {
                $isOk = false;
            }
            return $isOk;
        // @codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        // @codeCoverageIgnoreEnd
    }
    
    public static function secureSend(string $rawMessage, ?string $chatId = null, ?string $messageId = null, ?string $token = null, ?array $tags = null) : bool
    {
        try {
            if ($rawMessage !== '') {                
                $n = 5;
                $delay = 500000;
                $tokenBot = $token ?? self::$defaultBot;
                $chat_id = $chatId ?? self::$defaultChatId;
                $admittedTags = $tags ?? self::$admittedTags;
                $message = Utility::purgeHtml($rawMessage, $admittedTags);
                for ($i = 1; $i <= $n; $i++) {
                    if (isset($messageId) && $i < 3) {
                        $response = self::replyMessage($message, $messageId, $chat_id, $tokenBot);
                    } else {
                        $response = self::sendMessage($message, $chat_id, $tokenBot);
                    }
                    $arrJson = json_decode($response, true);            
                    $isOk = $arrJson['ok'] ?? false;
                    if ($isOk) {
                        break;
                    } else {
                        usleep($delay);
                    }
                }
            } else {
                $isOk = false;
            }
            return $isOk;
        // @codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        // @codeCoverageIgnoreStart
    }
    
    private function updateOffset() : void
    {
        try {
            if (count($this->updates) > 0) {
                $last = end($this->updates);
                if (array_key_exists('update_id', $last)) {
                    $updateId = $last['update_id'];
                } else {
                    throw new \Exception("Parametro update id mancante");
                }
                self::secureUpdate($updateId, $this->token);
            } else {
                $updateId = $this->start;
            }            
            $this->end = $updateId;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    public function getProperties() : array
    {
        try {
            if (isset($this->botName) && isset($this->userName) && isset($this->token) && isset($this->end) && isset($this->botCommands) && isset($this->chats)) {
                $properties = [
                    'botName' => $this->botName,
                    'userName' => $this->userName,
                    'token' => $this->token,
                    'offset' => $this->end,
                    'commands' => $this->botCommands,
                    'chats' => $this->chats
                ];
            } else {
                throw new \Exception("Parametri non impostati");
            }            
            return $properties;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    private function setAutorized() : void
    {
        try {
            $autorized = ['yes' => [],'not' => [],'undef' => []];            
            foreach ($this->updates as $update) {
                if (array_key_exists('update_id', $update)) {                    
                    $chatId = $this->getChatId($update);
                    if ($chatId !== 0) {
                        if (in_array($chatId, $this->chats)) {
                            $autorized['yes'][] = $update['update_id'];
                        } else {
                            $autorized['not'][] = $update['update_id'];
                        }
                    } else {
                        $autorized['undef'][] = $update['update_id'];
                    }
                } else {
                    throw new \Exception("Update id non definito");
                }
            }
            $this->autorized = $autorized;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    private function setCommands() : void
    {
        try {
            $commands = ['yes' => [], 'not' => [],'undef' => []];
            foreach ($this->updates as $update) {
                if (array_key_exists('update_id', $update)) {                    
                    $entType = $this->getEntityType($update);
                    if ($entType !== '' && $entType === 'bot_command') {
                        if (in_array($update['update_id'], $this->autorized['yes'])) {                        
                            $commands['yes'][] = $update['update_id'];
                        } elseif (in_array($update['update_id'], $this->autorized['not'])) {
                            $commands['not'][] = $update['update_id'];
                        } else {
                            $commands['undef'][] = $update['update_id'];
                        }
                    } else {
                        $commands['undef'][] = $update['update_id'];
                    }
                } else {
                    throw new \Exception("Update id non definito");
                }
            }
            $this->commands = $commands;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    private function sendReplies(bool $isAutorized) : bool
    {
        try {
            $isAllOk = true;
            $autorized = $isAutorized ? 'yes' : 'not';
            if (count($this->commands[$autorized]) > 0) {
                foreach ($this->updates as $update) {
                    if (array_key_exists('update_id', $update)) {
                        $updateId = $update['update_id'];                        
                        if (in_array($updateId, $this->commands[$autorized])) {                            
                            $message = $this->prepareReply($update, $isAutorized);
                            $isOk = $this->sendReply($update, $message);
                            if (!$isOk) {
                                Error::noticeHandler(new \Exception('Risposta update id <b>' . $updateId . '</b> non inviata'), DEBUG_LEVEL, 'html');
                                $isAllOk = false;
                            }
                        } 
                    } else {
                        throw new \Exception("Update id non definito");
                    }
                }
            }
            return $isAllOk;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    public static function checkStruct(array $update, array $keys) : bool
    {
        try {
            Utility::getSubArray($update, $keys);
            return true;
        } catch (\Throwable $e) {
            self::reportNotAllowed($update, $keys);
            return false;
        }
    }
    
    public static function reportNotAllowed(array $update, array $keys) : void
    {
        try {
            if (!array_key_exists('update_id', $update)) {
                throw new \Exception("Update id non definito");
            }
            $updateId = $update['update_id'];
            $strKeys = implode('->', $keys);
            $allowed = array_flip(array_merge(['update_id'], self::$allowed));
            $notAllowed = array_diff_key($update, $allowed);
            if (count($notAllowed) > 0) {            
                Error::noticeHandler(new \Exception("Struttura updates id <b>" . $updateId . "</b> non compatibile. Struttura " . $strKeys . " non presente."), DEBUG_LEVEL, 'html');
                file_put_contents(LOG_PATH . '/Telegram_' . $updateId . '.json' , json_encode($update));
            }
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    private function getChatId(array $update) : int
    {
        try {
            if (count($update) > 0) {
                $chatId = 0;
                foreach (self::$allowed as $allowed) {
                    if (self::checkStruct($update, [$allowed,'chat','id'])) {
                        $chatId = $update[$allowed]['chat']['id'];                                
                    }
                }
            } else {
                throw new \Exception("Array update vuoto");
            }
            return $chatId;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    private function getMessageId(array $update) : int
    {
        try {
            if (count($update) > 0) {
                $messageId = 0;
                foreach (self::$allowed as $allowed) {
                    if (self::checkStruct($update, [$allowed,'message_id'])) {
                        $messageId = $update[$allowed]['message_id'];
                        break;
                    }
                }
            } else {
                throw new \Exception("Array update vuoto");
            }
            return $messageId;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    private function getText(array $update) : string
    {
        try {
            if (count($update) > 0) {
                $text = '';
                foreach (self::$allowed as $allowed) {
                    if (self::checkStruct($update, [$allowed,'text'])) {
                        $text = $update[$allowed]['text'];
                        break;
                    }
                }
            } else {
                throw new \Exception("Array update vuoto");
            }
            return $text;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    private function getEntityType(array $update) : string
    {
        try { 
            if (count($update) > 0) {
                $type = '';
                foreach (self::$allowed as $allowed) {
                    if (self::checkStruct($update, [$allowed,'entities',0,'type'])) {
                        $type = $update[$allowed]['entities'][0]['type'];
                        break;
                    }
                }
            } else {
                throw new \Exception("Array update vuoto");
            }
            return $type;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    private function prepareReply(array $update, bool $isAutorized) : string
    {
        try {
            if (count($update) > 0) {
                $message = '';
                if ($isAutorized) {
                    $text = $this->getText($update);
                    if ($text !== '') {
                        $message = $this->getCommandReply($text);
                    }
                } else {
                    $message = 'Non sei autorizzato ad usare i comandi di BotScarichi.';
                }
            } else {
                throw new \Exception("Array update vuoto");
            }
            return $message;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    private function sendReply(array $update, string $message) : bool
    {
        try {
            if (count($update) > 0) {
                $isOk = false;
                if ($message !== '') {
                    $chatId = $this->getChatId($update);
                    $messageId = $this->getMessageId($update);
                    if ($chatId !== 0 && $messageId !== 0) {
                        $isOk = self::checkAndSend($message, null, null, null, $chatId, $messageId, $this->token);
                    }
                }
            } else {
                throw new \Exception("Array update vuoto");
            }
            return $isOk;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    private function parseCommand(string $text) : array
    {
        try {
            if ($text !== '') {
                $params = explode(' ', $text);
                $botComplete = substr($params[0], 1);
                $botCommand = str_replace('@' . $this->userName, '', $botComplete);
                if (in_array($botCommand, $this->botCommands)) {
                    $this->setMethods($botCommand);
                    $command = ['ok' => true,'params' => []];
                    $iMax = count($params);
                    for ($i = 1; $i < $iMax; $i++) {
                        $command['params'][] = $params[$i];
                    }
                } else {
                    $command = ['ok' => false,'error' => 'Comando inesistente.'];
                }
            } else {
                throw new \Exception("Il testo non contiene un comando");
            }
            return $command;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    private function setMethods(string $botCommand) : void
    {
        try {
            if (in_array($botCommand, $this->botCommands)) {
                $methods = array_keys($this->methods);
                foreach ($methods as $method) {
                    $this->methods[$method] = 'Bot::' . $method . ucfirst($botCommand);
                }
            } else {
                throw new \Exception("Comando bot non trovato");
            }
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    private function getCommandReply(string $text) : string
    {
        try {
            if ($text !== '') {
                $command = $this->parseCommand($text);
                if ($command['ok']) {
                    $tests = Utility::callback($this->methods['selector'], array($command['params']));
                    $tested = Utility::callback($this->methods['tester'], array($tests, $command['params']));
                    if ($tested['ok']) {
                        $response = Utility::callback($this->methods['runner'], $tested['params']);
                        if ($response['ok']) {
                            $message = Utility::callback($this->methods['messager'], array($response['records']));
                        } else {
                            $message = $response['descrizione errore'];
                        }
                    } else {
                        $message = implode(PHP_EOL, $tested['errors']);
                    }
                } else {
                    $message = $command['error'];
                }
            } else {
                throw new \Exception("Il testo non contiene un comando");
            }
            return $message;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    public static function selectorVolume(array $params) : array
    {
        try {
            $n_params = count($params);
            switch ($n_params) {
                case 0:
                    $tests = [
                        'testNames' => [],
                        'errors' => [
                            'Parametri non definiti (es. <i>/volume 30030 01/01/2020 02/01/2020</i>).'
                        ]
                    ];
                    break;
                case 1:
                    $tests = [
                        'testNames' => [],
                        'errors' => [
                            'Numero parametri non sufficiente:' . PHP_EOL . 'inserire l\'id della variabile e almeno una data.'
                        ]
                    ];
                    break;
                default:
                    $tests = [
                        'testNames' => [
                            'variabile',
                            'datefrom',
                            'dateto'
                        ],
                        'errors' => []
                    ];
                    break;
            }
            return $tests;
        // @codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        // @codeCoverageIgnoreEnd
    }
    
    public static function testerVolume(array $tests, array $params) : array
    {
        try {            
            $testNames = $tests['testNames'];
            $errors = $tests['errors'];
            if (count($testNames) === 0 && count($errors) === 0)  {
                throw new \Exception("Problemi con la selezione dei test");
            }
            foreach ($testNames as $testName) {
                $functionName = 'Bot::test' . ucfirst($testName);
                $resTest = Utility::callback($functionName, array($params));
                if ($resTest['ok']) {
                    $testedParams[] = $resTest['param'];
                } else {
                    $errors[] = $resTest['error'];
                }
            }
            return (count($errors) > 0) ? ['ok' => false,'errors' => $errors] : ['ok' => true,'params' => $testedParams];
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    public static function testVariabile(array $params) : array
    {
        try {
            if (count($params) > 0) {
                $raw = intval($params[0]);
                if ($raw >= 30000 && $raw <= 39999) {
                    $resTest = [
                        'ok' => true,
                        'param' => strval($raw)
                    ];
                } else {
                    $resTest = [
                        'ok' => false,
                        'error' => 'Il primo parametro deve essere l\'id della variabile:' . PHP_EOL . 'inserire un numero compreso fra 30000 e 39999.'
                    ];
                }
            } else {
                throw new \Exception("Numero parametri insufficiente. Impossibile testare la variabile");
            }
            return $resTest;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    public static function testDatefrom(array $params) : array
    {
        try {
            if (count($params) > 1) {
                $raw = strval($params[1]);
                if (preg_match('/^[0-9]{2}[\/][0-9]{2}[\/][0-9]{4}$/', $raw) && Utility::checkDate($raw, true)) {
                    $resTest = [
                        'ok' => true,
                        'param' => $raw
                    ];
                } else {
                    $resTest = [
                        'ok' => false,
                        'error' => 'Il secondo parametro deve essere una data valida:' . PHP_EOL . 'utilizzare il formato dd/mm/yyyy.'
                    ];
                }
            } else {
                throw new \Exception("Numero parametri insufficiente. Impossibile testare la data iniziale");
            }
            return $resTest;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    public static function testDateto(array $params) : array
    {
        try {
            if (count($params) >= 2) {
                if (isset($params[2])) {
                    $raw = strval($params[2]);
                } else {
                    $dateTime = new \DateTime('NOW', new \DateTimeZone('Europe/Rome'));
                    $date = $dateTime->format('d/m/Y');
                    $raw = $date;
                }                
                if (preg_match('/^[0-9]{2}[\/][0-9]{2}[\/][0-9]{4}$/', $raw) && Utility::checkDate($raw, true)) {
                    $resTest = [
                        'ok' => true,
                        'param' => $raw
                    ];
                } else {
                    $resTest = [
                        'ok' => false,
                        'error' => 'Il terzo parametro deve essere una data valida:' . PHP_EOL . 'utilizzare il formato dd/mm/yyyy.'
                    ];
                }
            } else {
                throw new \Exception("Numero parametri insufficiente. Impossibile testare la data finale");
            }
            return $resTest;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    public static function runnerVolume(string $variabile, string $datefrom, string $dateto) : array
    {
        try {
            $url = 'http://' . LOCALHOST . '/scarichi/tojson.php';
            $params = [
                'var' => $variabile,
                'datefrom' => $datefrom,
                'dateto' => $dateto,
                'field' => 'cumulato'
            ];
            $json = Curl::run($url, $params, true);            
            $arrJson = json_decode($json, true);
            
            return $arrJson;
        // @codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        // @codeCoverageIgnoreEnd
    }
    
    public static function messagerVolume(array $response) : string
    {
        try {
            if (count($response) > 0) {
                $startRecord = reset($response);
                $endRecord = end($response);
                
                $variabile = Bot::extractVariabile($startRecord);
                $iniziale = Bot::extractDataOra($startRecord);
                $finale = Bot::extractDataOra($endRecord);
                $valore = Bot::extractValore($endRecord);
                
                $message = 'Il volume movimentato da <b>' . $variabile . '</b>' . PHP_EOL;
                $message .= 'dal <i>' . $iniziale['data'] . '</i> alle <i>' . $iniziale['ora'] . '</i>' . PHP_EOL;
                $message .= 'al <i>' . $finale['data'] . '</i> alle <i>' . $finale['ora'] . '</i>' . PHP_EOL;
                $message .= 'é di <b>' . $valore . ' mc</b>.';
            } else {
                $message = 'Non ci sono dati per il periodo selezionato.';
            }            
            return $message;
        // @codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        // @codeCoverageIgnoreEnd
    }
    
    public static function extractVariabile(array $record) : string
    {
        try {
            if (array_key_exists('variabile', $record)) {
                $variabile = $record['variabile'];
            } else {
                throw new \Exception("Campo variabile assente");
            }
            return $variabile;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    public static function extractDataOra(array $record) : array
    {
        try {
            if (array_key_exists('data_e_ora', $record)) {
                $arrDataOra = explode(' ', $record['data_e_ora']);                
                $dataOra = [
                    'data' => $arrDataOra[0],
                    'ora' => $arrDataOra[1],
                ];
            } else {
                throw new \Exception("Campo data_e_ora assente");
            }
            return $dataOra;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    public static function extractValore(array $record) : string
    {
        try {
            if (array_key_exists('valore', $record)) {
                $valore = $record['valore'];
                $arrValore = explode(',', $valore);                
                $intera = intval($arrValore[0]);
                $intForm = number_format($arrValore[0], 0, ',', '.');                
                if (count($arrValore) > 1) {                    
                    $decimale = intval($arrValore[1]);
                    if ($decimale >= 100) {
                        $cumulato = $intForm . ',' . $decimale;
                    } elseif ($decimale >= 10) {
                        $cumulato = $intForm . ',' . $decimale * 10;
                    } else {
                        $cumulato = $intForm . ',' . $decimale * 100;
                    }
                } else {
                    if ($intera > 0) {
                        $cumulato = $intForm;
                    } else {
                        $cumulato = '0';
                    }
                }                
            } else {
                throw new \Exception("Campo valore assente");
            }
            return $cumulato;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
}
