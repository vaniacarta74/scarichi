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
        $expected_OLD = $date . ': Errore fatale funzione <b>' . $functionName . '()</b><br/>';
        
        $expected = '';
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
    
     /**
     * @covers \vaniacarta74\Scarichi\Utility::appendToFile
     */
    public function testAppendToFileContents() : void
    {
        $message = 'Test message';
        
        $expected = $message;
        
        Utility::appendToFile($message);
        
        $actual = file_get_contents(Utility::$logFile, false, null, -1 * strlen($message));
        
        $this->assertEquals($expected, $actual);
    }
    
     /**
     * @covers \vaniacarta74\Scarichi\Utility::appendToFile
     */
    public function testAppendToFileMode() : void
    {
        $message = 'Test file mode';
        
        Utility::appendToFile($message);
        
        $expected = '0777';
        
        $actual = substr(sprintf('%o', fileperms(Utility::$logFile)), -4);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Utility::defineMessage
     */    
    public function testDefineMessageCliContainsString() : void
    {
        try {
            if (true) {
                throw new \Exception('Test if defineMessage() contains a string');
            }
        } catch (\Exception $e) {
            $expected = 'Messaggio di errore: ' . $e->getMessage() . PHP_EOL;
            $actual = Utility::defineMessage($e, true);

            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Utility::defineMessage
     */    
    public function testDefineMessageWwwContainsString() : void
    {
        try {
            if (true) {
                throw new \Exception('Test if defineMessage() contains a string');
            }
        } catch (\Exception $e) {
            $expected = 'Messaggio di errore: <b>' . $e->getMessage() . '</b><br/>';
            $actual = Utility::defineMessage($e, false);

            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Utility::errorHandler
     */
    public function testErrorHandlerNoOutput() : void
    {
        try {
            if (true) {
                throw new \Exception('Test if errorHandler() return a string');
            }
        } catch (\Exception $e) {
            $expected = '';
        
            $this->expectOutputString($expected);
            
            Utility::errorHandler($e, 0, true);
        }
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Utility::errorHandler
     */
    public function testErrorHandlerOutputFile() : void
    {
        try {
            $dateTime = new \DateTime();
            $dateTime->setTimezone(new \DateTimeZone('Europe/Rome'));
            $date = $dateTime->format('d/m/Y H:i:s');
            if (true) {
                throw new \Exception($date . ' Test if errorHandler() return a string');
            }
        } catch (\Exception $e) {
            $expected = 'Messaggio di errore: ' . $e->getMessage() . PHP_EOL;

            Utility::errorHandler($e, 1, true);

            $actual = file_get_contents(Utility::$logFile);

            $this->assertStringContainsString($expected, $actual);
        }
    }   
    
    /**
     * @covers \vaniacarta74\Scarichi\Utility::errorHandler
     */
    public function testErrorHandlerCliOutputRegex() : void
    {
        try {
            if (true) {
                throw new \Exception('Test if errorHandler return a cli string');
            }
        } catch (\Exception $e) {
            $expected = 'Messaggio di errore\: ' . $e->getMessage() . '\\n';
            $regex = '/(' . $expected . ')/';
            
            $this->expectOutputRegex($regex);
            
            Utility::errorHandler($e, 2, true);
        }
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Utility::errorHandler
     */
    public function testErrorHandlerWwwOutputRegex() : void
    {
        try {
            if (true) {
                throw new \Exception('Test if errorHandler return a www string');
            }
        } catch (\Exception $e) {
            $expected = 'Messaggio di errore: <b>' . $e->getMessage() . '<\/b><br\/>';
            $regex = '/(' . $expected . ')/';
            
            $this->expectOutputRegex($regex);
            
            Utility::errorHandler($e, 2, false);
        }
    }
}
