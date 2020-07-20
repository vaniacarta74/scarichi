<?php

$queryString = "SELECT
                    rel_scarichi_variabili.scarico,
                    db.db_name AS db,
                    variabili_scarichi.variabile,
                    variabili_scarichi.tipo_dato,
                    variabili_scarichi.data_attivazione,
                    variabili_scarichi.data_disattivazione,
                    categorie.categoria    
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
                        rel_scarichi_variabili
                    ON
                        rel_scarichi_variabili.variabile = variabili_scarichi.id_variabile_scarico
                WHERE
                    rel_scarichi_variabili.scarico = ?scarico?
                ORDER BY
                    variabili_scarichi.categoria,
                    variabili_scarichi.data_attivazione";

$paramNames = [
    '?scarico?'
];
