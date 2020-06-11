<?php

$queryString = "SELECT
                    variabili_scarichi.scarico,
                    db.db_name AS db,
                    variabili_scarichi.variabile,
                    variabili_scarichi.tipo_dato,
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
                WHERE
                    variabili_scarichi.scarico = ?scarico?";

$paramNames = array('?scarico?');

