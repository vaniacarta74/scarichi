<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi\tests\Unit;

use PHPUnit\Framework\TestCase;
use vaniacarta74\Scarichi\Error;

/**
 * Description of ErrorTest
 *
 * @author Vania
 */
class ErrorTest extends TestCase
{
    /**
     * @covers \vaniacarta74\Scarichi\Error::printErrorInfo
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
        
        $actual = Error::printErrorInfo($functionName, 2);
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Error::printErrorInfo
     */
    public function testPrintErrorInfoOutputFile() : void
    {
        $dateTime = new \DateTime();
        $dateTime->setTimezone(new \DateTimeZone('Europe/Rome'));
        $date = $dateTime->format('d/m/Y H:i:s');
        $functionName = 'pippo';
        
        $expected = $date . ' Errore fatale funzione ' . $functionName . '()' . PHP_EOL;
        
        Error::printErrorInfo($functionName, 1);
        
        $actual = file_get_contents(Error::$logFile, false, null, -1 * strlen($expected));
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Error::printErrorInfo
     */
    public function testPrintErrorInfoNoOutput() : void
    {
        $functionName = 'pippo';
        
        $expected = '';
        
        $this->expectOutputString($expected);
        
        Error::printErrorInfo($functionName, 0);
    }
    
    /**
    * @covers \vaniacarta74\Scarichi\Error::appendToFile
    */
    public function testAppendToFileContents() : void
    {
        $message = 'Test message';
        
        $expected = $message;
        
        Error::appendToFile($message);
        
        $actual = file_get_contents(Error::$logFile, false, null, -1 * strlen($message));
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
    * @covers \vaniacarta74\Scarichi\Error::appendToFile
    */
    public function testAppendToFileMode() : void
    {
        $message = 'Test file mode';
        
        Error::appendToFile($message);
        
        $expected = '0777';
        
        $actual = substr(sprintf('%o', fileperms(Error::$logFile)), -4);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Error::errorMsgCli
     */
    public function testErrorMsgCliContainsString() : void
    {
        try {
            if (true) {
                throw new \Exception('Test if errorMsgCli() contains a string');
            }
        } catch (\Exception $e) {
            $expected = 'Messaggio di errore: ' . $e->getMessage() . PHP_EOL;
            $actual = Error::errorMsgCli($e);

            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Error::errorMsgHtml
     */
    public function testErrorMsgHtmlContainsString() : void
    {
        try {
            if (true) {
                throw new \Exception('Test if errorMsgHtml() contains a string');
            }
        } catch (\Exception $e) {
            $expected = 'Messaggio di errore: <b>' . $e->getMessage() . '</b><br/>';
            $actual = Error::errorMsgHtml($e);

            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Error::errorMsgJson
     */
    public function testErrorMsgJsonContainsString() : void
    {
        try {
            if (true) {
                throw new \Exception('Test if errorMsgJson() contains a string');
            }
        } catch (\Exception $e) {
            $expected = '"Messaggio di errore":"' . $e->getMessage() . '"';
            $actual = Error::errorMsgJson($e);

            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Error::errorHandler
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
            
            Error::errorHandler($e, 0, 'cli');
        }
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Error::errorHandler
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

            Error::errorHandler($e, 1, 'cli');

            $actual = file_get_contents(Error::$logFile);

            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Error::errorHandler
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
            
            Error::errorHandler($e, 2, 'cli');
        }
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Error::errorHandler
     */
    public function testErrorHandlerHtmlOutputRegex() : void
    {
        try {
            if (true) {
                throw new \Exception('Test if errorHandler return a www string');
            }
        } catch (\Exception $e) {
            $expected = 'Messaggio di errore: <b>' . $e->getMessage() . '<\/b><br\/>';
            $regex = '/(' . $expected . ')/';
            
            $this->expectOutputRegex($regex);
            
            Error::errorHandler($e, 2, 'html');
        }
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Error::noticeMsgCli
     */
    public function testNoticeMsgCliContainsString() : void
    {
        try {
            if (true) {
                throw new \Exception('Test if noticeMsgCli() contains a string');
            }
        } catch (\Exception $e) {
            $expected = 'Notifica: ' . $e->getMessage() . PHP_EOL;
            $actual = Error::noticeMsgCli($e);

            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Error::noticeMsgHtml
     */
    public function testNoticeMsgHtmlContainsString() : void
    {
        try {
            if (true) {
                throw new \Exception('Test if noticeMsgHtml() contains a string');
            }
        } catch (\Exception $e) {
            $expected = '<b>Notifica:</b> ' . $e->getMessage() . '<br/>';
            $actual = Error::noticeMsgHtml($e);

            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Error::noticeMsgJson
     */
    public function testNoticeMsgJsonContainsString() : void
    {
        try {
            if (true) {
                throw new \Exception('Test if noticeMsgJson() contains a string');
            }
        } catch (\Exception $e) {
            $expected = '{"ok":false,"codice errore":400,"descrizione errore":"' . $e->getMessage() . '"}';
            $actual = Error::noticeMsgJson($e);

            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Error::noticeHandler
     */
    public function testNoticeHandlerNoOutput() : void
    {
        try {
            if (true) {
                throw new \Exception('Test if noticeHandler() return a string');
            }
        } catch (\Exception $e) {
            $expected = '';
        
            $this->expectOutputString($expected);
            
            Error::noticeHandler($e, 0, 'cli');
        }
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Error::noticeHandler
     */
    public function testNoticeHandlerOutputFile() : void
    {
        try {
            if (true) {
                throw new \Exception('Test if noticeHandler() return a string');
            }
        } catch (\Exception $e) {
            $expected = 'Notifica: ' . $e->getMessage() . PHP_EOL;

            Error::noticeHandler($e, 1, 'cli');

            $actual = file_get_contents(Error::$logFile);

            $this->assertStringContainsString($expected, $actual);
        }
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Error::noticeHandler
     */
    public function testNoticeHandlerCliOutputRegex() : void
    {
        try {
            if (true) {
                throw new \Exception('Test if noticeHandler return a cli string');
            }
        } catch (\Exception $e) {
            $expected = 'Notifica\: ' . htmlspecialchars(strip_tags($e->getMessage())) . '\\n';
            $regex = '/(' . $expected . ')/';
            
            $this->expectOutputRegex($regex);
            
            Error::noticeHandler($e, 2, 'cli');
        }
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Error::noticeHandler
     */
    public function testNoticeHandlerCliTagInputRegex() : void
    {
        try {
            if (true) {
                throw new \Exception('Test if <b>noticeHandler</b> return a cli string');
            }
        } catch (\Exception $e) {
            $expected = 'Notifica\: ' . htmlspecialchars(strip_tags($e->getMessage())) . '\\n';
            $regex = '/(' . $expected . ')/';
            
            $this->expectOutputRegex($regex);
            
            Error::noticeHandler($e, 2, 'cli');
        }
    }
    
    /**
     * @covers \vaniacarta74\Scarichi\Error::noticeHandler
     */
    public function testNoticeHandlerHtmlOutputRegex() : void
    {
        try {
            if (true) {
                throw new \Exception('Test if noticeHandler return a www string');
            }
        } catch (\Exception $e) {
            $expected = '<b>Notifica:<\/b> ' . $e->getMessage() . '<br\/>';
            $regex = '/(' . $expected . ')/';
            
            $this->expectOutputRegex($regex);
            
            Error::noticeHandler($e, 2, 'html');
        }
    }
}
