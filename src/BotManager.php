<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi;

use vaniacarta74\Scarichi\Bot;
use vaniacarta74\Scarichi\Error;
use vaniacarta74\Scarichi\Utility;

/**
 * Description of BotManager
 *
 * @author Vania
 */
class BotManager
{
    
    /**
     * Stampa il nome della funzione che ha generato l'errore.
     *
     * Il metodo printErrorInfo() viene chiamato nel caso una funzione abbia
     * generato un errore. Restituisce una stringa con il nome della funzione e
     * la data e l'ora.
     *
     * @param string $functionName Nome della funzione dove si è scatenato l'errore
     * @param int $debug_level Livello di debug
     * @return void
     */
    public static function exec(array $arrBots) : array
    {
        try {
            if (!array_key_exists('bots', $arrBots)) {
                throw new \Exception('Formato file di configurazione errato');
            }
            $bots = $arrBots['bots'];
            foreach ($bots as $bot) {
                $objBot = new Bot($bot);
                $isOk = $objBot->run();
                $properties = $objBot->getProperties();
                $outRes[] = $properties;
                $botRes[] = [
                    'bot' => $properties['botName'],
                    'ok' => $isOk                    
                ];                
            }
            return ['bots' => $outRes, 'results' => $botRes];            
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * Stampa il nome della funzione che ha generato l'errore.
     *
     * Il metodo printErrorInfo() viene chiamato nel caso una funzione abbia
     * generato un errore. Restituisce una stringa con il nome della funzione e
     * la data e l'ora.
     *
     * @param string $functionName Nome della funzione dove si è scatenato l'errore
     * @param int $debug_level Livello di debug
     * @return void
     */
    public static function print(array $result, bool $isOk) : string
    {
        try {
            if (!array_key_exists('results', $result)) {
                throw new \Exception('Impossibile stampare i risultati dell\'elaborazione: formato inatteso');
            }
            $botPrint = '';
            $botResults = $result['results'];
            foreach ($botResults as $botResult) {
                if (!array_key_exists('ok', $botResult) || !array_key_exists('bot', $botResult)) {
                    throw new \Exception('Impossibile stampare i risultati dell\'elaborazione: formato dati bot inatteso');
                }
                $okPrint = $botResult['ok'] ? 'true' : 'false';
                $botPrint .= '<b>' . $botResult['bot'] . '</b> is ok: <b>' . $okPrint . '</b>.<br/>' . PHP_EOL;
            }
            $putPrint = $isOk ? 'avvenuta con successo' : 'fallita';
            $putPrint = 'Registrazione offset ' . $putPrint . '.<br/>' . PHP_EOL;
            $benchmarkPrint = 'Tempo di elaborazione: <b>' . Utility::benchmark(START) . '</b>.<br/>' . PHP_EOL; 
            $toPrint = $botPrint . $putPrint . $benchmarkPrint;
            return $toPrint;            
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
    
    /**
     * Stampa il nome della funzione che ha generato l'errore.
     *
     * Il metodo printErrorInfo() viene chiamato nel caso una funzione abbia
     * generato un errore. Restituisce una stringa con il nome della funzione e
     * la data e l'ora.
     *
     * @param string $functionName Nome della funzione dove si è scatenato l'errore
     * @param int $debug_level Livello di debug
     * @return void
     */
    public static function load(string $botPath) : array
    {
        try {
            return Utility::getJsonArray($botPath);
        
        // @codeCoverageIgnoreStart
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
        // @codeCoverageIgnoreEnd
    }
    
    /**
     * Stampa il nome della funzione che ha generato l'errore.
     *
     * Il metodo printErrorInfo() viene chiamato nel caso una funzione abbia
     * generato un errore. Restituisce una stringa con il nome della funzione e
     * la data e l'ora.
     *
     * @param string $functionName Nome della funzione dove si è scatenato l'errore
     * @param int $debug_level Livello di debug
     * @return void
     */
    public static function update(array $result, string $botPath) : bool
    {
        try {
            if (!array_key_exists('bots', $result)) {
                throw new \Exception('Impossibile aggiornare l\'offset: formato dati inatteso');
            }
            $arrJson = ['bots' => $result['bots']];
            $json = json_encode($arrJson);
            $put = file_put_contents($botPath, $json);    
            $isOk = $put ? true : false;
            
            return $isOk;            
        } catch (\Throwable $e) {
            Error::printErrorInfo(__FUNCTION__, DEBUG_LEVEL);
            throw $e;
        }
    }
}
