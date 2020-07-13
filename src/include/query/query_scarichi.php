<?php

$queryString = "SELECT
                    scarichi.id_scarico AS scarico,
                    scarichi.denominazione,
                    db.db_name AS db,
                    scarichi.variabile,
                    tipi_scarico.tipo_scarico AS tipo
                FROM
                    scarichi                    
                    INNER JOIN
                        tipi_scarico
                    ON
                        scarichi.tipo_scarico = tipi_scarico.id_tipo_scarico
                    INNER JOIN
                        db
                    ON
                        scarichi.db = db.id_db
                WHERE
                    scarichi.variabile = ?variabile?";

$paramNames = [
    '?variabile?'
];
