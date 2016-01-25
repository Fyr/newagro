<?php
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));
Cache::config('default', array(
	'engine' => 'File', //[required]
	'duration' => '+999', //[optional]
	'prefix' => 'app_', //[optional]  prefix every cache file with this string
	'lock' => false, //[optional]  use file locking
	'serialize' => true, //[optional]
));

App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'File',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'File',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));
Configure::write('Exception.renderer', 'SiteExceptionRenderer');
Configure::write('Config.language', 'rus');

// define('PATH_FILES_UPLOAD', $_SERVER['DOCUMENT_ROOT'].'/files/');
Configure::write('media', array(
	'path' => $_SERVER['DOCUMENT_ROOT'].'/files/',
	'path2' => 'D:/Projects/vitacars.dev/wwwroot/app/webroot/files/'
));

Configure::write('params.motor', 6);
Configure::write('params.price_by', 47);
Configure::write('params.price_ru', 48);
Configure::write('params.price2_ru', 31);
Configure::write('params.markaTS', 33);
Configure::write('params.motorsTS', 34);
Configure::write('params.dopInfa', 9);

// Values from google recaptcha account
define('RECAPTCHA_PUBLIC_KEY', '6Lezy-QSAAAAAJ_mJK5OTDYAvPEhU_l-EoBN7rxV');
define('RECAPTCHA_PRIVATE_KEY', '6Lezy-QSAAAAACCM1hh6ceRr445OYU_D_uA79UFZ');

Configure::write('Recaptcha.publicKey', RECAPTCHA_PUBLIC_KEY);
Configure::write('Recaptcha.privateKey', RECAPTCHA_PRIVATE_KEY);

Configure::write('domain', array(
	'url' => 'newagro.dev',
	'title' => 'NewAgro.dev',
	'zone' => 'by'
));

Configure::write('search', array(
	'stopWords' => __('search.stopWords')
));

define('AUTH_ERROR', __('Invalid username or password, try again'));
define('TEST_ENV', $_SERVER['SERVER_ADDR'] == '192.168.1.22');

define('EMAIL_ADMIN', 'fyr.work@gmail.com');
define('EMAIL_ADMIN_CC', 'fyr.work@gmail.com');

CakePlugin::loadAll();

function fdebug($data, $logFile = 'tmp.log', $lAppend = true) {
		file_put_contents($logFile, mb_convert_encoding(print_r($data, true), 'cp1251', 'utf8'), ($lAppend) ? FILE_APPEND : null);
}

function assertTrue($msg, $result) {
	if ($result) {
		echo $msg.' - OK<br>';
	} else {
		$result = var_export($result, true);
		echo "{$msg} - ERROR! <br>Result: <b>`{$result}`</b><br>Must be: `true`<br>";
	}
}

function assertEqual($msg, $sample, $result) {
	if ($sample === $result) {
		echo $msg.' - OK<br>';
	} else {
		$result = var_export($result, true);
		$sample = var_export($sample, true);
		echo "{$msg} - ERROR! <br>Result: <b>`{$result}`</b><br>Must be: `{$sample}`<br>";
	}
}