<?php
namespace vaniacarta74\scarichi\src\config\inc\query;

$queryString = "SELECT
                    id_variabile,
                    impianto,
                    unita_misura
                FROM
                    variabili
                WHERE
                    id_variabile = ?variabile?";

$paramNames = [
    '?variabile?'
];
