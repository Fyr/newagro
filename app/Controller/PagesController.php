<?php
App::uses('AppController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('Page', 'Model');
App::uses('News', 'Model');
App::uses('SiteArticle', 'Model');

class PagesController extends AppController {
	public $name = 'Pages';
	public $uses = array('Page', 'Product', 'News');
	public $helpers = array('ArticleVars', 'Media.PHMedia', 'Core.PHTime', 'Media');

	public function home() {
		$conditions = array('News.published' => 1);
		if ($this->aEvents) {
			$conditions['News.id <> '] = $this->aEvents[0]['News']['id'];
		}
		$aNews = $this->News->find('all', array(
			'conditions' => $conditions,
			'order' => array('News.featured DESC', 'News.created DESC'),
			'limit' => 3
		));
		$this->set('aHomePageNews', $aNews);
		/*
		$aID = array();
		foreach($this->aFeaturedProducts as $article) {
			$aID[] = $article['Article']['id'];
		}
		$aFeaturedProducts = $this->Article->find('all', array(
			'conditions' => array('Article.object_type' => 'products', 'Article.featured' => 1, 'Article.published' => 1, 'NOT' => array('Article.id' => $aID)),
			'order' => 'Article.created DESC',
			'limit' => 3
		));

		$this->set('aFeaturedProducts2', $aFeaturedProducts);

		$aID = array();
		foreach($aFeaturedProducts as $row) {
			$aID[] = $row['Article']['id'];
		}
		$aLastProducts = $this->Article->find('all', array(
			'conditions' => array('Article.object_type' => 'products', 'Article.published' => 1, 'NOT' => array('Article.id' => $aID)),
			'order' => 'Article.created DESC',
			'limit' => 8
		));
		$this->set('aLastProducts', $aLastProducts);

		$aArticle = $this->SitePage->findByPageId('home');
		$this->set('contentArticle', $aArticle);

		$this->pageTitle = (isset($aArticle['Seo']['title']) && $aArticle['Seo']['title']) ? $aArticle['Seo']['title'] : $aArticle['Article']['title'];
		$this->data['SEO'] = $aArticle['Seo'];
		*/
		$aArticle = $this->Page->findBySlug('home');
		$this->set('contentArticle', $aArticle);
	}
	
	public function show($slug) {
		$aArticle = $this->Page->findBySlug($slug);
		if (!$aArticle) {
			$this->redirect('/404');
			return;
		}
		$this->set('aArticle', $aArticle);
		/*
		if (in_array($slug, array('dealers', 'remont', 'about-us', 'about-us2', 'contacts1', 'contacts2'))) {
			$aCurr = array(
				'dealers' => 'partner',
				'remont' => 'remont',
				'about-us' => 'aboutus',
				'about-us2' => 'aboutus',
				'contacts1' => 'contacts',
				'contacts2' => 'contacts'
			);
			$this->currMenu = $aCurr[$pageID];
			$this->currLink = $this->currMenu;
		}
		*/
		// $this->pageTitle = (isset($aArticle['Seo']['title']) && $aArticle['Seo']['title']) ? $aArticle['Seo']['title'] : $aArticle['Article']['title'];
		if (!(isset($aArticle['Seo']) & isset($aArticle['Seo']['title']) && $aArticle['Seo']['title'])) {
			$aArticle['Seo']['title'] = $aArticle['Page']['title'];
		}
		
		$this->seo = $aArticle['Seo'];
		$this->currMenu = $slug;
	}
}
