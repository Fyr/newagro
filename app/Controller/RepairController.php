<?php
App::uses('AppController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('Page', 'Model');
App::uses('News', 'Model');
App::uses('SiteArticle', 'Model');

class RepairController extends AppController {
	public $name = 'Repair';
	public $uses = array('Page', 'RepairArticle');
	public $helpers = array('ArticleVars', 'Media.PHMedia', 'Core.PHTime', 'Media');

	public function index($slug = '') {
		$objectType = 'Page';
		$cat_id = 0;
		if (!$slug) {
			$aArticle = $this->Page->findBySlug('remont');
		} else {
			$aArticle = $this->RepairArticle->findBySlug($slug);
			$cat_id = Hash::get($aArticle, 'RepairArticle.id');
			$objectType = 'RepairArticle';
		}

		if (!$aArticle) {
			$this->redirect404();
			return;
		}
		$this->set('aArticle', $aArticle);
		if (!(isset($aArticle['Seo']) & isset($aArticle['Seo']['title']) && $aArticle['Seo']['title'])) {
			$aArticle['Seo']['title'] = $aArticle[$objectType]['title'];
		}
		$this->seo = $aArticle['Seo'];

		$this->set('articles', $this->RepairArticle->findAllByCatId($cat_id));
		$this->currMenu = 'remont';
	}
}
