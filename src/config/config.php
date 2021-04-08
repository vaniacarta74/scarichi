<?php
namespace vaniacarta74\Scarichi\config;

use vaniacarta74\Scarichi\Utility;

$now = new \DateTime('NOW', new \DateTimeZone('Europe/Rome'));
define('START', $now->format('Y-m-d H:i:s.u'));

define('COMPOSER', Utility::getJsonArray(__DIR__ . '/../../composer.json'));
define('CONFIG', Utility::getJsonArray(__DIR__ . '/config.json'));

define('OFFSET', CONFIG['command']['offset']);

define('SERVICES', CONFIG['define']['services']);
define('ASYNC', boolval(SERVICES['tocsv']['isAsync']));
define('TOCSVURL', 'http://' . constant(SERVICES['tocsv']['host']) . '/' . SERVICES['tocsv']['path']);
define('TELRESTURL', 'http://' . constant(SERVICES['telegram_REST']['host']) . '/' . SERVICES['telegram_REST']['path']);

define('NODATA', intval(CONFIG['define']['csv']['nodata']));
define('MAXRECORD', intval(CONFIG['define']['csv']['maxrecord']));
define('MAKESUBDIR', boolval(CONFIG['define']['csv']['subdirectory']));

define('TELSCARICHI', CONFIG['define']['telegram']['scarichi']);
define('ADMITTEDTAGS', CONFIG['define']['telegram']['admittedTags']);
define('TAGLIMIT', intval(CONFIG['define']['telegram']['tagLimit']));
define('MSGLIMIT', intval(CONFIG['define']['telegram']['limit']));
define('BOTPATH', __DIR__ . CONFIG['define']['telegram']['path']);
define('BOTURL', 'http://' . LOCALHOST . '/' . CONFIG['define']['telegram']['url']);

define('NITER', intval(CONFIG['define']['watchdog']['iterations']));
define('DELAY', intval(CONFIG['define']['watchdog']['delay']));
define('LOG_PATH', __DIR__ . CONFIG['define']['log']['log_path']);
define('ERROR_LOG', CONFIG['define']['log']['error_log']);

define('TIMEOUT', intval(CONFIG['define']['system']['timeout']));

if (PRODUCTION) {
    define('DEBUG_LEVEL', intval(CONFIG['define']['log']['debug_level']['prod']));
    define('TELEGRAM', boolval(CONFIG['define']['telegram']['send']['prod']));
    define('GLOBALMSG', boolval(CONFIG['define']['telegram']['global']['prod']));
    define('PERIOD', CONFIG['command']['period']['prod']);
} else {
    define('DEBUG_LEVEL', intval(CONFIG['define']['log']['debug_level']['dev']));
    define('TELEGRAM', boolval(CONFIG['define']['telegram']['send']['dev']));
    define('GLOBALMSG', boolval(CONFIG['define']['telegram']['global']['dev']));
    define('PERIOD', CONFIG['command']['period']['dev']);
}
define('MODE', boolval(!GLOBALMSG && TELEGRAM));

ini_set('memory_limit', CONFIG['define']['system']['memory_limit']);
ini_set('max_execution_time', TIMEOUT);
