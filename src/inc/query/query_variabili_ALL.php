<?php
namespace vaniacarta74\Scarichi\inc\query;

$queryString = "SELECT
                    id_variabile
                FROM
                    variabili
                WHERE
                    id_variabile >= 30000 AND
                    id_variabile <= 39999 AND
                    unita_misura = 'mc'";
                    //unita_misura = 'm3'";

$paramNames = [];
