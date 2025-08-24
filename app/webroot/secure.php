<?
class Security {
    const NOTE = 0;
    const WARN = 1;
    const CRIT = 2;

    const LEVEL = array(
        self::NOTE => 'NOTE!',
        self::WARN => 'WARN!',
        self::CRIT => 'CRIT!',
    );

    const LOG_FILE = 'secure.log';
    const BAN_FILE = 'secure-banned.log';

    // allowed: GET, POST, PUT
    private $aHttpMethods = array(
        self::CRIT => array('OPTIONS', 'TRACE', 'CONNECT'),
        self::WARN => array('DELETE', 'HEAD', 'PATCH'),
    );

    private $aStopLines = array(
        self::CRIT => array(),
        self::WARN => array(
            '/api',
            '/docs',
            '/feed',
        ),
    );
    private $aStopWords = array(
        self::CRIT => array(
            // most used
            '/wp/',
            '/wp-',
            '.well-known',
            '.git',
            '.env',

            'config',
            'xmlrpc',
            'backup',
            'package',
            'wordpress',
            'phpmyadmin',
            'node_modules',
            'credentials',
            'production',
            'install',
            '/aws/',
            '/docker',
            '/cgi-bin',
            '/conf',
            '/cron',
            '/wp2/',
            '/application',
            '/app/',
            '.aws',
            '.github',
            '.vscode',
            '.idea',
            '.circleci',
            '.dump',
        ),
        self::WARN => array(),
    );

    private $aExtWhiteList = array('html', 'css', 'js', 'ico', 'txt', 'jpg', 'jpeg', 'jfif', 'png', 'gif', 'pdf', 'csv');
    private $aStopExt = array(
        self::CRIT => array('php', 'sql', 'py', 'exe'),
        self::WARN => array('xsd', 'yml', 'yaml', 'zip', 'rar', 'bak'),
    );

    private $url = '', $pathInfo = array();

    public function __construct() {
        $this->getURL();
    }

    public function check() {
        try {
            // check all CRIT issues first
            foreach(array(self::CRIT, self::WARN) as $level) {
                $this->checkHeaders($level, $_SERVER['REQUEST_METHOD'], $this->aHttpMethods[$level]);
                $this->checkStopLine($level, $this->getURL(), $this->aStopLines[$level]);
                $this->checkStopWord($level, $this->getURL(), $this->aStopWords[$level]);

                if ($level === self::CRIT) { // check this issues only once
                    $this->checkDotURL($this->getURL());
                }
            }
        } catch (Exception $e) {
            $this->logIssue($_SERVER['REMOTE_ADDR'], self::LEVEL[$e->getCode()], $e->getMessage());
            switch ($e->getCode()) {
                case self::CRIT:
                    $this->blockIP($_SERVER['REMOTE_ADDR']);
                case self::WARN:
                    // in both cases terminate request
                    $this->abortRequest('Service is temporary unavailable');
            }
        }
    }

    private function checkHeaders($level, $httpMethod, $aMethods) {
        $issue = ($level === self::CRIT)
            ? "Dangerous HTTP method '{$httpMethod}'"
            : "Unsupported HTTP method '{$httpMethod}'";
        if (in_array($httpMethod, $aMethods)) {
            throw new Exception($issue, $level);
        }
    }

    private function checkStopLine($level, $url, $aStopLines) {
        foreach($aStopLines as $stopLine) {
            if ($this->startsWith($url, $stopLine)) {
                throw new Exception("Stop line '$stopLine'", $level);
            }
        }
    }

    private function checkStopWord($level, $url, $aStopWords) {
        foreach($aStopWords as $stopWord) {
            if ($this->contains($url, $stopWord)) {
                throw new Exception("Stop word '$stopWord'", $level);
            }
        }
    }

    function checkDotURL($url) {
        if (!$this->contains($url, '.')) {
            return;
        }

        if (!$this->pathInfo) {
            $this->pathInfo = pathinfo($url);
        }
        $pathInfo = $this->pathInfo;

        // TODO: refactor to respect cakePHP's named params
        // for ex. /AdminProducts/index/Product/sort:Category.title/limit:100/
        $dirname = $pathInfo['dirname'];
        if (strpos($dirname, '.') !== false) { // only admin or products URLs can contain sort or filters with '.'
            $aWhiteList = array(
                '/admin',
                '/products'
            );
            foreach($aWhiteList as $skip) {
                if ($this->startsWith($url, $skip)) {
                    return;
                }
            }
            throw new Exception("Unsupported folder '$dirname'", self::WARN);
        }

        $ext = strtolower($pathInfo['extension']);
        if (in_array($ext, $this->aExtWhiteList)) {
            return;
        }

        if (in_array($ext, $this->aStopExt[self::CRIT])) {
            throw new Exception('Dangerous extension *.'.$ext, self::CRIT);
        }
        if (in_array($ext, $this->aStopExt[self::WARN])) {
            throw new Exception('Unsupported extension *.'.$ext, self::WARN);
        }

        // check *.xml - only sitemap*.xml is allowed
        if ($ext === 'xml') {
            if ($this->contains($url, '/sitemap')) {
                return;
            }
            throw new Exception('Unsupported *.XML', self::WARN);
        }

        // json-s - needs to be checked
        // TODO: refactor our ajax-requests to have placeholder
        if ($ext === 'json') {
            // skip our own ajax calls
            $aWhiteList = array(
                '/media/ajax',
                '/adminajax'
            );
            foreach($aWhiteList as $skip) {
                if ($this->startsWith($url, $skip)) {
                    return;
                }
            }
            throw new Exception('Unsupported *.JSON', self::WARN);
        }

        /* exclude URLs like:
         /zapchasti/fendt/fendt/toplivoprovod-f-f339.202.060.090
         /AdminProducts/index/Product/sort:Category.title
        */
        $aWhiteList = array(
            '/admin',
            '/zapchasti',
            '/products',
        );
        foreach($aWhiteList as $skip) {
            if ($this->startsWith($url, $skip)) {
                return;
            }
        }
        throw new Exception('Found suspicious file extension *.'.$ext, self::WARN);
    }

    private function startsWith($str, $key) {
        return strpos($str, $key) === 0;
    }

    private function contains($str, $key) {
        return strpos($str, $key) !== false;
    }

    private function getURL() {
        if ($this->url) {
            return $this->url;
        }
        $url = strtolower($_SERVER['REQUEST_URI']);
        if (strpos($url, '?') !== false) {
            list($url) = explode('?', $url);
        }
        $this->url = $url;
        return $this->url;
    }

    protected function logIssue($ip, $level, $message) {
        // format our log exactly as Apache log too speed up a search of an original log-record in Apache log
        $nowDate = date('d/M/Y:H:i:s +0200', $_SERVER['REQUEST_TIME']);
        $timeConsumed = round(microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 3); // count time consumed from getting request (Apache handler start)
        file_put_contents(self::LOG_FILE, "{$ip} - - [{$nowDate}] {$level} {$message} URL: {$_SERVER['REQUEST_URI']} ({$timeConsumed} s)\r\n", FILE_APPEND);
    }

    protected function blockIP($ip) {
        // log this ip that we block
        file_put_contents(self::BAN_FILE, $ip."\r\n", FILE_APPEND);

        // ban this IP
        system("/usr/local/bin/ip-ban {$ip} &");
    }

    protected function abortRequest() {
        exit('Service is temporary unavailable');
    }
}
