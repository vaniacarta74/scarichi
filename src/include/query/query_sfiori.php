<?php

$queryString = "SELECT
                    scarico,
                    mi,
                    larghezza,
                    quota,
                    max_portata AS limite
                FROM
                    sfiori
                WHERE
                    scarico = ?scarico?";

$paramNames = [
    '?scarico?'
];
