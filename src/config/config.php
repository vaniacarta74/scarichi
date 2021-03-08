<?php
namespace vaniacarta74\Scarichi\config;

use vaniacarta74\Scarichi\Utility;

$now = new \DateTime('NOW', new \DateTimeZone('Europe/Rome'));
define('START', $now->format('Y-m-d H:i:s.u'));

define('COMPOSER', Utility::getJsonArray(__DIR__ . '/../../composer.json'));
define('CONFIG', Utility::getJsonArray(__DIR__ . '/config.json'));

define('LOCALHOST', CONFIG['define']['system']['localhost']);

define('PERIOD', CONFIG['command']['period']);
define('OFFSET', CONFIG['command']['offset']);
define('ASYNC', boolval(CONFIG['command']['async']));
define('URL', 'http://' . LOCALHOST . '/' . CONFIG['command']['url']);

define('NODATA', intval(CONFIG['define']['csv']['nodata']));
define('MAXRECORD', intval(CONFIG['define']['csv']['maxrecord']));
define('MAKESUBDIR', boolval(CONFIG['define']['csv']['subdirectory']));

define('TELEGRAM', boolval(CONFIG['define']['telegram']['send']));
define('ADMITTEDTAGS', CONFIG['define']['telegram']['admittedTags']);
define('TAGLIMIT', intval(CONFIG['define']['telegram']['tagLimit']));
define('MSGLIMIT', intval(CONFIG['define']['telegram']['limit']));
define('BOTPATH', __DIR__ . CONFIG['define']['telegram']['path']);
define('BOTURL', 'http://' . LOCALHOST . '/' . CONFIG['define']['telegram']['url']);

define('NITER', intval(CONFIG['define']['watchdog']['iterations']));
define('DELAY', intval(CONFIG['define']['watchdog']['delay']));

define('DEBUG_LEVEL', intval(CONFIG['define']['log']['debug_level']));
define('LOG_PATH', __DIR__ . CONFIG['define']['log']['log_path']);
define('ERROR_LOG', CONFIG['define']['log']['error_log']);

define('TIMEOUT', intval(CONFIG['define']['system']['timeout']));

ini_set('memory_limit', CONFIG['define']['system']['memory_limit']);
ini_set('max_execution_time', TIMEOUT);
