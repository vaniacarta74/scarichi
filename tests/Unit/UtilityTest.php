<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi\tests\Unit;

use PHPUnit\Framework\TestCase;
use vaniacarta74\Scarichi\Utility;

/**
 * Description of UtilityTest
 *
 * @author Vania
 */
class UtilityTest extends TestCase
{
    /**
     * @covers \vaniacarta74\Scarichi\Utility::printErrorInfo
     */
    public function testPrintErrorInfoOutputString() : void
    {
        $dateTime = new \DateTime();
        $dateTime->setTimezone(new \DateTimeZone('Europe/Rome'));
        $date = $dateTime->format('d/m/Y H:i:s');
        $functionName = 'pippo';
        
        $expected = $date . ': Errore fatale funzione <b>' . $functionName . '()</b><br/>';
        $this->expectOutputString($expected);
        
        $actual = Utility::printErrorInfo($functionName, 2);
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Utility::printErrorInfo
     */
    public function testPrintErrorInfoOutputFile() : void
    {
        $dateTime = new \DateTime();
        $dateTime->setTimezone(new \DateTimeZone('Europe/Rome'));
        $date = $dateTime->format('d/m/Y H:i:s');
        $functionName = 'pippo';
        
        $expected = $date . ' Errore fatale funzione ' . $functionName . '()' . PHP_EOL;
        
        Utility::printErrorInfo($functionName, 1);
        
        $actual = file_get_contents(Utility::$logFile, false, null, -1 * strlen($expected));
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Utility::printErrorInfo
     */
    public function testPrintErrorInfoNoOutput() : void
    {        
        $functionName = 'pippo';
        
        $expected = '';
        
        $this->expectOutputString($expected);
        
        Utility::printErrorInfo($functionName, 0);
    }
}
