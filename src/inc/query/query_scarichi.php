<?php
namespace vaniacarta74\Scarichi\inc\query;

$queryString = "SELECT
                    scarichi.id_scarico AS scarico,
                    scarichi.denominazione,
                    scarichi.variabile,
                    tipi_scarico.tipo_scarico AS tipo,
                    tipi_scarico.modello_calcolo AS modello
                FROM
                    scarichi                    
                    INNER JOIN
                        tipi_scarico
                    ON
                        scarichi.tipo_scarico = tipi_scarico.id_tipo_scarico                    
                WHERE
                    scarichi.variabile = ?variabile?";

$paramNames = [
    '?variabile?'
];
