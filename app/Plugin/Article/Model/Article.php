<?php
App::uses('AppModel', 'Model');
class Article extends AppModel {
	public $useTable = 'pages';

	public $validate = array(
		'title' => 'notempty'
	);

	public function beforeDelete($cascade = true) {
		if ($cascade && isset($this->hasOne) && isset($this->hasOne['Media'])) {
			$hasMedia = $this->hasOne['Media'];
			if (isset($hasMedia['conditions']) && isset($hasMedia['conditions']['Media.object_type'])) {
				// remove all media files instead of one no matter if "dependent" flag is set or not
				$conditions = array(
					'object_type' => $hasMedia['conditions']['Media.object_type'],
					'object_id' => $this->id
				);
				$aMedia = $this->Media->getList($conditions);
				foreach($aMedia as $media) {
					$this->Media->delete($media['Media']['id']);
				}
			}
		}
	}


    // fix all external links of article's body to have "nofollow" attr
	protected function processExternalUrls($html) {
        $dom = new DOMDocument();

        // Suppress errors caused by malformed HTML snippets
        libxml_use_internal_errors(true);

        // Load the text string as HTML (UTF-8 encoded)
        $header = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/></head><body>';
        $footer = '</body></html>';
        $dom->loadHTML($header.$html.$footer, LIBXML_NOERROR | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        // Clear the error buffer
        libxml_clear_errors();

        $links = [];
        // Loop through all anchor (<a>) tags
        foreach ($dom->getElementsByTagName('a') as $a) {
            $href = $a->getAttribute('href');

            // is that external URL that starts with http (except mailto etc)
            if (strpos($href, 'agromotors') === false && strpos($href, 'http') === 0) {
                if (!$a->getAttribute('rel')) {
                    $a->setAttribute('rel', 'nofollow');
                }
            }
        }
        return str_replace(array($footer, $header), '', $dom->saveHTML());
	}

	public function saveAll(array $data = null, array $options = array()) {
	    $body = Hash::get($data, $this->name.'.body'); // safely get body of model
	    if ($body) {
	        $body = $this->processExternalUrls($body);
	        $data = Hash::insert($data, $this->name.'.body', $body);
	    }

	    return parent::saveAll($data, $options);
	}
}
