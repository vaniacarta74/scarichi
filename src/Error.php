<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi;

/**
 * Classe gestione errori.
 *
 * Nella classe Error sono contenuti i metodi per la gestione degli errori
 * utilizzati nelle altre classi delle API del progetto Scarichi.
 *
 * @author Vania Carta
 */
class Error
{
    public static $logFile = \LOG_PATH . '/' . ERROR_LOG;
    
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
    public static function debugLevel() : int
    {
        return defined('DEBUG_LEVEL') ? DEBUG_LEVEL : 1;
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
    public static function printErrorInfo(string $functionName, int $debug_level) : void
    {
        $date = new \DateTime('NOW', new \DateTimeZone('Europe/Rome'));
        switch ($debug_level) {
            case 0:
            case 2:
                break;
            case 1:
                $message = $date->format('d/m/Y H:i:s') . ' Errore fatale funzione ' . $functionName . '()' . PHP_EOL;
                self::appendToFile($message);
                break;
        }
    }
    
    /**
     * Gestisce il messagio d'errore in funzione del livello di debug.
     *
     * Il metodo errorHandler() viene utilizzato per gestire il messaggio di errore
     * in funzione sia del livello di debug che del contesto in cui viene chiamato.
     * Se il livello di debug è uguale a 0 l'errore non viene gestito. Se è uguale
     * ad 1 il messaggio di errore viene stampato nell'error log, mentre se è uguale
     * a 2 viene stampato sul terminale o in html a seconda del contesto.
     *
     * @param \Throwable $e Oggetto che gestisce l'errore/eccezione/notifica
     * @param int $debug_level Livello di debug
     * @param bool $isCli Vero o falso a seconda del contesto
     * @return void
     */
    public static function errorHandler(\Throwable $e, int $debug_level, string $type) : void
    {
        switch ($debug_level) {
            case 0:
                break;
            case 1:
                $message = self::errorMsgCli($e);
                self::appendToFile($message);
                break;
            case 2:
                echo call_user_func('self::errorMsg' . ucfirst($type), $e);
                break;
        }
    }
    
    /**
     * Gestisce il messagio d'errore in funzione del livello di debug.
     *
     * Il metodo errorHandler() viene utilizzato per gestire il messaggio di errore
     * in funzione sia del livello di debug che del contesto in cui viene chiamato.
     * Se il livello di debug è uguale a 0 l'errore non viene gestito. Se è uguale
     * ad 1 il messaggio di errore viene stampato nell'error log, mentre se è uguale
     * a 2 viene stampato sul terminale o in html a seconda del contesto.
     *
     * @param \Throwable $e Oggetto che gestisce l'errore/eccezione/notifica
     * @param int $debug_level Livello di debug
     * @param bool $isCli Vero o falso a seconda del contesto
     * @return void
     */
    public static function noticeHandler(\Throwable $e, int $debug_level, string $type) : void
    {
        $date = new \DateTime('NOW', new \DateTimeZone('Europe/Rome'));
        switch ($debug_level) {
            case 0:
                break;
            case 1:
                $message = $date->format('d/m/Y H:i:s') . ' ' . self::noticeMsgCli($e);
                self::appendToFile($message);
                break;
            case 2:
                echo call_user_func('self::noticeMsg' . ucfirst($type), $e);
                break;
        }
    }
    
    /**
     * Stampa su file il messaggio di errore.
     *
     * Il metodo appendToFile() stampa su file error log il messaggio di errore
     * accodandolo al testo presente o creando il file se questo non esiste. In
     * questo caso modifica anche le proprietà di lettura/scrittura del file
     *
     * @param string $message Il messaggio da stampare
     * @return void
     */
    public static function appendToFile(string $message) : void
    {
        file_put_contents(self::$logFile, $message, FILE_APPEND);
        chmod(self::$logFile, 0777);
    }
    
    /**
     * Formatta il messagio d'errore in funzione del contesto.
     *
     * Il metodo defineErrorMsg() viene utilizzato per formattare il messaggio di
     * errore in funzione del contesto in cui viene chiamato. In particolare, se
     * il messaggio deve essere stampato su terminale o file vengono impostati
     * codici di fine riga e formato diverso rispetto alla formattazione html.
     *
     * @param \Throwable $e Oggetto che gestisce l'errore/eccezione/notifica
     * @return string Messaggio di errore formattato
     */
    public static function errorMsgCli(\Throwable $e) : string
    {
        $offset = 3;
        $message = 'Descrizione Errore' . PHP_EOL;

        $lines[] = 'File: ' . $e->getFile() . PHP_EOL;
        $lines[] = 'Linea: ' . $e->getLine() . PHP_EOL;
        $lines[] = 'Codice errore: ' . $e->getCode() . PHP_EOL;
        $lines[] = 'Messaggio di errore: ' . htmlspecialchars(strip_tags($e->getMessage())) . PHP_EOL;

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
        return $message;
    }
    
    /**
     * Formatta il messagio d'errore in funzione del contesto.
     *
     * Il metodo defineErrorMsg() viene utilizzato per formattare il messaggio di
     * errore in funzione del contesto in cui viene chiamato. In particolare, se
     * il messaggio deve essere stampato su terminale o file vengono impostati
     * codici di fine riga e formato diverso rispetto alla formattazione html.
     *
     * @param \Throwable $e Oggetto che gestisce l'errore/eccezione/notifica
     * @return string Messaggio di errore formattato
     */
    public static function errorMsgHtml(\Throwable $e) : string
    {
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
        return $message;
    }
    
    /**
     * Formatta il messagio d'errore in funzione del contesto.
     *
     * Il metodo defineErrorMsg() viene utilizzato per formattare il messaggio di
     * errore in funzione del contesto in cui viene chiamato. In particolare, se
     * il messaggio deve essere stampato su terminale o file vengono impostati
     * codici di fine riga e formato diverso rispetto alla formattazione html.
     *
     * @param \Throwable $e Oggetto che gestisce l'errore/eccezione/notifica
     * @return string Messaggio di errore formattato
     */
    public static function errorMsgJson(\Throwable $e) : string
    {
        $arrJson = [
            'Descrizione errore' => [
                'File' => $e->getFile(),
                'Linea' => $e->getLine(),
                'Codice errore' => $e->getCode(),
                'Messaggio di errore' => htmlspecialchars(strip_tags($e->getMessage()))
            ]
        ];
        $arrStack = explode('#', $e->getTraceAsString());
        array_walk($arrStack, function ($value, $key) use (&$arrJson) {
            if ($value !== '') {
                $arrJson['Stack'][$key - 1] = substr($value, 2);
            }
        });
        $message = json_encode($arrJson);
        return $message;
    }
    
    /**
     * Formatta il messagio d'errore in funzione del contesto.
     *
     * Il metodo defineErrorMsg() viene utilizzato per formattare il messaggio di
     * errore in funzione del contesto in cui viene chiamato. In particolare, se
     * il messaggio deve essere stampato su terminale o file vengono impostati
     * codici di fine riga e formato diverso rispetto alla formattazione html.
     *
     * @param \Throwable $e Oggetto che gestisce l'errore/eccezione/notifica
     * @return string Messaggio di errore formattato
     */
    public static function noticeMsgCli(\Throwable $e) : string
    {
        return 'Notifica: ' . htmlspecialchars(strip_tags($e->getMessage())) . PHP_EOL;       
    }
    
    /**
     * Formatta il messagio d'errore in funzione del contesto.
     *
     * Il metodo defineErrorMsg() viene utilizzato per formattare il messaggio di
     * errore in funzione del contesto in cui viene chiamato. In particolare, se
     * il messaggio deve essere stampato su terminale o file vengono impostati
     * codici di fine riga e formato diverso rispetto alla formattazione html.
     *
     * @param \Throwable $e Oggetto che gestisce l'errore/eccezione/notifica
     * @return string Messaggio di errore formattato
     */
    public static function noticeMsgHtml(\Throwable $e) : string
    {
        return '<b>Notifica:</b> ' . $e->getMessage() . '<br/>';
    }
    
    /**
     * Formatta il messagio d'errore in funzione del contesto.
     *
     * Il metodo defineErrorMsg() viene utilizzato per formattare il messaggio di
     * errore in funzione del contesto in cui viene chiamato. In particolare, se
     * il messaggio deve essere stampato su terminale o file vengono impostati
     * codici di fine riga e formato diverso rispetto alla formattazione html.
     *
     * @param \Throwable $e Oggetto che gestisce l'errore/eccezione/notifica
     * @return string Messaggio di errore formattato
     */
    public static function noticeMsgJson(\Throwable $e) : string
    {
        $arrJson = [
            'ok' => false,
            'codice errore' => 400,
            'descrizione errore' => htmlspecialchars(strip_tags($e->getMessage()))
        ];
        return json_encode($arrJson);
    }
}
