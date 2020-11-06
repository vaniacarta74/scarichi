<?php
namespace vaniacarta74\Scarichi\config;

use vaniacarta74\Scarichi\Utility;

$now = new \DateTime('NOW', new \DateTimeZone('Europe/Rome'));
define('START', $now->format('Y-m-d H:i:s.u'));

define('COMPOSER', Utility::getJsonArray(__DIR__ . '/../../composer.json'));
define('CONFIG', Utility::getJsonArray(__DIR__ . '/config.json'));

define('ASYNC', boolval(CONFIG['command']['async']));

define('NODATA', intval(CONFIG['define']['csv']['nodata']));
define('MAXRECORD', intval(CONFIG['define']['csv']['maxrecord']));
define('MAKESUBDIR', boolval(CONFIG['define']['csv']['subdirectory']));

define('DEBUG_LEVEL', intval(CONFIG['define']['log']['debug_level']));
define('LOG_PATH', __DIR__ . CONFIG['define']['log']['log_path']);
define('ERROR_LOG', CONFIG['define']['log']['error_log']);

define('TIMEOUT', intval(CONFIG['define']['system']['timeout']));

ini_set('memory_limit', CONFIG['define']['system']['memory_limit']);
ini_set('max_execution_time', TIMEOUT);
