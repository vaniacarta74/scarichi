<?php

$queryString = "SELECT
                    scarichi.id_scarico AS scarico,
                    scarichi.denominazione,
                    scarichi.impianto,
                    scarichi.variabile,
                    tipi_scarico.tipo_scarico AS tipo
                FROM
                    scarichi                    
                    INNER JOIN
                        tipi_scarico
                    ON
                        scarichi.tipo_scarico = tipi_scarico.id_tipo_scarico
                WHERE
                    scarichi.variabile = ?variabile?";

$paramNames = array('?variabile?');

