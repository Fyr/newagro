<?php
App::uses('AppController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('Page', 'Model');
App::uses('News', 'Model');
App::uses('SiteArticle', 'Model');

class PagesController extends AppController {
	public $name = 'Pages';
	public $uses = array('Page', 'News');
	public $helpers = array('ArticleVars', 'Media.PHMedia', 'Core.PHTime', 'Media');

	public function home() {
		$this->set('isHomePage', true);
		
		$conditions = array('News.published' => 1);
		if ($this->aEvents) {
			$conditions['NOT'] = array('News.id' => Hash::extract($this->aEvents, '{n}.News.id'));
		}
		$aNews = $this->News->find('all', array(
			'conditions' => $conditions,
			'order' => array('News.featured DESC', 'News.sorting ASC', 'News.created DESC'),
			'limit' => 3
		));
		$this->set('aHomePageNews', $aNews);
		
		$aArticle = $this->Page->findBySlug('home');
		$this->set('contentArticle', $aArticle);
		
		if (!(isset($aArticle['Seo']) & isset($aArticle['Seo']['title']) && $aArticle['Seo']['title'])) {
			$aArticle['Seo']['title'] = $aArticle['Page']['title'];
		}
		$this->seo = $aArticle['Seo'];
	}
	
	public function show($slug) {
		$aArticle = $this->Page->findBySlug($slug);
		if (!$aArticle) {
			$this->redirect404();
			return;
		}
		$this->set('aArticle', $aArticle);
		
		if (!(isset($aArticle['Seo']) & isset($aArticle['Seo']['title']) && $aArticle['Seo']['title'])) {
			$aArticle['Seo']['title'] = $aArticle['Page']['title'];
		}
		$this->seo = $aArticle['Seo'];
		$this->currMenu = $slug;
	}

	public function nonExist() {
		$this->render('/Errors/error400');
	}
}
