<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 *
 * @author vania
 */
namespace vaniacarta74\scarichi\tests;

// TODO: check include path
ini_set('include_path', '/var/www/html/include');

// put your code here
$_REQUEST['var'] = '30030';
$_REQUEST['datefrom'] = '01/05/2020';
$_REQUEST['full'] = 1;
$_SERVER['SERVER_NAME'] = 'localhost';

require __DIR__ . '/../vendor/autoload.php';
