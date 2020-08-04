<?php
namespace vaniacarta74\scarichi\inc\query;

$queryString = "SELECT
                    scarico,
                    db,
                    variabile,
                    tipo_dato,
                    data_attivazione,
                    data_disattivazione,
                    categoria    
                FROM
                    variabili_scarichi                    
                WHERE
                    scarico = ?scarico?
                ORDER BY
                    categoria,
                    data_attivazione";

$paramNames = [
    '?scarico?'
];
