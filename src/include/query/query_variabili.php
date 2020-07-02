<?php

$queryString = "SELECT
                    id_variabile,
                    impianto,
                    unita_misura
                FROM
                    variabili
                WHERE
                    id_variabile = ?variabile?";

$paramNames = array(
    '?variabile?'
);

