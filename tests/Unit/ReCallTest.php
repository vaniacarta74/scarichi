<?php
namespace vaniacarta74\Scarichi\tests\Unit;

use PHPUnit\Framework\TestCase;
use vaniacarta74\Scarichi\Curl;

use function vaniacarta74\Scarichi\setHeader as setHeader;

class RecallTest extends TestCase
{
    
    /**
     * coversNothing
     */
    public function recallProvider() : array
    {
        $header = setHeader(COMPOSER);
        $now = new \DateTime('now', new \DateTimeZone('Europe/Rome'));        
        
        $data = [
            'standard' => [
                'var' => '30030',
                'datefrom' => '01/01/2019',
                'dateto' => '02/01/2019',
                'expected' => [
                    'ok' => true,
                    'response' => [
                        'version' => $header,
                        'date' => $now->format('Y-m-d H:') . '|',
                        'sync' => [
                            '1.0) PID sync.0: Processata in 0,039 secondi | Records: 240 | Insert: 1 | Update: 0 | Presenti: 239 | Scartati: 0 | Cancellati: 0',
                            '1.1) PID sync.1: Processata in 0,098 secondi | Records: 955 | Insert: 10 | Update: 0 | Presenti: 939 | Scartati: 6 | Cancellati: 0'
                        ],
                        'tocsv' => [
                            '1) PID 0: Elaborazione dati Volume variabile 30030 dal 01/01/2019 al 02/01/2019 avvenuta con successo in | sec. File CSV full esportati: 1 (tocsv@' . LOCALHOST . ')'                            
                        ],
                        'watchdog host 1' => [
                            '1) PID watchdog: Processato in 0,005 secondi | Records: 47 | Insert: 0 | Update: 0 | Presenti: 47 | Scartati: 0 | Cancellati: 0'
                        ],
                        'watchdog host 2' => [
                            '1) PID watchdog: Processato in 0,005 secondi | Records: 47 | Insert: 0 | Update: 0 | Presenti: 47 | Scartati: 0 | Cancellati: 0'
                        ],
                        'telegram' => [
                            'Invio messaggio Telegram disabilitato.'
                        ]                        
                    ]
                ]
            ],
            'standard double' => [
                'var' => '30030,30040',
                'datefrom' => '01/01/2019',
                'dateto' => '02/01/2019',
                'expected' => [
                    'ok' => true,
                    'response' => [
                        'version' => $header,
                        'date' => $now->format('Y-m-d H:') . '|',
                        'sync' => [
                            '1.0) PID sync.0: Processata in 0,039 secondi | Records: 240 | Insert: 1 | Update: 0 | Presenti: 239 | Scartati: 0 | Cancellati: 0',
                            '1.1) PID sync.1: Processata in 0,098 secondi | Records: 955 | Insert: 10 | Update: 0 | Presenti: 939 | Scartati: 6 | Cancellati: 0'
                        ],
                        'tocsv' => [
                            '1) PID 0: Elaborazione dati Volume variabile 30030 dal 01/01/2019 al 02/01/2019 avvenuta con successo in | sec. File CSV full esportati: 1 (tocsv@' . LOCALHOST . ')'                            
                        ],
                        'watchdog host 1' => [
                            '1) PID watchdog: Processato in 0,005 secondi | Records: 47 | Insert: 0 | Update: 0 | Presenti: 47 | Scartati: 0 | Cancellati: 0'
                        ],
                        'watchdog host 2' => [
                            '1) PID watchdog: Processato in 0,005 secondi | Records: 47 | Insert: 0 | Update: 0 | Presenti: 47 | Scartati: 0 | Cancellati: 0'
                        ],
                        'telegram' => [
                            'Invio messaggio Telegram disabilitato.'
                        ]                        
                    ]
                ]
            ],
            'standard double +' => [
                'var' => '30030+30040',
                'datefrom' => '01/01/2019',
                'dateto' => '02/01/2019',
                'expected' => [
                    'ok' => true,
                    'response' => [
                        'version' => $header,
                        'date' => $now->format('Y-m-d H:') . '|',
                        'sync' => [
                            '1.0) PID sync.0: Processata in 0,039 secondi | Records: 240 | Insert: 1 | Update: 0 | Presenti: 239 | Scartati: 0 | Cancellati: 0',
                            '1.1) PID sync.1: Processata in 0,098 secondi | Records: 955 | Insert: 10 | Update: 0 | Presenti: 939 | Scartati: 6 | Cancellati: 0'
                        ],
                        'tocsv' => [
                            '1) PID 0: Elaborazione dati Volume variabile 30030 dal 01/01/2019 al 02/01/2019 avvenuta con successo in | sec. File CSV full esportati: 1 (tocsv@' . LOCALHOST . ')'                            
                        ],
                        'watchdog host 1' => [
                            '1) PID watchdog: Processato in 0,005 secondi | Records: 47 | Insert: 0 | Update: 0 | Presenti: 47 | Scartati: 0 | Cancellati: 0'
                        ],
                        'watchdog host 2' => [
                            '1) PID watchdog: Processato in 0,005 secondi | Records: 47 | Insert: 0 | Update: 0 | Presenti: 47 | Scartati: 0 | Cancellati: 0'
                        ],
                        'telegram' => [
                            'Invio messaggio Telegram disabilitato.'
                        ]                        
                    ]
                ]
            ],
            'standard all' => [
                'var' => 'ALL',
                'datefrom' => '01/01/2019',
                'dateto' => '02/01/2019',
                'expected' => [
                    'ok' => true,
                    'response' => [
                        'version' => $header,
                        'date' => $now->format('Y-m-d H:') . '|',
                        'sync' => [
                            '1.0) PID sync.0: Processata in 0,039 secondi | Records: 240 | Insert: 1 | Update: 0 | Presenti: 239 | Scartati: 0 | Cancellati: 0',
                            '1.1) PID sync.1: Processata in 0,098 secondi | Records: 955 | Insert: 10 | Update: 0 | Presenti: 939 | Scartati: 6 | Cancellati: 0',
                            '1.2) PID sync.2: Processata in 0,045 secondi | Records: 149 | Insert: 1 | Update: 0 | Presenti: 148 | Scartati: 0 | Cancellati: 0'
                        ],
                        'tocsv' => [
                            '|) PID |: Elaborazione dati Volume variabile | dal 01/01/2019 al 02/01/2019 avvenuta con successo in | sec. File CSV full esportati: 1 (tocsv@' . LOCALHOST . ')'                            
                        ],
                        'watchdog host 1' => [
                            '1) PID watchdog: Nessun files da processare!'
                        ],
                        'watchdog host 2' => [
                            '1) PID watchdog: Nessun files da processare!'
                        ],
                        'telegram' => [
                            'Invio messaggio Telegram disabilitato.'
                        ]                        
                    ]
                ]
            ],
            'only datefrom' => [
                'var' => '30030',
                'datefrom' => '01/05/2020',
                'dateto' => null,
                'expected' => [
                    'ok' => true,
                    'response' => [
                        'version' => $header,
                        'date' => $now->format('Y-m-d H:') . '|',
                        'sync' => [
                            '1.0) PID sync.0: Processata in 0,039 secondi | Records: 240 | Insert: 1 | Update: 0 | Presenti: 239 | Scartati: 0 | Cancellati: 0',
                            '1.1) PID sync.1: Processata in 0,098 secondi | Records: 955 | Insert: 10 | Update: 0 | Presenti: 939 | Scartati: 6 | Cancellati: 0'
                        ],
                        'tocsv' => [
                            '1) PID 0: Elaborazione dati Volume variabile 30030 dal 01/05/2020 al ' . $now->format('d/m/Y') . ' avvenuta con successo in | sec. File CSV full esportati: 1 (tocsv@' . LOCALHOST . ')'                            
                        ],
                        'watchdog host 1' => [
                            '1) PID watchdog: Processato in 0,005 secondi | Records: 47 | Insert: 0 | Update: 0 | Presenti: 47 | Scartati: 0 | Cancellati: 0'
                        ],
                        'watchdog host 2' => [
                            '1) PID watchdog: Processato in 0,005 secondi | Records: 47 | Insert: 0 | Update: 0 | Presenti: 47 | Scartati: 0 | Cancellati: 0'
                        ],
                        'telegram' => [
                            'Invio messaggio Telegram disabilitato.'
                        ]                        
                    ]
                ]
            ],
            'only dateto' => [
                'var' => '30030',
                'datefrom' => null,
                'dateto' => '02/05/2019',
                'expected' => [
                    'ok' => true,
                    'response' => [
                        'version' => $header,
                        'date' => $now->format('Y-m-d H:') . '|',
                        'sync' => [
                            '1.0) PID sync.0: Processata in 0,039 secondi | Records: 240 | Insert: 1 | Update: 0 | Presenti: 239 | Scartati: 0 | Cancellati: 0',
                            '1.1) PID sync.1: Processata in 0,098 secondi | Records: 955 | Insert: 10 | Update: 0 | Presenti: 939 | Scartati: 6 | Cancellati: 0'
                        ],
                        'tocsv' => [
                            '1) PID 0: Elaborazione dati Volume variabile 30030 dal 01/05/2019 al 02/05/2019 avvenuta con successo in | sec. File CSV full esportati: 1 (tocsv@' . LOCALHOST . ')'                            
                        ],
                        'watchdog host 1' => [
                            '1) PID watchdog: Processato in 0,005 secondi | Records: 47 | Insert: 0 | Update: 0 | Presenti: 47 | Scartati: 0 | Cancellati: 0'
                        ],
                        'watchdog host 2' => [
                            '1) PID watchdog: Processato in 0,005 secondi | Records: 47 | Insert: 0 | Update: 0 | Presenti: 47 | Scartati: 0 | Cancellati: 0'
                        ],
                        'telegram' => [
                            'Invio messaggio Telegram disabilitato.'
                        ]                        
                    ]
                ]
            ],
            'only var' => [
                'var' => '30030',
                'datefrom' => null,
                'dateto' => null,
                'expected' => [
                    'ok' => false,
                    'codice errore' => 400,
                    'descrizione errore' => 'Parametri intervallo date assenti. Indicare uno o entrambi i parametri &quot;datefrom&quot; e &quot;dateto&quot;'
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group main
     * covers recall.php
     * @dataProvider recallProvider
     */
    public function testReCallStringContainsString(string $var, ?string $dateFrom, ?string $dateTo, array $response) : void
    {
        $from = isset($dateFrom) ? '&datefrom=' . $dateFrom : '';
        $to = isset($dateTo) ? '&dateto=' . $dateTo : '';
        $args = '?var=' . $var . $from . $to;
        
        $actual = Curl::run('http://' . LOCALHOST . '/scarichi/recall.php'. $args);
        
        $json = json_encode($response, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
            
        $expecteds = explode('|', $json);

        foreach ($expecteds as $expected) {
            $this->assertStringContainsString($expected, $actual);
        }
    }
}
