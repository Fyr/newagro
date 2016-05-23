<?php
App::uses('AppModel', 'Model');
App::uses('Seo', 'Seo.Model');
class SeoArticle extends Seo {
	public $useDbConfig = 'vitacars';
	public $useTable = 'seo';

	public function defaultSeo($aSeo, $defaultTitle = '', $defaultKeywords = '', $defaultDescr = '') {
		$title = 'title_'.Configure::read('domain.zone');
		if (!(isset($aSeo[$title]) && $aSeo[$title]) && $defaultTitle) {
			$aSeo['title'] = $defaultTitle;
		} else {
			$aSeo['title'] = $aSeo[$title];
		}
		$keywords = 'keywords_'.Configure::read('domain.zone');
		if (!(isset($aSeo[$keywords]) && $aSeo[$keywords]) && $defaultKeywords) {
			$aSeo['keywords'] = $defaultKeywords;
		} else {
			$aSeo['keywords'] = $aSeo[$keywords];
		}
		$descr = 'descr_'.Configure::read('domain.zone');
		if (!(isset($aSeo[$descr]) && $aSeo[$descr]) && $defaultDescr) {
			$aSeo['descr'] = $defaultDescr;
		} else {
			$aSeo['descr'] = $aSeo[$descr];
		}

		return $aSeo;
	}
}
