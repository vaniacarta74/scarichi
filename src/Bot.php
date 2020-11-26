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
        try {
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
            if (array_key_exists('chats', $bot) && is_array($bot['chats'])) {
                $this->chats = $bot['chats'];
            } else {
                throw new \Exception('Chats non presente o errato');
            }            
            $this->updates = [];
            $this->autorized = ['yes' => [],'not' => [],'undef' => []];
            $this->commands = ['yes' => [],'not' => [],'undef' => []];
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    } 
    
    public function run() : void
    {
        try {
            $this->updates = self::secureUpdate($this->start, $this->token);
            $this->updateOffset();
            $this->setAutorized();
            $this->setCommands();

            self::sendRejections($this->updates, $this->commands, $this->token);
            self::sendReplies($this->updates, $this->commands, $this->token);
            
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    public static function getUpdates(int $offset, string $token) : string
    {
        try {
            $url = self::$url . $token . '/getUpdates'; 
            $params = [
                'offset' => $offset
            ];
            $response = Curl::run($params, $url, true);
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
            if (count($this->updates) > 0) {
                $last = end($this->updates);
                if (array_key_exists('update_id', $last)) {
                    $updateId = $last['update_id'];
                } else {
                    throw new \Exception("Parametro update id mancante");
                }                
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
            if (isset($this->token) && isset($this->end) && isset($this->chats)) {
                $properties = [
                    'token' => $this->token,
                    'offset' => $this->end,
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
                    if (!self::checkStruct($update, ['message','chat','id'])) {
                        $autorized['undef'][] = $update['update_id'];
                        continue;
                    }
                    if (in_array($update['message']['chat']['id'], $this->chats)) {
                        $autorized['yes'][] = $update['update_id'];
                    } else {
                        $autorized['not'][] = $update['update_id'];
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
                    if (!self::checkStruct($update, ['message','entities',0,'type'])) {
                        $commands['undef'][] = $update['update_id'];
                        continue;
                    }
                    if ($update['message']['entities'][0]['type'] === 'bot_command') {
                        if (in_array($update['update_id'], $this->autorized['yes'])) {                        
                            $commands['yes'][] = $update['update_id'];
                        } elseif (in_array($update['update_id'], $this->autorized['not'])) {
                            $commands['not'][] = $update['update_id'];
                        } else {
                            $commands['undef'][] = $update['update_id'];
                        }
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
                if (isset($arrValore[1]) && intval($arrValore[0]) > 0) {
                    $cumulato = number_format($arrValore[0], 0, ',', '.') . ',' . $arrValore[1];
                } elseif (intval($arrValore[0]) > 0) {
                    $cumulato = number_format($arrValore[0], 0, ',', '.');
                } else {
                    $cumulato = '0';
                }
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
    
    public static function checkStruct(array $update, array $keys) : bool
    {
        try {
            Utility::getSubArray($update, $keys);
            return true;
        } catch (\Throwable $e) {
            Error::noticeHandler(new \Exception("Struttura updates id <b>" . $update['update_id'] . "</b> non compatibile"), DEBUG_LEVEL, 'html');
            file_put_contents(LOG_PATH . '/Telegram_' . $update['update_id'] . '.json' , json_encode($update));
            return false;
        }
    }
}
