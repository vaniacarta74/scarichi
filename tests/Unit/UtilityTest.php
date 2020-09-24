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
    public function testPrintErrorInfo() : void
    {
        $dateTime = new \DateTime();
        $date = $dateTime->format('d/m/Y H:i:s');
        $functionName = 'pippo';
        
        $expected = $date . ': Errore fatale funzione <b>' . $functionName . '()</b><br/>';
        
        $actual = Utility::printErrorInfo($functionName);
        
        $this->assertEquals($expected, $actual);
    }
}
