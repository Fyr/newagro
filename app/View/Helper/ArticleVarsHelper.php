<?php
App::uses('AppHelper', 'View/Helper');
App::uses('SiteRouter', 'Lib/Routing');
class ArticleVarsHelper extends AppHelper {
	public $helpers = array('Html', 'Media');

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
		// TODO: протестить
		if (strpos($url, 'http') === false) {
			$url.= HTTP;
		}
		return str_replace('http://', HTTP, $url);
	}

	public function fixPhone($phone) {
		return str_replace(array('-', ' ', '(' , ')', '+'), '', preg_replace('/[^0-9]/', '', $phone));
	}

	public function callableLink($type, $phoneOrUID, $xOptions = array()) {
		$options = array_merge(array('class' => "callable ${type}"), $xOptions);
		$url = $phoneOrUID;
		switch ($type) {
			case 'tel': 
				$url = 'tel:'.str_replace('375', '+375', $this->fixPhone(str_replace('+7', '8', $phoneOrUID)));
				break;
			case 'whatsapp': 
				$url = 'https://api.whatsapp.com/send?phone='.$this->fixPhone($phoneOrUID);
				break;
			case 'viber':
				$url = 'viber://chat?number='.$phoneOrUID;
				break;
			case 'telegram':
				$url = 'tg://resolve?domain='.$phoneOrUID;
				break;
			case 'skype':
				$url = 'skype:'.$phoneOrUID;
				break;
		}
		return $this->Html->link($phoneOrUID, $url, $options);
	}
}
