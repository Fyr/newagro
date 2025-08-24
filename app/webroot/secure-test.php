<pre>
<?
require_once('./secure.php');

class SecurityTest extends Security {
    private $log = array();

    protected function logIssue($ip, $level, $issue) {
        $this->log = array($ip, $level, $issue);
        parent::logIssue($ip, $level, $issue);
    }

    public function getLog() {
        return $this->log;
    }

    protected function blockIP($ip) {
        // log this ip that we block
        // file_put_contents(self::BAN_FILE, $ip."\r\n", FILE_APPEND);

        // ban this IP
        // system("/usr/local/bin/ip-ban {$ip} &");
    }

    protected function abortRequest() {
        // exit('Service is temporary unavailable');
    }
}

$requests = readRequests('secure-requests.tests');
$fixtures = readResponse('secure-response.tests');

foreach($requests as $i => $request) {
    $_SERVER = array_merge($_SERVER, $request);
    $secure = new SecurityTest();
    $secure->check();
    if ($fixtures[$i] !== $secure->getLog()) {
        echo 'FAILED!';
        print_r(array('line' => $i + 1, 'fixture' => $fixtures[$i], 'log' => $secure->getLog(), 'request' => $request));
    }
}

function readRequests($file) {
    $lines = file($file);
    $fixtures = array();
    foreach($lines as $line) {
        list($ip, $params) = explode(' - - ', $line);
        list($httpMethod, $url) = explode(' ', explode('"', $params)[1]);
        $fixtures[] = array(
            'REQUEST_METHOD' => $httpMethod,
            'REMOTE_ADDR' => $ip,
            'REQUEST_URI' => $url,
        );
    }
    // print_r($fixtures);
    return $fixtures;
}

function readResponse($file) {
    $lines = file($file);
    $fixtures = array();
    foreach($lines as $line) {
        $line = trim($line);
        if (!$line || $line === 'OK') {
            $fixtures[] = array();
        } else {
            $fixtures[] = explode(' | ', $line);
        }
    }
    // print_r($fixtures);
    return $fixtures;
}
