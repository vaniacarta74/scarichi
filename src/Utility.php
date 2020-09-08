<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi;

/**
 * Description of Scarichi
 *
 * @author Vania
 */
class Utility
{
    
    public static function printErrorInfo(string $functionName) : string
    {
        $date = new \DateTime();
        $message = $date->format('d/m/Y H:i:s') . ': Errore fatale funzione <b>' . $functionName . '()</b><br/>';
        return $message;
    }
}
