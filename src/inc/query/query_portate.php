<?php
namespace vaniacarta74\Scarichi\inc\query;

$queryString = "SELECT
                    portata
                FROM
                    portate
                WHERE
                    portate.scarico = ?scarico? AND
                    portate.quota = ?quota? AND
                    portate.livello = ?livello?";

$paramNames = [
    '?scarico?',
    '?quota?',
    '?livello?'
];
