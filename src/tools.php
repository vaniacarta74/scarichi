<?php

require_once(__DIR__ . '/config/config.php');
require_once('php_MSSQL_router.inc.php');


function printErrorInfo(string $functionName) : void
{
    $date = new DateTime();
    echo $date->format('d/m/Y H:i:s') . ': Errore fatale funzione <b>' . $functionName . '()</b><br/>';
}


function errorHandler(Throwable $e) : string
{
    $html = '<br/><b>Descrizione Errore:</b><br/>';
    $html .= 'File: ' . $e->getFile() . '<br/>';
    $html .= 'Linea: ' . $e->getLine() . '<br/>';
    $html .= 'Codice errore: ' . $e->getCode() . '<br/>';
    $html .= 'Messaggio di errore: <b>' . $e->getMessage() . '</b><br/>';
    
    $stack = $e->getTraceAsString();
    $arrStack = explode('#', $stack);
    
    $html .= '<br/><b>Stack:</b>';
    foreach ($arrStack as $line) {
        $html .= $line . '<br/>';
    }
    return $html;
}


function checkRequest(?array $request) : array
{
    try {
        $variables['var'] = checkVariable($request);
        
        $dates = checkInterval($request);
        
        $filters['full'] = checkFilter($request);
        
        $fields['field'] = checkField($request);
        
        $checked = array_merge($variables, $dates, $filters, $fields);
        
        return $checked;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function checkVariable(?array $request) : string
{
    try {
        if (isset($request['var']) || isset($request['variabile']) || isset($request['variable'])) {
            $paramNames = ['var', 'variabile', 'variable'];
            foreach ($paramNames as $key) {
                if (array_key_exists($key, $request)) {
                    $variabile = htmlspecialchars(strip_tags($request[$key]));
                    break;
                }
            }
            
            $n_variabile = intval($variabile);
            if ($n_variabile >= 30000 && $n_variabile <= 39999) {
                return $variabile;
            } else {
                throw new Exception('Variabile non analizzabile. Valori ammessi compresi fra 30000 e 39999');
            }
        } else {
            throw new Exception("Parametro variabile non presente nell'url o nome parametro non valido. Usare var, variable o variabile");
        }
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}

function checkField(?array $request) : string
{
    try {
        if (isset($request['field'])) {
            $fieldsNames = [
                'volume',
                'livello',
                'portata',
                'media',
                'delta',
                'altezza'
            ];
            
            $urlField = strtolower(htmlspecialchars(strip_tags($request['field'])));
            
            if (in_array($urlField, $fieldsNames)) {
                $field = $urlField;
            } else {
                throw new Exception('Nome campo non supportato. Valori ammessi: ' . implode(', ', $fieldsNames));
            }
        } else {
            $field = 'volume';
        }
        return $field;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}

function checkFilter(?array $request) : bool
{
    try {
        if (isset($request['full'])) {
            $full = htmlspecialchars(strip_tags($request['full']));
            
            if ($full === '0') {
                $filtered = true;
            } elseif ($full === '1') {
                $filtered = false;
            } else {
                throw new Exception('Valore parametro "full" non ammesso. Scegliere fra 0 e 1');
            }
        } else {
            $filtered = false;
        }
        $notFiltered = !$filtered;
        
        return $notFiltered;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function formatDate(string $date) : string
{
    try {
        $cleanDate = htmlspecialchars(strip_tags($date));

        $dateParts = explode('/', $cleanDate);
        
        if (count($dateParts) !== 3) {
            throw new Exception('Parametro data inserito nel formato errato. Formato richiesto "gg/mm/yyyy"');
        }

        $day = intval($dateParts[0]);
        $month = intval($dateParts[1]);
        $year = intval($dateParts[2]);

        if (checkdate($month, $day, $year)) {
            $formatDate = date('Y-m-d', mktime(0, 0, 0, $month, $day, $year));
            
            return $formatDate;
        } else {
            throw new Exception('Data inserita inesistente');
        }
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function formatDateTime(string $dateTime) : string
{
    try {
        $dateTimeParts = explode(' ', $dateTime);
        if (count($dateTimeParts) !== 2) {
            throw new Exception('Parametro data e ora inserito nel formato errato. Formato richiesto "gg/mm/yyyy hh:ii:ss"');
        }
        
        $dateString = formatDate($dateTimeParts[0]);
        
        $timeParts = explode(':', $dateTimeParts[1]);
        if (count($timeParts) !== 3) {
            throw new Exception('Parametro data e ora inserito nel formato errato. Formato ora richiesto "hh:ii:ss"');
        }

        $hours = intval($timeParts[0]);
        $minutes = intval($timeParts[1]);
        $seconds = intval($timeParts[2]);
        
        if ($hours < 0 || $hours > 24 || $minutes < 0 || $minutes > 60 || $seconds < 0 || $seconds > 60) {
            throw new Exception('Ora inesistente');
        }
        
        $timeString = date('H:i:s', mktime($hours, $minutes, $seconds));
        
        $dateTimeString = $dateString . ' ' . $timeString;
        
        $formatDateTime = date('Y-m-d H:i:s', strtotime($dateTimeString));
        
        return $formatDateTime;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function checkInterval(?array $request) : array
{
    try {
        if (isset($request['datefrom']) && isset($request['dateto'])) {
            $dateFrom = formatDate($request['datefrom']);
            $dateTo = formatDate($request['dateto']);
        } elseif (isset($request['datefrom'])) {
            $dateFrom = formatDate($request['datefrom']);
            $dateTo = date('Y-m-d');
        } elseif (isset($request['dateto'])) {
            $dateFrom = formatDate($request['dateto']);
            $dateTo = date('Y-m-d', strtotime($dateFrom . ' +1 day'));
        } else {
            throw new Exception('Parametri intervallo date assenti. Indicare uno o entrambi i parametri "datefrom" e "dateto"');
        }
        $dateTimeFrom = new DateTime($dateFrom);
        $dateTimeTo = new DateTime($dateTo);

        $dates = ['datefrom' => $dateTimeFrom, 'dateto' => $dateTimeTo];

        return $dates;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function setDateTimes(array $request) : array
{
    try {
        if (count($request) !== 2 && !array_key_exists('datefrom', $request) && !array_key_exists('datefrom', $request)) {
            throw new Exception('Array non valido. Richieste due chiavi: "datefrom" e "dateto"');
        }
        $dateFrom = formatDateTime($request['datefrom']);
        $dateTo = formatDateTime($request['dateto']);
        
        $dateTimeFrom = new DateTime($dateFrom);
        $dateTimeTo = new DateTime($dateTo);

        $dates = ['datefrom' => $dateTimeFrom, 'dateto' => $dateTimeTo];

        return $dates;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function connect(string $dbName) //: resource
{
    $n = 2;
    $delay = 40000;
    $serverName = MSSQL_HOST;
    $connectionInfo = ['Database' => $dbName, 'UID' => MSSQL_USER, 'PWD' => MSSQL_PASSWORD];
    
    try {
        for ($i=1; $i <= $n; $i++) {
            $conn = sqlsrv_connect($serverName, $connectionInfo);

            if ($conn) {
                break;
            } else {
                usleep($delay);
            }
        }
        if ($conn) {
            return $conn;
        } else {
            throw new Exception(error());
        }
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function query($conn, string $fileName, array $paramValues)
{
    try {
        include __DIR__ . '/include/query/' . $fileName . '.php';

        $query = str_replace($paramNames, $paramValues, $queryString);

        $stmt = sqlsrv_query($conn, $query);
        
        if ($stmt !== false) {
            return $stmt;
        } else {
            throw new Exception(error());
        }
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function fetch($stmt) : ?array
{
    try {
        $record = 0;
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            foreach ($row as $key => $value) {
                $dati[$record][$key] = $value;
            }
            $record++;
        }
        if (!isset($dati)) {
            $dati = [];
        }
        return $dati;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function close($conn) : void
{
    try {
        if (!sqlsrv_close($conn)) {
            throw new Exception(error());
        }
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function error() : string
{
    $errors = sqlsrv_errors();
    $text = '<pre>' . print_r($errors, true) . '</pre>';
    
    return $text;
}


function addMedia(array $dati, string $nomeCampo) : array
{
    try {
        $medie = [];
        foreach ($dati as $record => $campi) {
            if (!array_key_exists($nomeCampo, $campi)) {
                throw new Exception('Campo inesistente. Impossibile eseguirne la media');
            }
            
            foreach ($campi as $campo => $valore) {
                $medie[$record][$campo] = $valore;

                if ($campo === $nomeCampo) {
                    if ($record === 0) {
                        $media = $valore;
                    } else {
                        $media = ($valore + $medie[$record - 1][$campo])/2;
                    }
                    $medie[$record]['media'] = $media;
                }
            }
        }
        return $medie;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function addDelta(array $dati, string $nomeCampo) : array
{
    try {
        $delta = [];
        foreach ($dati as $record => $campi) {
            if (!array_key_exists($nomeCampo, $campi)) {
                throw new Exception('Campo inesistente. Impossibile calcolare il delta temporale');
            }
            
            foreach ($campi as $campo => $valore) {
                $delta[$record][$campo] = $valore;

                if ($campo === $nomeCampo) {
                    if (!is_a($valore, 'DateTime')) {
                        throw new Exception('Per calcolare il delta temporale è neccessario che gli elementi del campo scelto siano del tipo \'DateTime\'');
                    }
                    
                    if ($record === 0) {
                        $deltaT = 0;
                    } else {
                        $timestamp0 = $delta[$record - 1][$campo]->getTimestamp();
                        $timestamp1 = $valore->getTimestamp();
                        $deltaT = $timestamp1 - $timestamp0;
                    }
                    $delta[$record]['delta'] = $deltaT;
                }
            }
        }
        return $delta;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function initVolumi(array $variabili, array $dati) : array
{
    try {
        if (count($dati) === 0) {
            throw new Exception('Nessun dato presente per le date selezionate');
        }
        
        $volumi = [];
        foreach ($dati as $record => $campi) {
            $volumi[$record]['variabile'] = $variabili['id_variabile'];

            foreach ($campi as $campo => $valore) {
                if ($campo === 'data_e_ora') {
                    $volumi[$record][$campo] = $valore;
                }
            }

            $volumi[$record]['tipo_dato'] = 1;
        }
        return $volumi;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function addLivello(array $volumi, array $dati) : array
{
    try {
        $livelli = [];
        if (count($volumi) !== count($dati)) {
            throw new Exception('Array differenti');
        }
        foreach ($volumi as $record => $campi) {
            foreach ($campi as $campo => $valore) {
                $livelli[$record][$campo] = $valore;
                
                if ($campo === 'data_e_ora') {
                    $dato = $dati[$record][$campo];
                    if ($valore->format('d/m/Y H:i:s') === $dato->format('d/m/Y H:i:s')) {
                        $livelli[$record]['livello'] = $dati[$record]['valore'];
                    } else {
                        throw new Exception('Date differenti');
                    }
                }
            }
        }
        return $livelli;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function addAltezza(array $dati, ?float $quota, string $nomeCampo) : array
{
    try {
        if (!isset($quota)) {
            throw new Exception('Quota non definita');
        }
        
        $altezze = [];
        foreach ($dati as $record => $campi) {
            if (!array_key_exists($nomeCampo, $campi)) {
                throw new Exception('Campo inesistente. Impossibile calcolare l\'altezza');
            }
            
            foreach ($campi as $campo => $valore) {
                $altezze[$record][$campo] = $valore;

                if ($campo === $nomeCampo) {
                    $altezze[$record]['altezza'] = $dati[$record][$campo] - $quota;
                }
            }
        }
        return $altezze;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function addPortata(array $dati, array $formule) : array
{
    try {
        if (count($formule) === 0) {
            throw new Exception('Formula scarico non definita');
        }
        $tipo = $formule['tipo_formula'];
        switch ($tipo) {
            case 'portata sfiorante':
                $coefficienti = [
                    'mi' => $formule['mi'],
                    'larghezza' => $formule['larghezza'],
                    'limite' => $formule['limite']
                ];
                $nomi_campo = ['altezza'];
                break;
        }
        
        $portate = [];
        foreach ($dati as $record => $campi) {
            $parametri = [];
            foreach ($campi as $campo => $valore) {
                $portate[$record][$campo] = $valore;
                
                if (in_array($campo, $nomi_campo)) {
                    $parametri[$campo] = $dati[$record][$campo];
                }
            }
            $portate[$record]['portata'] = calcolaPortata($tipo, $coefficienti, $parametri);
        }
        return $portate;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function addVolume(array $dati) : array
{
    try {
        $volumi = [];
        foreach ($dati as $record => $campi) {
            if (!array_key_exists('portata', $campi) || !array_key_exists('delta', $campi)) {
                throw new Exception('Dati di portata e delta t assenti impossibile calcolare volume scaricato');
            }
            
            foreach ($campi as $campo => $valore) {
                $volumi[$record][$campo] = $valore;
            }
            $volumi[$record]['volume'] = $volumi[$record]['portata'] * $volumi[$record]['delta'];
        }
        return $volumi;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function setFile(string $variabile, array $dates, bool $filtered, string $field, string $path) : string
{
    try {
        if (!is_dir($path)) {
            throw new Exception('Directory inesistente');
        }
        
        $dateFrom = $dates['datefrom']->format('YmdHi');
        $dateTo = $dates['dateto']->format('YmdHi');
        
        $fileName = $path . '/' . ucfirst($field) . '_' . $variabile . '_' . $dateFrom . '_' . $dateTo;
        
        if ($filtered) {
            $fileName .= '_no0.csv';
        } else {
            $fileName .= '_full.csv';
        }
        
        return $fileName;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function format(array $dati, string $field) : array
{
    $nomiCampi = [
        'variabile' => 'variabile',
        'valore' => $field,
        'data_e_ora' => 'data_e_ora',
        'tipo_dato' => 'tipo_dato'
    ];
    
    try {
        $formatted = [];
        foreach ($dati as $record => $campi) {
            if (!array_key_exists($field, $campi)) {
                throw new Exception('Campo inesistente. Impossibile assegnarlo a \'valore\'');
            }
            
            foreach ($campi as $campo => $valore) {
                if (in_array($campo, $nomiCampi)) {
                    foreach ($nomiCampi as $nuovo => $vecchio) {
                        if ($campo === $vecchio) {
                            if (is_a($valore, 'DateTime')) {
                                $formatted[$record][$nuovo] = $valore->format('d/m/Y H:i:s');
                            } elseif (is_numeric($valore)) {
                                if (is_int($valore)) {
                                    $formatted[$record][$nuovo] = number_format($valore, 0, '', '');
                                } elseif (is_float($valore)) {
                                    $strFloat = number_format($valore, 3, ',', '');
                                    if ($strFloat === '0,000') {
                                        $strFloat = '0';
                                    }
                                    $formatted[$record][$nuovo] = $strFloat;
                                }
                            } else {
                                $formatted[$record][$nuovo] = $valore;
                            }
                            break;
                        }
                    }
                }
            }
        }
        return $formatted;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function changeTimeZone(string $dateIn, bool $isLocalToUTC, bool $format)
{
    try {
        if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (?:2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/", $dateIn)) {
            throw new Exception('Inserire la data nel formato Y-m-d H:i:s');
        }
        
        if ($isLocalToUTC) {
            $zoneIn = 'Europe/Rome';
            $zoneOut = 'Etc/GMT-1';
        } else {
            $zoneIn = 'Etc/GMT-1';
            $zoneOut = 'Europe/Rome';
        }

        $dateTime = new DateTime($dateIn, new DateTimeZone($zoneIn));
        $dateTime->setTimezone(new DateTimeZone($zoneOut));

        if ($format) {
            $dateOut = $dateTime->format('d/m/Y H:i:s');
        } else {
            $dateOut = $dateTime;
        }
        return $dateOut;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function datesToString(array $dates, string $format) : array
{
    try {
        foreach ($dates as $key => $date) {
            if (is_a($date, 'DateTime')) {
                $formattedDates[$key] = $date->format($format);
                
                if (!DateTime::createFromFormat($format, $formattedDates[$key])) {
                    throw new Exception('Formato data scelto non valido. Utilizzare "d/m/Y H:i:s" o "Y-m-d H:i:s"');
                }
            } else {
                $formattedDates[$key] = $date;
            }
        }
        return $formattedDates;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function checkDates(string $db, array $dates, bool $isLocalToUTC) : array
{
    try {
        $checkedDates = [];
        foreach ($dates as $key => $date) {
            if (is_a($date, 'DateTime') && ($db === 'SPT')) {
                $dateString = $date->format('Y-m-d H:i:s');
                $checkedDates[$key] = changeTimeZone($dateString, $isLocalToUTC, false);
            } else {
                $checkedDates[$key] = $date;
            }
        }
        return $checkedDates;
        // @codeCoverageIgnoreStart
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
    // @codeCoverageIgnoreEnd
}


function setToLocal(string $db, array $dati) : array
{
    try {
        $locals = [];
        foreach ($dati as $record => $campi) {
            $locals[$record] = checkDates($db, $campi, false);
        }
        return $locals;
        // @codeCoverageIgnoreStart
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
    // @codeCoverageIgnoreEnd
}

function checkNull($value)
{
    try {
        if (is_null($value)) {
            throw new Exception('Valore parametro non valido o nullo');
        } else {
            $res = $value;
        }
        return $res;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}

function getDataFromDb(string $db, string $queryFileName, array $parametri) : array
{
    try {
        $data = [];
        
        $conn = connect($db);
        
        $checkedParams = array_map('checkNull', $parametri);
        
        $checkedDateParams = checkDates($db, $checkedParams, true);
        
        $params = datesToString($checkedDateParams, 'd/m/Y H:i:s');
        
        $stmt = query($conn, $queryFileName, $params);
        
        $rawData = fetch($stmt);
        
        $data = setToLocal($db, $rawData);
        
        close($conn);
        
        return $data;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function printToCSV(array $dati, string $fileName) : void
{
    $mode = 'w';
    $delimiter = ';';
    
    try {
        $handle = @fopen($fileName, $mode);
        
        if ($handle) {
            $nomiCampi = array_keys($dati[0]);

            fputcsv($handle, $nomiCampi, $delimiter);

            foreach ($dati as $valori) {
                fputcsv($handle, $valori, $delimiter);
            }

            fclose($handle);
        } else {
            throw new Exception('Problemi con l\'apertura del file CSV');
        }
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function divideAndPrint(array $data, bool $full, string $field, ?int $limit = null) : bool
{
    $limit = $limit ?? MAXRECORD;
    $filtered = !$full;
    
    try {
        if ($limit <= 0) {
            throw new Exception('Impostare un numero massimo di record da esportare almeno uguale ad 1');
        }
        if (count($data) === 0) {
            $printed = false;
        } else {
            $i = 0;
            $printableData = [];
            foreach ($data as $record) {
                if ($i === $limit) {
                    printPart($printableData, $i, $filtered, $field);
                    $i = 0;
                    $printableData = [];
                }
                $printableData[$i] = $record;
                $i++;
            }
            if ($i > 0) {
                printPart($printableData, $i, $filtered, $field);
            }
            $printed = true;
        }
        return $printed;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function printPart(array $printableData, int $i, bool $filtered, string $field) : void
{
    try {
        $max = $i - 1;
        if (!array_key_exists($max, $printableData)) {
            throw new Exception('Indice array in stampa non definito');
        }
        
        $variabile = $printableData[0]['variabile'];
        $dates = [
            'datefrom' => $printableData[0]['data_e_ora'],
            'dateto' => $printableData[$max]['data_e_ora']
        ];
        $dateTimes = setDateTimes($dates);
        $fileName = setFile($variabile, $dateTimes, $filtered, $field, CSV);
        printToCSV($printableData, $fileName);
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function filter(array $dati, bool $full) : array
{
    try {
        if ($full) {
            $filteredData = $dati;
        } else {
            $filteredData = [];
            foreach ($dati as $record => $campi) {
                if (!array_key_exists('valore', $campi)) {
                    throw new Exception('Campo "valore" su cui eseguire il filtro non presente');
                }
                
                $flag = true;
                foreach ($campi as $campo => $valore) {
                    if (($campo === 'valore') && ($valore === '0')) {
                        $flag = false;
                    }
                }
                if ($flag) {
                    $filteredData[$record] = $campi;
                }
            }
        }
        return $filteredData;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}
    
function response(array $request, bool $printed) : string
{
    try {
        $keys = array_flip(['var', 'datefrom', 'dateto', 'full', 'field']);
        
        if (array_diff_key($request, $keys) || array_diff_key($keys, $request)) {
            throw new Exception('Parametri mancanti');
        }
        
        $type = $request['full'] ? 'full' : 'senza zeri';
        
        $html = 'Elaborazione dati <b>' . ucfirst($request['field']) . '</b> variabile <b>' . $request['var'] . '</b> dal <b>' . $request['datefrom']->format('d/m/Y') . '</b> al <b>' . $request['dateto']->format('d/m/Y') . '</b> avvenuta con successo.';
        
        if ($printed) {
            $html .= ' File CSV <b>' . $type . '</b> esportati.';
        } else {
            $html .= ' Nessun file CSV <b>' . $type . '</b> esportato per mancanza di dati.';
        }
        return $html;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}

function calcolaPortata(string $tipo, array $coefficienti, array $parametri) : float
{
    try {
        switch ($tipo) {
            case 'portata sfiorante':
                $altezza_sfioro = $parametri['altezza'];
                $mi = $coefficienti['mi'];
                $larghezza_soglia = $coefficienti['larghezza'];

                if ($altezza_sfioro > 0) {
                    $portata = $mi * $larghezza_soglia * $altezza_sfioro * sqrt(2 * 9.81 * $altezza_sfioro);
                } else {
                    $portata = 0;
                }
                break;
            default:
                throw new Exception('Tipologia di portata non definita');
                break;
        }
        return ($portata <= $coefficienti['limite']) ? $portata : 0;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function uniformaCategorie(array $dati_acquisiti) : array
{
    try {
        if (count($dati_acquisiti) === 0) {
            throw new Exception('Nessuna categoria definita');
        }
        
        $uniformati = [];
        foreach ($dati_acquisiti as $categoria => $dati) {
            $dati_target = [];
            $dati_confronto = [];
            
            $dati_target[$categoria] = $dati;
            $dati_confronto = array_diff_key($dati_acquisiti, $dati_target);

            $uniformati[$categoria] = integraDate($dati, $dati_confronto);
        }
        return $uniformati;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function integraDate(array $targets, array $checkers) : array
{
    try {
        if (count($targets) === 0) {
            throw new Exception('Nessun dato presente nel target, modificare date');
        }
        foreach ($checkers as $categoria => $dati) {
            foreach ($dati as $j => $dato) {
                $iMax = count($targets) - 1;
                
                $prototype = [
                    $iMax => [
                        'variabile' => $targets[$iMax]['variabile'],
                        'valore' => null,
                        'unita_misura' => $targets[$iMax]['unita_misura'],
                        'data_e_ora' => $dato['data_e_ora'],
                        'tipo_dato' => $targets[$iMax]['tipo_dato']
                    ]
                ];
                
                if ($dato['data_e_ora'] < $targets[0]['data_e_ora']) {
                    array_splice($targets, 0, 0, $prototype);
                } elseif ($dato['data_e_ora'] > $targets[$iMax]['data_e_ora']) {
                    array_splice($targets, count($targets), 0, $prototype);
                } else {
                    for ($i = 1; $i <= $iMax; $i++) {
                        if ($dato['data_e_ora'] > $targets[$i - 1]['data_e_ora'] && $dato['data_e_ora'] < $targets[$i]['data_e_ora']) {
                            array_splice($targets, $i, 0, $prototype);
                            break;
                        }
                    }
                }
            }
        }
        return $targets;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}
    
    
function completaDati(array $dati_uniformi) : array
{
    try {
        if (count($dati_uniformi) === 0) {
            throw new Exception('Nessuna categoria definita');
        }
        
        $completi = [];
        foreach ($dati_uniformi as $categoria => $dati) {
            $completi[$categoria] = riempiCode($dati);
            if ($categoria === 'manovra') {
                $completi[$categoria] = riempiNull($completi[$categoria]);
            } else {
//                $completi[$categoria] = interpolaNull($completi[$categoria]);
            }
        }
        return $completi;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function riempiCode(array $dati) : array
{
    try {
        $boundaries = [];
        $capi = trovaCapi($dati);

        foreach ($dati as $record => $campi) {
            foreach ($campi as $key => $valore) {
                if ($key === 'valore') {
                    if ($record < $capi['testa']['id']) {
                        $boundaries[$record][$key] = $capi['testa']['valore'];
                    } elseif ($record > $capi['coda']['id']) {
                        $boundaries[$record][$key] = $capi['coda']['valore'];
                    } else {
                        $boundaries[$record][$key] = $valore;
                    }
                } else {
                    $boundaries[$record][$key] = $valore;
                }
            }
        }
        return $boundaries;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function trovaCapi(array $dati) : array
{
    try {
        $capi = [];
        $iMax = count($dati) - 1;
        if (isset($dati[0]['valore']) && isset($dati[$iMax]['valore'])) {
            $testa = [
                'id' => 0,
                'valore' => $dati[0]['valore']
            ];
            $coda = [
                'id' => $iMax,
                'valore' => $dati[$iMax]['valore']
            ];
        } else {
            foreach ($dati as $record => $campi) {
                foreach ($campi as $key => $valore) {
                    if ($key === 'valore' && isset($valore)) {
                        if (!isset($testa)) {
                            $testa = [
                                'id' => $record,
                                'valore' => $valore
                            ];
                        }
                        $coda = [
                            'id' => $record,
                            'valore' => $valore
                        ];
                    }
                }
            }
        }
        if (isset($testa) && isset($coda)) {
            $capi = [
                'testa' => $testa,
                'coda' => $coda
            ];
        } else {
            throw new Exception('Nessun valore di riferimento trovato');
        }
        return $capi;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}


function riempiNull(array $dati) : array
{
    try {
        $pieni = [];
        
        foreach ($dati as $record => $campi) {
            foreach ($campi as $key => $valore) {
                if ($key === 'valore') {
                    if (isset($valore)) {
                        $pieni[$record][$key] = $valore;
                        $prev = $valore;
                    } else {
                        if (isset($prev)) {
                            $pieni[$record][$key] = $prev;
                        } else {
                            throw new Exception('Nessun valore di riferimento trovato');
                        }
                    }
                } else {
                    $pieni[$record][$key] = $valore;
                }
            }
        }
        return $pieni;
    } catch (Throwable $e) {
        printErrorInfo(__FUNCTION__);
        throw $e;
    }
}
