<?php

require_once(__DIR__ . '/tools.php');

if (isset($_REQUEST['var'])) {
    
    $conn = connect('dbcore');
    
    $paramValues = array(htmlspecialchars(strip_tags($_REQUEST['var'])));
    
    $stmt = query($conn, 'query_variabili_correlate_dbcore', $paramValues);
    
    if ($stmt !== false) {
        
        $i = 0;
        while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $id_impianto = $row['impianto'];
            $variabile_scarico = $row['variabile_scarico'];
            $nome_scarico = $row['denominazione'];
            $db[$i] = $row['db'];
            $variabile[$i] = $row['variabile'];
            $categoria[$i] = $row['categoria'];
            $tipo_dato[$i] = $row['tipo_dato'];
            $i++;
        }

        var_dump($id_impianto);
        var_dump($variabile_scarico);
        var_dump($nome_scarico);
        var_dump($variabile);
        var_dump($categoria);
        var_dump($tipo_dato);
        
    } else {
        die(print_r(sqlsrv_errors(), true));
    }
    
} else {
    echo 'Richiesta variabile';
}