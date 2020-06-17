<?php

$queryString = "SELECT
                    dati_acquisiti.variabile,
                    dati_acquisiti.valore,
                    dati_acquisiti.data_e_ora,                    
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
                    dati_acquisiti.data_e_ora >= '?data_iniziale?' AND
                    dati_acquisiti.data_e_ora <= '?data_finale?' AND
                    dati_acquisiti.data_e_ora >= '?data_attivazione?' AND
                    dati_acquisiti.data_e_ora < '?data_disattivazione?'
                ORDER BY
                    dati_acquisiti.data_e_ora";

$paramNames = array(
    '?variabile?',
    '?tipo_dato?',
    '?data_iniziale?',
    '?data_finale?',
    '?data_attivazione?',
    '?data_disattivazione?'
);

