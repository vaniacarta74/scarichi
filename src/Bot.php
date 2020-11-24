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
class Bot {
    
    private static $url = 'https://api.telegram.org/bot';
    private static $defaultBot = TOKEN;   
    private static $defaultChatId = CHATID;
    private $token;
    private $start;
    private $end;
    private $chats;
    private $updates;
    private $autorized;
    private $commands;
    
    public function __construct(array $bot)
    {
        $this->token = $bot['token'];
        $this->start = $bot['offset'];
        $this->end = $bot['offset'];
        $this->chats = $bot['chats'];
        $this->updates = [];
        $this->autorized = [
            'yes' => [],
            'not' => []
        ];
        $this->commands = [
            'yes' => [],
            'not' => []
        ];
    } 
    
    public function run() : void
    {
        $this->updates = self::secureUpdate($this->start, $this->token);
        $this->updateOffset();
        $this->setAutorized();
        $this->setCommands();
        
        //$this->commands = self::getCommands($this->updates, $this->autorized);
        self::sendRejections($this->updates, $this->commands, $this->token);
        self::sendReplies($this->updates, $this->commands, $this->token);
    }
    
    public static function getUpdates(int $offset, string $token) : string
    {
        try {
            $url = self::$url . $token . '/getUpdates'; 
            $params = [
                'offset' => $offset
            ];            
            return Curl::run($params, $url, true);            
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
            $tokenBot = $token ?? self::$defaultBot;
            $offsetBot = $offset ?? 0;
            for ($i = 1; $i <= $n; $i++) {
                $response = Bot::getUpdates($offsetBot, $tokenBot);                       
                $arrJson = json_decode($response, true);
                $isOk = $arrJson['ok'] ?? false;
                if ($isOk) {
                    break;
                } else {
                    usleep($delay);
                }
            }            
            if ($isOk && count($arrJson['result']) > 1) {
                $updates = self::arrayShift($arrJson['result']);
            } else {
                $updates = [];
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
            $response = Curl::run($params, $url, true);
            
            return $response;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }

    public static function secureSend(string $message, ?string $chatId = null, ?string $token = null) : bool
    {
        try {
            if ($message === '') {
                throw new \Exception('Non è possibile inviare messaggi vuoti');
            }
            $n = 5;
            $delay = 10000;
            $tokenBot = $token ?? self::$defaultBot;
            $chat_id = $chatId ?? self::$defaultChatId;
            for ($i = 1; $i <= $n; $i++) {
                $response = self::sendMessage($message, $chat_id, $tokenBot);
                $arrJson = json_decode($response, true);            
                $isOk = $arrJson['ok'] ?? false;
                if ($isOk) {
                    break;
                } else {
                    usleep($delay);
                }
            }
            return $isOk;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    private function updateOffset() : void
    {
        try {
            $start = $this->start;
            $updates = $this->updates;
            if (count($updates) > 0) {
                $last = end($updates);
                $updateId = $last['update_id'];
            } else {
                $updateId = $start;
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
            $properties = [
                'token' => $this->token,
                'offset' => $this->end,
                'chats' => $this->chats
            ];
            
            return $properties;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    private function setAutorized() : void
    {
        try {
            $updates = $this->updates;
            $chats = $this->chats;
            $autorized['yes'] = [];
            $autorized['not'] = [];
            foreach ($updates as $update) {
                if (in_array($update['message']['chat']['id'], $chats)) {
                    $autorized['yes'][] = $update['update_id'];
                } else {
                    $autorized['not'][] = $update['update_id'];
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
            $updates = $this->updates;
            $autorized = $this->autorized;
            $commands['yes'] = [];
            $commands['not'] = [];
            foreach ($updates as $update) {
                $message = $update['message'];
                if (array_key_exists('entities', $message) && $message['entities'][0]['type'] === 'bot_command') {
                    if (in_array($update['update_id'], $autorized['yes'])) {                        
                        $commands['yes'][] = $update['update_id'];
                    } elseif (in_array($update['update_id'], $autorized['not'])) {
                        $commands['not'][] = $update['update_id'];
                    }
                }
            }
            $this->commands = $commands;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    public static function sendRejections(array $updates, array $commands, ?string $token = null) : void
    {
        try {
            $tokenBot = $token ?? self::$defaultBot;
            if (count($commands['not']) > 0) {
                foreach ($updates as $update) {
                    if (in_array($update['update_id'], $commands['not'])) {
                        $message = 'Non sei autorizzato ad usare questo comando';
                        $chatId = $update['message']['chat']['id'];
                        $isOk = self::secureSend($message, $chatId, $token);
                    } 
                }
            }
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    public static function sendReplies(array $updates, array $commands, ?string $token = null) : void
    {
        try {
            $tokenBot = $token ?? self::$defaultBot;
            if (count($commands['yes']) > 0) {
                foreach ($updates as $update) {
                    if (in_array($update['update_id'], $commands['yes'])) {
                        $text = $update['message']['text'];
                        $message = self::getCommandReply($text);
                        $chatId = $update['message']['chat']['id'];
                        $isOk = self::secureSend($message, $chatId, $token);
                    } 
                }
            }
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    public static function getCommandReply(string $text) : string
    {
        try {
            $command = self::parseCommand($text);
            $functionCommand = $command['command'];
            $functionMessage = $command['message'];
            $params = $command['params'];
            $response = Utility::callback($functionCommand, $params);
            $message = Utility::callback($functionMessage, array($response));
            return $message;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    public static function parseCommand(string $text) : array
    {
        try {
            $params = explode(' ', $text);
            $commandName = ucfirst(substr($params[0], 1));
            $command['command'] = 'Bot::command' . $commandName;
            $iMax = count($params);
            for ($i = 1; $i < $iMax; $i++) {
                $command['params'][] = $params[$i];
            }
            $command['message'] = 'Bot::msg' . $commandName;
            return $command;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    public static function commandVolume(string $variabile, string $datefrom, ?string $dateto = null) : array
    {
        try {
            $dateTime = new \DateTime('NOW', new \DateTimeZone('Europe/Rome'));
            $date = $dateTime->format('d/m/Y');
            $url = 'http://localhost/scarichi/tojson.php';
            $params = [
                'var' => $variabile,
                'datefrom' => $datefrom,
                'dateto' => $dateto ?? $date,
                'field' => 'cumulato'
            ];
            $json = Curl::run($params, $url, true);            
            $arrJson = json_decode($json, true);
            
            return $arrJson;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    public static function msgVolume(array $response) : string
    {
        try {
            if (count($response) > 0) {
                $volume = end($response);
                $variabile = $volume['variabile'];
                $iniziale = $response[0]['data_e_ora'];
                $arrIniziale = explode(' ', $iniziale);
                $dataIniziale = $arrIniziale[0];
                $oraIniziale = $arrIniziale[1];
                $finale = $volume['data_e_ora'];
                $arrFinale = explode(' ', $finale);
                $dataFinale = $arrFinale[0];
                $oraFinale = $arrFinale[1];
                $valore = $volume['valore'];
                $arrValore = explode(',', $valore);                
                $cumulato = number_format($arrValore[0], 0, ',', '.') . ',' . $arrValore[1];
                $message = 'Il volume movimentato da <b>' . $variabile . '</b>' . PHP_EOL;
                $message .= 'dal <i>' . $dataIniziale . '</i> alle <i>' . $oraIniziale . '</i>' . PHP_EOL;
                $message .= 'al <i>' . $dataFinale . '</i> alle <i>' . $oraFinale . '</i>' . PHP_EOL;
                $message .= 'é di <b>' . $cumulato . ' mc</b>';
            } else {
                $message = 'error';
            }            
            return $message;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
}
