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
    /**
     * Stampa il nome della funzione che ha generato l'errore.
     *
     * Il metodo printErrorInfo() viene chiamato nel caso una funzione abbia
     * generato un errore. Restituisce una stringa con il nome della funzione e
     * la data e l'ora.
     *
     * @param string $functionName
     * @return string
     */
    public static function printErrorInfo(string $functionName) : string
    {
        $date = new \DateTime();
        $message = $date->format('d/m/Y H:i:s') . ': Errore fatale funzione <b>' . $functionName . '()</b><br/>';
        return $message;
    }
}
