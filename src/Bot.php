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
    private $botCommands;
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
            $this->sendReplies(false);
            $this->sendReplies(true);
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
            if (isset($this->token) && isset($this->end) && isset($this->botCommands) && isset($this->chats)) {
                $properties = [
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
                    if ($chatId > 0) {
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
            Error::noticeHandler(new \Exception("Struttura updates id <b>" . $update['update_id'] . "</b> non compatibile"), DEBUG_LEVEL, 'html');
            file_put_contents(LOG_PATH . '/Telegram_' . $update['update_id'] . '.json' , json_encode($update));
            return false;
        }
    }
    
    private function getChatId(array $update) : int
    {
        try {
            if (count($update) > 0) {
                $chatId = 0;
                if (self::checkStruct($update, ['message','chat','id'])) {
                    $chatId = $update['message']['chat']['id'];                                
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
    
    private function getText(array $update) : string
    {
        try {
            if (count($update) > 0) {
                $text = '';
                if (self::checkStruct($update, ['message','text'])) {
                    $text = $update['message']['text'];                                
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
                if (self::checkStruct($update, ['message','entities',0,'type'])) {
                    $type = $update['message']['entities'][0]['type'];                                
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
                    if ($chatId > 0) {
                        $isOk = self::secureSend($message, $chatId, $this->token);
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
    
    private function getCommandReply(string $text) : string
    {
        try {
            if ($text !== '') {
                $command = $this->parseCommand($text);
                if (count($command) > 0) {
                    $tested = Utility::callback($command['tester'], array($command['params']));
                    if ($tested['ok']) {
                        $response = Utility::callback($command['command'], $tested['params']);
                        if ($response['ok']) {
                            $message = Utility::callback($command['messager'], array($response['records']));
                        } else {
                            $message = $response['descrizione errore'];
                        }
                    } else {
                        $message = implode(PHP_EOL, $tested['errors']);
                    }
                } else {
                    $message = 'Comando inesistente.';
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
    
    private function parseCommand(string $text) : array
    {
        try {
            if ($text !== '') {
                $params = explode(' ', $text);
                $botCommand = substr($params[0], 1);
                if (in_array($botCommand, $this->botCommands)) {
                    $commandName = ucfirst($botCommand);
                    $command['tester'] = 'Bot::tester' . $commandName;
                    $command['command'] = 'Bot::command' . $commandName;
                    $command['messager'] = 'Bot::msg' . $commandName;                
                    $command['params'] = [];
                    $iMax = count($params);
                    for ($i = 1; $i < $iMax; $i++) {
                        $command['params'][] = $params[$i];
                    }
                } else {
                    $command = [];
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
    
    public static function testerVolume(array $params) : array
    {
        try {
            $n_params = count($params);
            switch ($n_params) {
                case 0:
                    $testNames = [];
                    $errors[] = 'Parametri non definiti (es. <i>/volume 30030 01/01/2020 02/01/2020</i>).';
                    break;
                case 1:
                    $testNames = [];
                    $errors[] = 'Numero parametri non sufficiente:' . PHP_EOL . 'inserire l\'id della variabile e almeno una data.';
                    break;
                default:
                    $testNames = ['variabile','datefrom','dateto'];
                    break;
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
            if (isset($errors)) {
                $tested = ['ok' => false,'errors' => $errors];
            } else {
                $tested = ['ok' => true,'params' => $testedParams];
            }
            return $tested;
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
                        'param' => $raw
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
                if (preg_match('/^[0-9]{2}[\/][0-9]{2}[\/][0-9]{4}$/', $raw) && Utility::checkDate($raw)) {
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
                if (preg_match('/^[0-9]{2}[\/][0-9]{2}[\/][0-9]{4}$/', $raw) && Utility::checkDate($raw)) {
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
    
    public static function commandVolume(string $variabile, string $datefrom, string $dateto) : array
    {
        try {
            $url = 'http://localhost/scarichi/tojson.php';
            $params = [
                'var' => $variabile,
                'datefrom' => $datefrom,
                'dateto' => $dateto,
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
                $message .= 'é di <b>' . $cumulato . ' mc</b>.';
            } else {
                $message = 'Non ci sono dati per il periodo selezionato.';
            }            
            return $message;
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
}
