<?php
namespace vaniacarta74\scarichi\src\config\inc\query;

$queryString = "SELECT
                    tipi_formula.tipo_formula,
                    formule.scarico,
                    formule.mi,
                    formule.scabrosita,
                    formule.lunghezza,
                    formule.larghezza,
                    formule.altezza,
                    formule.angolo,
                    formule.raggio,
                    formule.quota,
                    formule.velocita,
                    formule.limite
                FROM
                    formule
                    INNER JOIN
                        tipi_formula
                    ON
                        formule.tipo_formula = tipi_formula.id_tipo_formula
                WHERE
                    scarico = ?scarico?";

$paramNames = [
    '?scarico?'
];
