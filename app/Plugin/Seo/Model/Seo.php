<?php
App::uses('AppModel', 'Model');
class Seo extends AppModel {
	public $useTable = 'seo';

	public function defaultSeo($aSeo, $defaultTitle = '', $defaultKeywords = '', $defaultDescr = '') {
		if (!(isset($aSeo['title']) && $aSeo['title']) && $defaultTitle) {
			$aSeo['title'] = $defaultTitle;
		}
		if (!(isset($aSeo['keywords']) && $aSeo['keywords']) && $defaultKeywords) {
			$aSeo['keywords'] = $defaultKeywords;
		}
		if (!(isset($aSeo['descr']) && $aSeo['descr']) && $defaultDescr) {
			$aSeo['descr'] = $defaultDescr;
		}
		return $aSeo;
	}

}
