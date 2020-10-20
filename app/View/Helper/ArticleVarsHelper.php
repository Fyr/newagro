<?php
App::uses('AppHelper', 'View/Helper');
App::uses('SiteRouter', 'Lib/Routing');
class ArticleVarsHelper extends AppHelper {
	public $helpers = array('Media');

	public function init($article, &$url, &$title, &$teaser = '', &$src = '', $size = 'noresize', &$featured = false, &$id = '') {
		$objectType = $this->getObjectType($article);
		$id = $article[$objectType]['id'];
		
		$url = SiteRouter::url($article);
		
		$title = $article[$objectType]['title'];
		$zone = Configure::read('domain.zone');
		$field = ($zone != 'by' && in_array($objectType, array('Brand', 'Category', 'Subcategory'))) ? 'teaser_'.$zone : 'teaser';
		$teaser = nl2br($article[$objectType][$field]);
		$src = $this->Media->imageUrl($article, $size);
		$featured = $article[$objectType]['featured'];
	}

	public function body($article) {
		$objectType = $this->getObjectType($article);
		$zone = Configure::read('domain.zone');
		$field = ($zone != 'by' && in_array($objectType, array('Brand', 'Category', 'Subcategory', 'Product'))) ? 'body_'.$zone : 'body';
		return $article[$this->getObjectType($article)][$field];
	}

	public function httpsUrl($url) {
		$https = (isset($_SERVER['REQUEST_SCHEME']) && $_SERVER['REQUEST_SCHEME'] == 'https') ? 'https' : 'http';
		return str_replace('http://', $https.'://', $url);
	}
}
