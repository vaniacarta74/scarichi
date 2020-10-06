<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi;

/**
 * Classe di utilità.
 *
 * Nella classe Utility sono contenuti i metodi di utilità utilizzati nelle
 * altre classi delle API del progetto Scarichi.
 *
 * @author Vania Carta
 */
class Utility
{
    public static $logFile = \LOG_PATH . '/error.log';
    
    /**
     * Stampa il nome della funzione che ha generato l'errore.
     *
     * Il metodo printErrorInfo() viene chiamato nel caso una funzione abbia
     * generato un errore. Restituisce una stringa con il nome della funzione e
     * la data e l'ora.
     *
     * @param string $functionName
     * @return void
     */
    public static function printErrorInfo(string $functionName) : void
    {
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone('Europe/Rome'));
        switch (DEBUG_LEVEL) {
            case 0:
                break;
            case 1:
                $message = $date->format('d/m/Y H:i:s') . ' Errore fatale funzione ' . $functionName . '()' . PHP_EOL;
                self::appendToFile($message);
                break;
            case 2:
                $message = $date->format('d/m/Y H:i:s') . ': Errore fatale funzione <b>' . $functionName . '()</b><br/>';
                echo $message;
                break;
        }       
    }
    
    public static function errorHandler(\Throwable $e) : void
    {
        switch (DEBUG_LEVEL) {
            case 0:
                break;
            case 1:
                $offset = 3;
                $message = 'Descrizione Errore' . PHP_EOL;
                
                $lines[] = 'File: ' . $e->getFile() . PHP_EOL;                
                $lines[] = 'Linea: ' . $e->getLine() . PHP_EOL;
                $lines[] = 'Codice errore: ' . $e->getCode() . PHP_EOL;
                $lines[] = 'Messaggio di errore: ' . $e->getMessage() . PHP_EOL;
                
                foreach ($lines as $line) {
                    $message .= str_pad($line, strlen($line) + $offset, ' ', STR_PAD_LEFT);
                }                
                $stack = $e->getTraceAsString();
                $arrStack = explode('#', $stack);

                $message .= 'Stack' . PHP_EOL;
                foreach ($arrStack as $line) {
                    $message .= $line !== '' ? str_pad($line, strlen($line) + $offset, ' ', STR_PAD_LEFT) : '';
                }
                $message .= PHP_EOL;
                
                self::appendToFile($message);
                break;
            case 2:
                $message = '<br/><b>Descrizione Errore</b><br/>';
                $message .= 'File: ' . $e->getFile() . '<br/>';
                $message .= 'Linea: ' . $e->getLine() . '<br/>';
                $message .= 'Codice errore: ' . $e->getCode() . '<br/>';
                $message .= 'Messaggio di errore: <b>' . $e->getMessage() . '</b><br/>';

                $stack = $e->getTraceAsString();
                $arrStack = explode('#', $stack);

                $message .= '<br/><b>Stack</b>';
                foreach ($arrStack as $line) {
                    $message .= $line . '<br/>';
                }
                echo $message;
                break;
        }         
    }
    
    public static function appendToFile(string $message) : void
    {
        file_put_contents(self::$logFile, $message, FILE_APPEND);
        chmod(self::$logFile, 0777);
    }
}
