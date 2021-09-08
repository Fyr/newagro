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

Configure::write('media', array(
	'path' => WWW_ROOT.'files'.DS,
	'path2' => WWW_ROOT.'../../../../vitacars.loc/public_html/app/webroot/files/'
));

Configure::write('params.motor', 6);
Configure::write('params.price_by', 47);
Configure::write('params.price2_by', 18);
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

$domain = explode('.', $_SERVER['HTTP_HOST']);

Configure::write('domain', array(
	'url' => 'agromotors.loc',
	'title' => 'AgroMotors.loc',
	'zone' => 'ru',
	'subdomain' => (count($domain) > 2) ? $domain[0] : 'www'
));

define('SUBDOMAIN_ALL', 0);
define('SUBDOMAIN_WWW', 1);

Configure::write('search', array(
	'stopWords' => __('search.stopWords')
));
Configure::write('sitemap', array(
	'cache' => true,
	'dir' => ROOT.DS.APP_DIR.DS.'tmp'.DS.'cache'.DS,
	'prefix' => 'sitemap_'
));
define('AUTH_ERROR', __('Invalid username or password, try again'));
define('TEST_ENV', $_SERVER['SERVER_ADDR'] == '127.0.0.1');

define('EMAIL_ADMIN', 'fyr.work@gmail.com');
define('EMAIL_ADMIN_CC', 'fyr.work@gmail.com');

CakePlugin::loadAll();

function fdebug($data, $logFile = 'tmp.log', $lAppend = true) {
	file_put_contents($logFile, mb_convert_encoding(print_r($data, true), 'cp1251', 'utf8'), ($lAppend) ? FILE_APPEND : null);
	return $data;
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