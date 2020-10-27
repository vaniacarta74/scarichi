<?php
namespace vaniacarta74\Scarichi\config;

define('NODATA', -9999);
define('TIMEOUT', 7200);
define('MAXRECORD', 10000);
define('DEBUG_LEVEL', 2);
define('MAKESUBDIR', true);
define('LOG_PATH', __DIR__ . '/../../tests/log');

ini_set('memory_limit', '2048M');
ini_set('max_execution_time', TIMEOUT);
