<?php
namespace vaniacarta74\Scarichi\inc\query;

$queryString = "SELECT TOP 1
                    dati_acquisiti.variabile,
                    dati_acquisiti.valore,
                    variabili.unita_misura,
                    dati_acquisiti.tipo_dato
                FROM
                    variabili
                    INNER JOIN
                        dati_acquisiti
                    ON
                        dati_acquisiti.variabile = variabili.id_variabile
                WHERE
                    variabili.id_variabile = ?variabile? AND
                    dati_acquisiti.tipo_dato = ?tipo_dato? AND
                    dati_acquisiti.data_e_ora < '?data_e_ora?'
                ORDER BY
                    dati_acquisiti.data_e_ora DESC";

$paramNames = [
    '?variabile?',
    '?tipo_dato?',
    '?data_e_ora?'
];
