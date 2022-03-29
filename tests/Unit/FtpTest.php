<?php
namespace vaniacarta74\Scarichi\tests\Unit;

use PHPUnit\Framework\TestCase;

use function vaniacarta74\Scarichi\changeMode as changeMode;

class FtpTest extends TestCase
{
    
    /**
     * @coversNothing
     */
    public function changeModeProvider() : array
    {
        $data = [
            'standard' => [
                'path' => '/var/www/html/telecontrollo/scarichi/github/tests/providers',
                'url' => '/var/www/html/telecontrollo/scarichi/github/tests/providers/test.csv',
                'mode' => 0777,
                'expected' => true
            ],
            'no change' => [
                'path' => '/var/www/html/telecontrollo/scarichi/github/tests/providers',
                'url' => '/var/www/html/telecontrollo/scarichi/github/tests/providers/pippo.csv',
                'mode' => 0777,
                'expected' => false
            ],
            'ftp 0644' => [
                'path' => 'ftp://' . FTP_USER . '@' . FTP_HOST . '/telecontrollo/scarichi/github/tests/providers',
                'url' => 'ftp://' . FTP_USER . '@' . FTP_HOST . '/telecontrollo/scarichi/github/tests/providers/test.csv',
                'mode' => 0644,
                'expected' => !MAKESUBDIR
            ],
            'ftp 0755' => [
                'path' => 'ftp://' . FTP_USER . '@' . FTP_HOST . '/telecontrollo/scarichi/github/tests/providers',
                'url' => 'ftp://' . FTP_USER . '@' . FTP_HOST . '/telecontrollo/scarichi/github/tests/providers/test.csv',
                'mode' => 0755,
                'expected' => !MAKESUBDIR
            ],
            'ftp 0777' => [
                'path' => 'ftp://' . FTP_USER . '@' . FTP_HOST . '/telecontrollo/scarichi/github/tests',
                'url' => 'ftp://' . FTP_USER . '@' . FTP_HOST . '/telecontrollo/scarichi/github/tests/providers/test.csv',
                'mode' => 0777,
                'expected' => !MAKESUBDIR
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group test
     * covers changeMode()
     * @dataProvider changeModeProvider
     */
    public function testchangeModeEquals(string $path, string $url, int $mode, bool $expected) : void
    {
        $actual = changeMode($path, $url, $mode);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group test
     * covers changeMode()
     */
    public function testchangeModeException() : void
    {
        $path = 'ftp://pippo:pluto@paperino/minni';
        $url = 'ftp://pippo:pluto@paperino/minni/paperoga.csv';
        $mode = 0777;
        
        $this->expectException(\Exception::class);
        
        changeMode($path, $url, $mode);
    }
}
