<?php
namespace vaniacarta74\Scarichi\tests\Unit;

use PHPUnit\Framework\TestCase;

use function vaniacarta74\Scarichi\setHeader as setHeader;

class ScarichiTest extends TestCase
{
    
    /**
     * coversNothing
     */
    public function scarichiProvider() : array
    {
        $composer = COMPOSER;
        $description = $composer['description'];
        $header = setHeader($composer). PHP_EOL . '|';
        $headerNoStart = setHeader($composer);
        $single = '1) PID 0: Elaborazione dati Volume variabile 30030 dal 01/01/2019 al 02/01/2019 avvenuta con successo in | sec. File CSV full esportati: |' . PHP_EOL;
        $double = $single . '2) PID 1: Elaborazione dati Volume variabile 30040 dal 01/01/2019 al 02/01/2019 avvenuta con successo in | sec. File CSV full esportati: |' . PHP_EOL;
        
        $now = new \DateTime();
        $day = new \DateTime();
        $interval = new \DateInterval('P1D');
        $now->add($interval);
        $day->sub($interval);
        
        $data = [
            'standard' => [
                'help' => null,
                'version' => null,
                'default' => null,
                'var' => '30030',
                'datefrom' => '01/01/2019',
                'dateto' => '02/01/2019',
                'nozero' => 'FALSE',
                'campo' => 'V',
                'expected' => $header . PHP_EOL . $single
            ],
            'standard double' => [
                'help' => null,
                'version' => null,
                'default' => null,
                'var' => '30030,30040',
                'datefrom' => '01/01/2019',
                'dateto' => '02/01/2019',
                'nozero' => 'FALSE',
                'campo' => 'V',
                'expected' => $header . PHP_EOL . $double
            ],
            'no full and field' => [
                'help' => null,
                'version' => null,
                'default' => null,
                'var' => '30030',
                'datefrom' => '01/01/2019',
                'dateto' => '02/01/2019',
                'nozero' => '',
                'campo' => '',
                'expected' => $header . PHP_EOL . $single
            ],
            'only datefrom' => [
                'help' => null,
                'version' => null,
                'default' => null,
                'var' => '30030',
                'datefrom' => '01/05/2020',
                'dateto' => '',
                'nozero' => '',
                'campo' => '',
                'expected' => $header . PHP_EOL . '1) PID 0: Elaborazione dati Volume variabile 30030 dal 01/05/2020 al ' . $now->format('d/m/Y') . ' avvenuta con successo in | sec. File CSV full esportati: |' . PHP_EOL
            ],
            'datefrom costant' => [
                'help' => null,
                'version' => null,
                'default' => null,
                'var' => '30030',
                'datefrom' => '2D',
                'dateto' => '03/01/2019',
                'nozero' => '',
                'campo' => '',
                'expected' => $header . PHP_EOL . '1) PID 0: Elaborazione dati Volume variabile 30030 dal 01/01/2019 al 03/01/2019 avvenuta con successo in | sec. File CSV full esportati: |' . PHP_EOL
            ],
            'only dateto' => [
                'help' => null,
                'version' => null,
                'default' => null,
                'var' => '30030',
                'datefrom' => '',
                'dateto' => '02/05/2019',
                'nozero' => '',
                'campo' => '',
                'expected' => $header . PHP_EOL . '1) PID 0: Elaborazione dati Volume variabile 30030 dal 01/05/2019 al 02/05/2019 avvenuta con successo in | sec. File CSV full esportati: |' . PHP_EOL
            ],
            'field other' => [
                'help' => null,
                'version' => null,
                'default' => null,
                'var' => '30030',
                'datefrom' => '01/01/2019',
                'dateto' => '02/01/2019',
                'nozero' => '',
                'campo' => 'L',
                'expected' => $header . PHP_EOL . '1) PID 0: Elaborazione dati Livello variabile 30030 dal 01/01/2019 al 02/01/2019 avvenuta con successo in | sec. File CSV full esportati: |' . PHP_EOL
            ],
            'full 0' => [
                'help' => null,
                'version' => null,
                'default' => null,
                'var' => '30030',
                'datefrom' => '01/01/2019',
                'dateto' => '02/01/2019',
                'nozero' => 'TRUE',
                'campo' => '',
                'expected' => $header . PHP_EOL . '1) PID 0: Elaborazione dati Volume variabile 30030 dal 01/01/2019 al 02/01/2019 avvenuta con successo in | sec. Nessun file CSV senza zeri esportato per mancanza di dati.' . PHP_EOL
            ],
            'full 1' => [
                'help' => null,
                'version' => null,
                'default' => null,
                'var' => '30030',
                'datefrom' => '01/01/2019',
                'dateto' => '02/01/2019',
                'nozero' => 'FALSE',
                'campo' => '',
                'expected' => $header . PHP_EOL . $single
            ],
            'only var' => [
                'help' => null,
                'version' => null,
                'default' => null,
                'var' => '30030',
                'datefrom' => '',
                'dateto' => '',
                'nozero' => '',
                'campo' => '',
                'expected' => $header . PHP_EOL . '1) PID 0: Elaborazione dati Volume variabile 30030 dal ' . $day->format('d/m/Y') . ' al ' . $now->format('d/m/Y') . ' avvenuta con successo in | sec. Nessun file CSV full esportato per mancanza di dati.' . PHP_EOL
            ],
            'help' => [
                'help' => '',
                'version' => null,
                'default' => null,
                'var' => null,
                'datefrom' => null,
                'dateto' => null,
                'nozero' => null,
                'campo' => null,
                'expected' => $headerNoStart . PHP_EOL . $description . PHP_EOL
            ],
            'version' => [
                'help' => null,
                'version' => '',
                'default' => null,
                'var' => null,
                'datefrom' => null,
                'dateto' => null,
                'nozero' => null,
                'campo' => null,
                'expected' => $header . PHP_EOL
            ],
            'default' => [
                'help' => null,
                'version' => null,
                'default' => '',
                'var' => null,
                'datefrom' => null,
                'dateto' => null,
                'nozero' => null,
                'campo' => null,
                'expected' => $headerNoStart . PHP_EOL . 'php scarichi.php -V ALL -f YEAR -t NOW -c V -n FALSE' . PHP_EOL . '|'
            ],
            'error' => [
                'help' => null,
                'version' => null,
                'default' => null,
                'var' => '30030',
                'datefrom' => '01/01/2019',
                'dateto' => '02/01/2019',
                'nozero' => 'FALSE',
                'campo' => null,
                'expected' => $header . PHP_EOL . 'Parametri errati o insufficienti. Per info digitare: php scarichi.php -h' . PHP_EOL
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group main
     * covers scarichi.php
     * @dataProvider scarichiProvider
     */
    public function testScarichiEquals(?string $help, ?string $version, ?string $default, ?string $var, ?string $dateFrom, ?string $dateTo, ?string $full, ?string $field, ?string $response) : void
    {
        $paramsRaw = [
            '--help' => $help,
            '--version' => $version,
            '--default' => $default,
            '--var' => $var,
            '--datefrom' => $dateFrom,
            '--dateto' => $dateTo,
            '--campo' => $field,
            '--nozero' => $full
        ];
        
        $paramsFilter = array_filter($paramsRaw, function ($value) {
            return !is_null($value);
        });
        $params = [];
        array_walk($paramsFilter, function ($item, $key) use (&$params) {
            if ($item === '') {
                $params[] = $key;
            } else {
                $params[] = $key . ' ' . $item;
            }
        });
        
        $arg = implode(' ', $params);
       
        $command = 'php ' . __DIR__ . '/../../src/scarichi.php ' . $arg;
        
        if ($arg === '-d' || $arg === '--default') {
            $this->assertEquals('default', 'default');
        } elseif ($arg === '-h' || $arg === '--help') {
            $actual = shell_exec($command);
            $this->assertStringContainsString($response, $actual);
        } else {
            $actual = shell_exec($command);
            
            $expecteds = explode('|', $response);
        
            foreach ($expecteds as $expected) {
                $this->assertStringContainsString($expected, $actual);
            }
        }
    }
}
