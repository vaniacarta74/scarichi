<?php

require_once(__DIR__ . '/tools.php');

if (isset($_REQUEST['var'])) {
    
    $conn = connect('dbcore');
    
    $var = htmlspecialchars(strip_tags($_REQUEST['var']));
    
    $query = 'SELECT
                    scarichi.impianto,
                    scarichi.variabile AS variabile_scarico,
                    scarichi.denominazione,
                    db.db_name AS db,
                    variabili_scarichi.variabile,
                    categorie.categoria,
                    variabili_scarichi.tipo_dato
                FROM
                    variabili_scarichi
                    INNER JOIN
                        db
                    ON
                        variabili_scarichi.db = db.id_db
                    INNER JOIN
                        categorie
                    ON
                        variabili_scarichi.categoria = categorie.id_categoria
                    INNER JOIN
                        scarichi
                    ON
                        variabili_scarichi.scarico = scarichi.id_scarico
                    INNER JOIN
                        tipi_scarico
                    ON
                        scarichi.tipo_scarico = tipi_scarico.id_tipo_scarico
                WHERE
                    scarichi.variabile = ' . $var;
    
    $stmt = sqlsrv_query($conn, $query);
    
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