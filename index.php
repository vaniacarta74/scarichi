<?php

if (isset($_REQUEST['var'])) {
    
    require_once(__DIR__ . '/config/config_DBCORE.php');
    //echo $conn;
    
    $variabile = htmlspecialchars(strip_tags($_REQUEST['var']));
    //echo 'Variabile: ' . $variabile;
    
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
                    scarichi.variabile = ' . $variabile;
    
    $stmt = sqlsrv_query($conn, $query);
    
    if ($stmt !== false) {
        echo 'ok';
    } else {
        die(print_r(sqlsrv_errors(), true));
    }
    
} else {
    echo 'Richiesta variabile';
}

require_once(__DIR__ . '/config/config_DBCORE.php');

echo $connessione;

require_once(__DIR__ . '/config/config_SPT.php');

echo $connessione;

require_once(__DIR__ . '/config/config_SSCP_data.php');

echo $connessione;