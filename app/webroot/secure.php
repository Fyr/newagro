<?
$nowDate = date('d/M/Y:H:i:s +0200');
$nowTime = microtime(true);

$url = strtolower($_SERVER['REQUEST_URI']);
if (strpos($url, '?') !== false) {
    list($url) = explode('?', $url);
}

$httpMethod = $_SERVER['REQUEST_METHOD'];
$allowedMethods = array('GET', 'POST', 'PUT');
if (!in_array($httpMethod, $allowedMethods)) {
    handleCrit($_SERVER['REMOTE_ADDR'], "Incorrect HTTP method: '$httpMethod'");
}

if ($stopLine = checkStopLine($url)) {
    handleCrit($_SERVER['REMOTE_ADDR'], "Stop Line: '$stopLine'");
}
if ($stopWord = checkStopWord($url)) {
    handleCrit($_SERVER['REMOTE_ADDR'], "Stop Word: '$stopWord'");
}

list($level, $reason) = checkDotURL($url);
if ($level) {
    if ($level == 'CRIT') {
        handleCrit($_SERVER['REMOTE_ADDR'], "$reason (dot)");
    } else {
        handleWarn($_SERVER['REMOTE_ADDR'], "$reason (dot)");
    }
}

// $timeConsumed = round(microtime(true) - $curTime, 3);
// exit("timeConsumed: $timeConsumed s");

function checkDotURL($url) {
    $allowed = array('', '');
    if (strpos($url, '.') === false) {
        return $allowed;
    }
    $pathInfo = pathinfo($url);

    $dirname = $pathInfo['dirname'];
    if (strpos($dirname, '.') !== false) {
        return array('CRIT', "Found dangerous folder '$dirname'");
    }

    $ext = strtolower($pathInfo['extension']);
    $aWhiteList = array('html', 'css', 'js', 'ico', 'txt', 'jpg', 'jpeg', 'jfif', 'png', 'gif', 'pdf', 'csv');
    if (in_array($ext, $aWhiteList)) {
        return $allowed;
    }

    $aBlackList = array('php', 'sql', 'xsd');
    if (in_array($ext, $aBlackList)) {
        return array('CRIT', 'Found black-listed *.'.$ext);
    }

    // check *.xml - only sitemap*.xml is allowed
    if ($ext === 'xml' && strpos($pathInfo['filename'], 'sitemap') !== 0) {
        return array('WARN', 'Found non-sitemap *.XML');
    }

    // json-s - needs to be checked
    if ($ext === 'json') {
        // skip our own ajax calls
        $aWhiteListJson = array(
            '/media/ajax',
            '/adminajax'
        );
        foreach($aWhiteListJson as $skip) {
            if (startsWith($url, $skip)) {
                return $allowed;
            }
        }
        return array('WARN', 'Found *.JSON that needs to be checked');
    }

    // TODO: refactor to respect cakePHP's named params
    // for ex. /AdminProducts/index/Product/limit:100/Product.detail_num:~04207119
    $aWhiteListExt = array(
        '.detail_num:',
        '.id:'
    );
    foreach($aWhiteListExt as $skip) {
        if (startsWith('.'.$ext, $skip)) {
            return $allowed;
        }
    }

    return array('WARN', 'Found suspicious file extension *.'.$ext);
}

function startsWith($str, $key) {
    return strpos($str, $key) === 0;
}

function checkStopLine($url) {
    $aStopLines = array(
        '/api',
        '/docs',
        '/feed'
    );
    foreach($aStopLines as $stopLine) {
        if (startsWith($url, $stopLine)) {
            return $stopLine;
        }
    }
    return '';
}

function checkStopWord($url) {
    $aStopWords = array(
        'config',
        'xmlrpc',
        'backup',
        'package',
        'wordpress',
        '/wp-',
        '/wp/',
    );
    foreach($aStopWords as $stopWord) {
        if (strpos($url, $stopWord) !== false) {
            return $stopWord;
        }
    }
    return '';
}

function handleCrit($ip, $reason) {
    file_put_contents($ip, 'secure-crit-ip.log', FILE_APPEND);
    logIssue("CRIT! {$ip} - {$reason}");
    exit('Service is temporary unavailable');
}

function handleWarn($ip, $reason) {
    logIssue("WARN! {$ip} - {$reason}");
}

function logIssue($issue, $logFile = 'secure.log', $lAppend = true) {
    global $nowTime, $nowDate;
    $timeConsumed = round(microtime(true) - $nowTime, 3);
	file_put_contents($logFile, "{$_SERVER['REMOTE_ADDR']} - - [{$nowDate}] {$issue} URL: {$_SERVER['REQUEST_URI']} ({$timeConsumed} s)\r\n", ($lAppend) ? FILE_APPEND : null);
}
